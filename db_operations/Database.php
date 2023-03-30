<?php

namespace App;

class Database
{
    private string $host        = "host = 127.0.0.1";
    private string $port        = "port = 5432";
    private string $dbname      = "dbname = ecommerce_db";
    private string $credentials = "user = postgres password=postgres";
    private $db;
    // private $sql;
    // private $ret;

    public function __construct()
    {
        $this->db = pg_connect($this->host . " " . $this->port . " " . $this->dbname . " " . $this->credentials);
        if (!$this->db) {
            echo "Error : Unable to open database\n";
        }
    }

    public function __destruct()
    {
        pg_close($this->db);
    }

    public function postgres_query($sql)
    {
        try {
            $ret = pg_query($this->db, $sql);
            if (!$ret) {
                throw new \Exception(pg_last_error($this->db));
            } else {
                return "Success";
            }
        } catch (\Exception $e) {
            $err = $e->getMessage();
            return $err;
        }
    }

    public function postgres_query_row($sql)
    {
        try {
            $ret = pg_query($this->db, $sql);
            if (!$ret) {
                throw new \Exception(pg_last_error($this->db));
            } else {
                $row = pg_fetch_row($ret);
                return $row;
            }
        } catch (\Exception $e) {
            $err = $e->getMessage();
            echo $err;
            return $err;
        }
    }
    public function postgres_query_all($sql)
    {
        try {
            $ret = pg_query($this->db, $sql);
            if (!$ret) {
                throw new \Exception(pg_last_error($this->db));
            } else {
                $row = pg_fetch_all($ret);
                return $row;
            }
        } catch (\Exception $e) {
            $err = $e->getMessage();
            echo $err;
            return $err;
        }
    }

    public function postgres_query_execute($sql)
    {
        $ret = pg_query($this->db, $sql);
        if (!$ret) {
            throw new \Exception(pg_last_error($this->db));
        } else {
            $row = pg_fetch_all($ret);
            return $row;
        }
    }

    public function addUser($userData)
    {
        try {
            $sql = <<<EOF
                INSERT INTO users (firstName,lastName,email,password,phoneNumber,verifiedUser,token)
                VALUES ('{$userData['firstname']}', '{$userData['lastname']}', '{$userData['email']}', '{$userData['password']}', {$userData['phonenumber']}, false, '{$userData['token']}') RETURNING u_id;
            EOF;

            $ret = pg_query($this->db, $sql);
            if (!$ret) {
                throw new \Exception(pg_last_error($this->db));
            } else {
                $data = pg_fetch_row($ret);
                $u_id = $data[0];
                return $u_id;
            }
        } catch (\Exception $e) {
            $err = $e->getMessage();
            echo $err;
            return $err;
        }
    }
    public function addSeller($sellerData)
    {
        try {
            $sql = <<<EOF
                INSERT INTO sellers (u_id, gst_no, businessName, businessAddress, token)
                VALUES ('{$sellerData['u_id']}', '{$sellerData['gst_no']}', '{$sellerData['businessName']}', '{$sellerData['businessAddress']}', '{$sellerData['token']}' ) RETURNING s_id;
            EOF;

            $ret = pg_query($this->db, $sql);
            if (!$ret) {
                throw new \Exception(pg_last_error($this->db));
            } else {
                $data = pg_fetch_row($ret);
                $s_id = $data[0];
                return $s_id;
            }
        } catch (\Exception $e) {
            $err = $e->getMessage();
            echo $err;
            return $err;
        }
    }

    public function loginUser($userData)
    {
        try {
            $sql = <<<EOF
                SELECT * FROM users WHERE email='{$userData['email']}' and password= '{$userData['password']}';
            EOF;

            $ret = pg_query($this->db, $sql);
            if (!$ret) {
                throw new \Exception(pg_last_error($this->db));
            } else {
                $row = pg_fetch_all($ret);
                return $row;
            }
        } catch (\Exception $e) {
            $err = $e->getMessage();
            echo $err;
            return $err;
        }
    }
    public function loginSeller($sellerData)
    {
        try {
            $sql = <<<EOF
                SELECT sellers.s_id,sellers.u_id,users.firstName,users.lastName, users.verifiedUser FROM sellers inner join users on sellers.u_id=users.u_id and users.email='{$sellerData['email']}' and users.password= '{$sellerData['password']}';
            EOF;

            $ret = pg_query($this->db, $sql);
            if (!$ret) {
                throw new \Exception(pg_last_error($this->db));
            } else {
                $row = pg_fetch_all($ret);
                return $row;
            }
        } catch (\Exception $e) {
            $err = $e->getMessage();
            echo $err;
            return $err;
        }
    }

    public function confirmOrder($cartList, $totalPrice, $a_id)
    {
        try {
            //Begin transaction
            $sql = <<<EOF
                BEGIN;
                EOF;
            $response = $this->postgres_query_execute($sql);

            //Fetch user address using a_id
            $sql = <<<EOF
                SELECT address FROM address WHERE a_id={$a_id}
                EOF;
            $response = $this->postgres_query_execute($sql);

            //Insert order data into orders table
            $address = $response[0]['address'];
            $transactionId = time();
            $sql = <<<EOF
                INSERT INTO orders(u_id, shippingAddress, transactionId, totalPrice) VALUES({$_SESSION['u_id']},'{$address}','{$transactionId}', {$totalPrice}) returning o_id;
                EOF;
            $response = $this->postgres_query_execute($sql);

            $o_id = $response[0]['o_id'];
            //Iterate over every item
            for ($x = 0; $x < count($cartList); $x++) {

                //Select item details from cart and products table
                $sql = <<<EOF
                    SELECT cart.quantity, products.p_id, products.price, products.stock, products.status FROM cart INNER JOIN products ON cart.p_id=products.p_id and cart.c_id={$cartList[$x]};
                    EOF;
                $response = $this->postgres_query_execute($sql);

                //Update stock in products table
                $quantity = $response[0]['quantity'];
                $p_id = $response[0]['p_id'];
                $price = $response[0]['price'];
                $stock = $response[0]['stock'];
                $newQuantity = $stock - $quantity;

                $sql = <<<EOF
                    UPDATE products SET stock={$newQuantity} WHERE p_id={$p_id}
                    EOF;

                $response = $this->postgres_query_execute($sql);

                $time = time();
                // $date = date('Y/m/d h:i:s a', time());

                //Insert items into orderItems table
                $sql = <<<EOF
                    INSERT INTO orderItems(o_id, p_id, quantity, price, status) VALUES($o_id, $p_id, $quantity, $price, '{"status": "ordered"}');
                EOF;

                $response = $this->postgres_query_execute($sql);

                //Remove items from cart table
                $sql = <<<EOF
                    DELETE FROM cart WHERE c_id={$cartList[$x]}
                    EOF;
            }
            $response = $this->postgres_query_execute($sql);

            //Commit transaction
            $sql = <<<EOF
                COMMIT;
                EOF;
            $response = $this->postgres_query_execute($sql);
            return "Success";
        } catch (\Exception $e) {
            $err = $e->getMessage();
            echo $err;
            return $err;
        }
    }
}

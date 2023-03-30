<?php

declare(strict_types=1);

namespace App;

require_once "db_operations/Database.php";
require_once "User.php";
require_once "MailVerification.php";

class Buyer implements User
{
    public function displayHome()
    {
        if (isset($_SESSION['u_id'])) {
            require_once "views/home.php";
        } else {
            require_once "views/app.php";
        }
    }
    public function displaySignup()
    {
        if (isset($_SESSION['u_id'])) {
            require_once "views/home.php";
        } else {
            require_once "views/signup.php";
        }
    }

    public function displayLogin()
    {
        if (isset($_SESSION['u_id'])) {
            require_once "views/home.php";
        } else {
            require_once "views/login.php";
        }
    }

    public function displayCart()
    {
        if (isset($_SESSION['u_id']) && isset($_SESSION['isVerified'])) {
            require_once "views/cart.php";
        } else if (isset($_SESSION['u_id']) && isset($_SESSION['isVerified'])) {
            require_once "views/verifyPage.php";
        } else {
            require_once "views/login.php";
        }
    }
    public function displayOrdersPage()
    {
        if (isset($_SESSION['u_id']) && isset($_SESSION['isVerified'])) {
            require_once "views/orders.php";
        } else if (isset($_SESSION['u_id']) && !isset($_SESSION['isVerified'])) {
            require_once "views/verifyPage.php";
        } else {
            require_once "views/login.php";
        }
    }
    public function displayOrderHistory()
    {
        if (isset($_SESSION['u_id']) && isset($_SESSION['isVerified'])) {
            require_once "views/orderHistory.php";
        } else if (isset($_SESSION['u_id']) && !isset($_SESSION['isVerified'])) {
            require_once "views/verifyPage.php";
        } else {
            require_once "views/login.php";
        }
    }

    public function signup($data)
    {
        $obj = new Database();
        // $data['status'] = 0;

        //First Check if user exists or not
        $sql = <<<EOF
        SELECT * FROM users WHERE email='{$data['email']}' and password= '{$data['password']}';
        EOF;
        $response = $obj->postgres_query_row($sql);

        if (is_array($response) && count($response) > 0) {
            $res = new \stdClass();
            $res->msg = "isUser";
            echo json_encode($res);
            exit;
        } else {
            $data['token'] = time();
            $u_id = $obj->addUser($data);
            if ($u_id) {
                $u_name = $data['firstname'] . " " . $data['lastname'];
                $subject = "Account Verification for E-commerce";
                $html = <<<EOF
                    <h3> Dear {$u_name}, Please click on the given link to verify your account <a href="http://localhost:8000/verifyMail?token={$data['token']}"> Verify Me!</a></h3><br/>
                    EOF;
                $auth = new \Authentication();
                // $response2 = $auth->sendEmail("sahilverma2642@gmail.com", $subject, $html);
                $response2 = $auth->sendEmail($data['email'], $subject, $html);
                if ($response2 === "Success") {
                    $_SESSION["u_id"] = $u_id;
                    $_SESSION['u_status'] = "buyer";
                    $_SESSION['u_name'] = $u_name;

                    $res = new \stdClass();
                    $res->msg = "Success";
                    echo json_encode($res);
                    exit;
                } else {
                    $res = new \stdClass();
                    $res->msg = "Failure";
                    echo json_encode($res);
                    exit;
                }
            } else {
                $res = new \stdClass();
                $res->msg = "Failure";
                echo json_encode($res);
                exit;
            }
        }
    }

    public function login($userData)
    {
        $obj = new Database();

        //     $sql = <<<EOF
        //     SELECT * FROM users WHERE email='{$data['email']}' and password= '{$data['password']}' and status= {$data['status']};
        // EOF;

        $data = $obj->loginUser($userData);

        if (is_array($data) && count($data) > 0) {

            if ($userData['email'] === $data[0]['email']) {
                $_SESSION['u_id'] = $data[0]['u_id'];
                $_SESSION['u_status'] = "buyer";
                $_SESSION['u_name'] = $data[0]['firstname'] . " " . $data[0]['lastname'];
                $_SESSION['isVerified'] = $data[0]['verifieduser'];

                if ($data[0]['verifieduser'] === false) {
                    $res = new \stdClass();
                    $res->msg = "pleaseVerify";
                    echo json_encode($res);
                    exit;
                } else {
                    $res = new \stdClass();
                    $res->msg = "Success";
                    echo json_encode($res);
                    exit;
                }
            } else {
                $res = new \stdClass();
                $res->msg = "wrongCreditentals";
                echo json_encode($res);
                exit;
            }
        } else {
            $res = new \stdClass();
            $res->msg = "Failure";
            echo json_encode($res);
            exit;
        }
    }

    public function logout()
    {
        session_destroy();
        // $page = $_SERVER['PHP_SELF'];
        $page = "/";
        $sec = "0.1";
        header("Refresh: $sec; url=$page");
    }

    public function displayResetPassword()
    {
        require_once "views/changePassword.php";
    }

    public function displayForgotPassword()
    {
        require_once "views/forgotPassword.php";
    }

    public function addToCart($data)
    {
        if (isset($_SESSION['u_id']) && isset($_SESSION['isVerified'])) {
            $obj = new Database();
            $data['u_id'] = $_SESSION['u_id'];


            //check if item already exists in cart
            $sql = <<<EOF
        SELECT c_id, quantity FROM cart WHERE u_id={$data['u_id']} and p_id= {$data['p_id']};
        EOF;
            $response = $obj->postgres_query_row($sql);

            if (is_array($response) && count($response) > 0) {
                $c_id = $response[0];
                $data['c_id'] = $c_id;
                $oldQuantity = $response[1];
                $newQuantity = $data['quantity'] + $oldQuantity;
                $data['quantity'] = $newQuantity;

                //check if enough stock is present
                $sql = <<<EOF
                SELECT stock,price FROM products where p_id ={$data['p_id']};
                EOF;
                $response2 = $obj->postgres_query_row($sql);

                if ($response2 !== false) {
                    $stock = $response2[0];
                    $price = $response2[1];

                    if ($newQuantity === 0) {
                        $res = new \stdClass();
                        $res->msg = "removeFromCart";
                        $res->c_id = $c_id;
                        $res->price = $price;
                        echo json_encode($res);
                        exit;
                    } else if ($stock - $newQuantity >= 0) {
                        $sql = <<<EOF
            update cart set quantity= {$data['quantity']} where c_id={$data['c_id']};
            EOF;
                        $response3 = $obj->postgres_query($sql);

                        if ($response3 === "Success") {
                            $res = new \stdClass();
                            $res->msg = "Success";
                            $res->quantity = $newQuantity;
                            $res->c_id = $c_id;
                            $res->price = $price;
                            echo json_encode($res);
                            exit;
                        } else {
                            //throw error
                        }
                    } else {
                        $res = new \stdClass();
                        $res->msg = "outOfStock";
                        echo json_encode($res);
                        exit;
                    }
                } else {
                    $res = new \stdClass();
                    $res->msg = "Failure";
                    echo json_encode($res);
                    exit;
                }
            } else if ($response === false) {
                $sql = <<<EOF
            INSERT INTO cart(u_id, p_id, quantity) values({$data['u_id']} ,{$data['p_id']}, {$data['quantity']});
            EOF;
                $response2 = $obj->postgres_query($sql);

                if ($response2 === "Success") {
                    $res = new \stdClass();
                    $res->msg = "Success";
                    echo json_encode($res);
                    exit;
                } else {
                    // throw new \Exception();
                }
            } else {
                $res = new \stdClass();
                $res->msg = "Failure";
                echo json_encode($res);
                exit;
            }
        } else if (isset($_SESSION['u_id']) && !isset($_SESSION['isVerified'])) {
            $res = new \stdClass();
            $res->msg = "verify";
            echo json_encode($res);
            exit;
        } else {
            $res = new \stdClass();
            $res->msg = "login";
            echo json_encode($res);
            exit;
        }
    }

    public function getCartItems()
    {
        $obj = new Database();
        $u_id = $_SESSION['u_id'];

        $sql = <<<EOF
        SELECT cart.*, products.* FROM cart INNER JOIN products on cart.p_id=products.p_id  and cart.u_id={$u_id};
        EOF;
        $response = $obj->postgres_query_all($sql);

        if (is_array($response) && count($response) > 0) {
            $res = new \stdClass();
            $res->msg = "Success";
            $res->data = $response;
            echo json_encode($res);
            exit;
        } else {
            $res = new \stdClass();
            $res->msg = "Failure";
            echo json_encode($res);
            exit;
        }
    }

    public function getCartItem($data)
    {
        var_dump($data);
        $obj = new Database();

        $sql = <<<EOF
        SELECT cart.*, products.* FROM cart INNER JOIN products on cart.p_id=products.p_id  and cart.c_id= {$data['c_id']};
        EOF;
        $response = $obj->postgres_query_all($sql);

        if (is_array($response) && count($response) > 0) {
            $res = new \stdClass();
            $res->msg = "Success";
            $res->data = $response;
            echo json_encode($res);
            exit;
        } else {
            $res = new \stdClass();
            $res->msg = "Failure";
            echo json_encode($res);
            exit;
        }
    }

    public function removeFromCart($data)
    {
        $obj = new Database();

        $sql = <<<EOF
        DELETE FROM cart WHERE c_id= {$data['c_id']};
        EOF;
        $response = $obj->postgres_query($sql);

        if ($response === "Success") {
            $res = new \stdClass();
            $res->msg = "Success";
            echo json_encode($res);
            exit;
        } else {
            $res = new \stdClass();
            $res->msg = "Failure";
            echo json_encode($res);
            exit;
        }
    }

    public function placeOrder($orderData)
    {
        $data = $orderData['items'];
        $price = $orderData['price'];
        $_SESSION['cart'] = array();
        $_SESSION['price'] = $price;

        foreach ($data as $value) {
            array_push($_SESSION['cart'], (int)$value);
        }
        if (isset($_SESSION['cart'])) {
            $res = new \stdClass();
            $res->msg = "Success";
            echo json_encode($res);
            exit;
        } else {
            $res = new \stdClass();
            $res->msg = "Failure";
            echo json_encode($res);
            exit;
        }
    }

    public function clearOrder()
    {
        if (isset($_SESSION['cart']) && isset($_SESSION['price'])) {
            unset($_SESSION['cart']);
            unset($_SESSION['price']);
        }
    }

    public function getOrderInfo()
    {
        if (isset($_SESSION['cart'])) {
            $items[] = array();

            foreach ($_SESSION['cart'] as $key => $value) {
                // array_push($items, $value);  --> Create a 2-d array
                $items["$key"] = $value;
            }

            $res = new \stdClass();
            $res->msg = "Success";
            $res->items = $items;
            $res->price = $_SESSION['price'];
            echo json_encode($res);
            exit;
        } else {
            $res = new \stdClass();
            $res->msg = "Failure";
            echo json_encode($res);
            exit;
        }
    }

    // public function getCurrentAddress()
    // {
    //     if (isset($_SESSION['a_id'])) {
    //         $res = new \stdClass();
    //         $res->msg = "Success";
    //         $res->data = $_SESSION['a_id'];
    //         echo json_encode($res);
    //         exit;
    //     }else{
    //         $res = new \stdClass();
    //         $res->msg = "failure";
    //         echo json_encode($res);
    //         exit;
    //     }
    // }

    // public function setAddressId($data)
    // {
    //     $_SESSION['a_id'] = $data['a_id'];
    //     $res = new \stdClass();
    //     $res->msg = "Success";
    //     echo json_encode($res);
    //     exit;
    // }

    public function addUserAddress($data)
    {
        $data['u_id'] = $_SESSION['u_id'];
        $obj = new Database();

        $sql = <<<EOF
        INSERT INTO address(u_id, address) VALUES(
        {$data['u_id']}, '{$data['address']}') returning a_id;
        EOF;
        $response = $obj->postgres_query_all($sql);

        if (is_array($response) && count($response) > 0) {
            $res = new \stdClass();
            $res->msg = "Success";
            $res->data = $response[0]['a_id'];
            echo json_encode($res);
            exit;
        } else {
            $res = new \stdClass();
            $res->msg = "Failure";
            echo json_encode($res);
            exit;
        }
    }

    public function getUserAddress()
    {
        $u_id = $_SESSION['u_id'];
        $obj = new Database();

        $sql = <<<EOF
        SELECT * FROM address WHERE u_id=$u_id;
        EOF;
        $response = $obj->postgres_query_all($sql);

        if (is_array($response) && count($response) > 0) {
            $res = new \stdClass();
            $res->msg = "Success";
            $res->data = $response;
            echo json_encode($res);
            exit;
        } else if (is_array($response) && count($response) === 0) {
            $res = new \stdClass();
            $res->msg = "noAddress";
            echo json_encode($res);
            exit;
        } else {
            $res = new \stdClass();
            $res->msg = "Failure";
            echo json_encode($res);
            exit;
        }
    }
    public function getSubTotal()
    {
        $res = new \stdClass();
        $res->msg = "Success";
        $res->data = $_SESSION['price'];
        echo json_encode($res);
        exit;
    }

    public function resetPassword($data)
    {
        $obj = new Database();

        $sql = <<<EOF
            UPDATE users SET password='{$data['newPassword']}' WHERE u_id={$_SESSION['u_id']} returning email;
        EOF;

        $response = $obj->postgres_query_row($sql);
        // var_dump($response);
        if (is_array($response) && count($response) > 0) {
            $data['email'] = $response[0];
            $u_name = $_SESSION['u_name'];
            $subject = "Password changed";
            $html = <<<EOF
                    <h3> Dear {$u_name},your password has been successfully changed.</h3><br/>"> Verify Me!</a></h3><br/>
                    EOF;
            $auth = new \Authentication();
            $response2 = $auth->sendEmail($data['email'], $subject, $html);

            if ($response2 === "Success") {
                $res = new \stdClass();
                $res->msg = "Success";
                echo json_encode($res);
                exit;
            } else {
                $res = new \stdClass();
                $res->msg = "Failure";
                echo json_encode($res);
                exit;
            }
        } else {
            $res = new \stdClass();
            $res->msg = "Failure";
            echo json_encode($res);
            exit;
        }
    }

    public function getEmail($data)
    {
        $obj = new Database();
        $sql = <<<EOF
            SELECT u_id, firstName, lastName, token FROM users WHERE email='{$data['email']}';
        EOF;

        $response = $obj->postgres_query_all($sql);

        if (is_array($response) && count($response) > 0) {
            $u_id = $response[0]['u_id'];
            $u_name = $response[0]['firstname'] . " " . $response[0]['lastname'];
            $token = $response[0]['token'];
            $_SESSION['u_id'] = $u_id;
            $_SESSION['u_status'] = 'buyer';
            $_SESSION['u_name'] = $u_name;
            $subject = "Reset your Password";
            $html = <<<EOF
                    <h3> Dear {$u_name}, Please click on the given link to reset password <a href="http://localhost:8000/verifyUser?token={$token}"> Reset Password!</a></h3><br/>
                    EOF;
            $auth = new \Authentication();

            $response2 = $auth->sendEmail($data['email'], $subject, $html);
            if ($response2 === "Success") {
                $res = new \stdClass();
                $res->msg = "Success";
                echo json_encode($res);
                exit;
            } else {
                $res = new \stdClass();
                $res->msg = "Failure";
                echo json_encode($res);
                exit;
            }
        } else {
            $res = new \stdClass();
            $res->msg = "Failure";
            echo json_encode($res);
            exit;
        }
    }

    public function verifyUser()
    {
        $token = $_GET['token'];
        var_dump($token);

        $obj = new \App\Database();
        $sql = <<<EOF
            SELECT * FROM users WHERE token='{$token}' and u_id={$_SESSION['u_id']};
        EOF;

        $data = $obj->postgres_query_row($sql);
        var_dump($data);
        if (is_array($data) && $data != false) {
            $page = "/resetPassword";
            $sec = "0.1";
            header("Refresh: $sec; url=$page");
        } else {
            var_dump("Error");
        }
    }

    public function confirmOrder($data)
    {
        $cartList = $data['cartList'];
        $totalPrice = $data['price'];
        $a_id = $data['a_id'];

        $obj = new Database();
        $response = $obj->confirmOrder($cartList, $totalPrice, $a_id);
        if ($response === "Success") {
            $this->clearOrder();
            $res = new \stdClass();
            $res->msg = "Success";
            echo json_encode($res);
            exit;
        } else {
            $res = new \stdClass();
            $res->msg = "Failure";
            echo json_encode($res);
            exit;
        }
    }

    public function getOrderedItems()
    {
        $obj = new \App\Database();
        $sql = <<<EOF
            SELECT products.productName, products.productImage, CONCAT(DATE_PART('day', orders.orderTime),'/',DATE_PART('month', orders.orderTime),'/',DATE_PART('year', orders.orderTime),'    ', DATE_PART('hour', orders.orderTime),':',DATE_PART('minute', orders.orderTime), ':',ROUND(DATE_PART('second', orders.orderTime))) AS orderTime, orders.shippingAddress, orders.transactionId, orderItems.item_id, orderItems.o_id, orderItems.p_id, orderItems.quantity, orderItems.price,orderItems.status -> 'status' AS status FROM products INNER JOIN (orders INNER JOIN orderItems ON orders.o_id=orderItems.o_id) ON products.p_id=orderItems.p_id WHERE orders.u_id={$_SESSION['u_id']} ORDER BY orders.orderTime desc;
        EOF;
        $response = $obj->postgres_query_all($sql);

        if (is_array($response) && count($response) > 0) {
            $res = new \stdClass();
            $res->msg = "Success";
            $res->data = $response;
            echo json_encode($res);
            exit;
        } else {
            $res = new \stdClass();
            $res->msg = "Failure";
            echo json_encode($res);
            exit;
        }
    }
}

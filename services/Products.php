<?php

declare(strict_types=1);

namespace App;

require_once "db_operations/Database.php";

class Products
{
    public function displayProductsToUser()
    {
        require_once "views/UserProducts.php";
    }

    public function displayAddProducts()
    {
        require_once "views/addProducts.php";
    }

    public function addProduct()
    {
        //This is the directory where images will be saved 
        $name = time();
        $target = "uploads/";
        $_FILES['productImage']['name'] = $name . "img";
        $target = $target . basename($_FILES['productImage']['name']);

        //This gets all the other information from the form 
        $productName = $_POST['productName'];
        $price = $_POST['price'];
        $productDesc = $_POST['productDesc'];
        $stock = $_POST['productStock'];
        $productImage = ($_FILES['productImage']['name']);
        $s_id = $_SESSION['s_id'];

        //Writes the information to the database 
        $obj = new Database();
        $query = <<<EOF
        INSERT INTO PRODUCTS(productName, price, productDesc, stock, productImage, s_id) VALUES ('{$productName}', {$price}, '{$productDesc}', {$stock}, '{$productImage}',{$s_id}) returning p_id;
        EOF;

        $response = $obj->postgres_query_all($query);

        if (is_array($response) && count($response) > 0) {
            if (move_uploaded_file($_FILES['productImage']['tmp_name'], $target)) {
                return $response[0]['p_id'];
            } else {
                return "Error";
            }
        } else {
            return $response;
        }
    }

    public function getProducts()
    {
        $s_id = $_SESSION["s_id"];
        $obj = new Database();
        $sql = <<<EOF
                SELECT * FROM products WHERE s_id= {$s_id};
            EOF;

        $products = $obj->postgres_query_all($sql);

        if (is_array($products)) {
            return $products;
        } else {
            return "Error";
        }
    }

    public function getNProducts($data)
    {
        $obj = new Database();
        $sql = <<<EOF
               SELECT * FROM products ORDER BY p_id LIMIT 5 OFFSET {$data['index']};
            EOF;

        $products = $obj->postgres_query_all($sql);

        if (is_array($products)) {
            $res = new \stdClass();
            $res->msg = "Success";
            $res->data = $products;
            echo json_encode($res);
        } else {
            $res = new \stdClass();
            $res->msg = "Failure";
            echo json_encode($res);
        }
    }

    public function getProductInfo($data)
    {
        $obj = new Database();
        $sql = <<<EOF
               SELECT * FROM products WHERE p_id= {$data['p_id']};
            EOF;

        $product = $obj->postgres_query_all($sql);

        if (is_array($product)) {
            $res = new \stdClass();
            $res->msg = "Success";
            $res->data = $product[0];
            echo json_encode($res);
        } else {
            $res = new \stdClass();
            $res->msg = "Failure";
            echo json_encode($res);
        }
    }

    public function updateProduct()
    {
        $productName = $_POST['productName'];
        $price = $_POST['price'];
        $productDesc = $_POST['productDesc'];
        $stock = $_POST['productStock'];
        $p_id = $_POST['p_id'];
        $productImage = " ";
        $target = " ";

        if (isset($_FILES['productImage']['name'])) {
            //This is the directory where images will be saved 
            $name = time();
            $_FILES['productImage']['name'] = $name . "img";
            $productImage = ($_FILES['productImage']['name']);
            $target = "uploads/" . basename($_FILES['productImage']['name']);
        } else {
            $productImage = $_POST['productImageUrl'];
        }

        $obj = new Database();
        $sql = <<<EOF
               UPDATE products SET productName='{$productName}',price={$price}, productDesc='{$productDesc}', stock= {$stock}, productImage='{$productImage}' WHERE p_id= {$p_id};
            EOF;

        $response = $obj->postgres_query($sql);

        if ($response === "Success") {
            if (isset($_FILES['productImage']['name'])) {
                if (move_uploaded_file($_FILES['productImage']['tmp_name'], $target)) {
                    return $p_id;
                } else {
                    return "Error";
                }
            } else {
                return $p_id;
            }
        } else {
            return "Error";
        }
    }

    public function getOrderedItems($data)
    {
        $orderDuration = $data["orderDuration"];
        $obj = new \App\Database();
        $sql="";

        if($orderDuration==="All"){
            $sql = <<<EOF
            SELECT products.productName, products.productImage, CONCAT(DATE_PART('day', orders.orderTime),'/',DATE_PART('month', orders.orderTime),'/',DATE_PART('year', orders.orderTime),'  ', DATE_PART('hour', orders.orderTime),':',DATE_PART('minute', orders.orderTime), ':',ROUND(DATE_PART('second', orders.orderTime))) AS orderTime, orders.shippingAddress, orders.transactionId, orderItems.item_id, orderItems.o_id, orderItems.p_id, orderItems.quantity, orderItems.price,orderItems.status -> 'status' AS status FROM products INNER JOIN (orders INNER JOIN orderItems ON orders.o_id=orderItems.o_id) ON products.p_id=orderItems.p_id WHERE orders.u_id={$_SESSION['u_id']} ORDER BY orders.orderTime desc;
            EOF;
        }else{
            $sql = <<<EOF
            SELECT products.productName, products.productImage, CONCAT(DATE_PART('day', orders.orderTime),'/',DATE_PART('month', orders.orderTime),'/',DATE_PART('year', orders.orderTime),'  ', DATE_PART('hour', orders.orderTime),':',DATE_PART('minute', orders.orderTime), ':',ROUND(DATE_PART('second', orders.orderTime))) AS orderTime, orders.shippingAddress, orders.transactionId, orderItems.item_id, orderItems.o_id, orderItems.p_id, orderItems.quantity, orderItems.price,orderItems.status -> 'status' AS status FROM products INNER JOIN (orders INNER JOIN orderItems ON orders.o_id=orderItems.o_id) ON products.p_id=orderItems.p_id WHERE orders.u_id={$_SESSION['u_id']} AND orders.orderTime > now() - interval '$orderDuration' ORDER BY orders.orderTime desc;
            EOF;
        }
            
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

    public function updateStatus($data)
    {
        $status = $data['status'];
        $obj = new Database();
        $sql = <<<EOF
               UPDATE orderItems SET status='{"status": "$status"}' WHERE item_id= {$data['item_id']};
            EOF;

        $response = $obj->postgres_query($sql);

        if ($response === "Success") {
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

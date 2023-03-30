<?php

declare(strict_types=1);

namespace App;

require_once "db_operations/Database.php";
require_once "User.php";
require_once "Products.php";

class Seller implements User
{
    // private $prod=new Products();

    public function displayDashboard()
    {
        if (isset($_SESSION['u_status']) && $_SESSION['u_status'] === "seller") {
            require_once "views/seller.php";
        } else {
            $this->displayLogin();
        }
    }
    public function displaySignup()
    {
        require_once "views/sellerSignup.php";
    }

    public function displayLogin()
    {
        require_once "views/sellerLogin.php";
    }

    public function displayOrderedProducts()
    {
        if (isset($_SESSION['u_status']) && isset($_SESSION['isVerified']) && $_SESSION['u_status'] === "seller") {
            require_once "views/orderedProducts.php";
        } else if (isset($_SESSION['u_id']) && isset($_SESSION['isVerified'])) {
            require_once "views/verifyPage.php";
        } else {
            require_once "views/login.php";
        }
    }

    public function signup($data)
    {
        $obj = new Database();
        // $data['status'] = 1;

        if (isset($_SESSION['u_id'])) {
            //First fetch the details from users table
            $u_id = $_SESSION["u_id"];

            $sql = <<<EOF
                SELECT * FROM users WHERE u_id={$u_id};
            EOF;

            $response1 = $obj->postgres_query_row($sql);
            if (is_array($response1) && count($response1) > 0) {
                //First Check if seller exists or not
                $data['u_id'] = $response1[0];

                $sql = <<<EOF
                    SELECT * FROM sellers WHERE u_id= '{$response1[0]}';
                EOF;
                $response2 = $obj->postgres_query_row($sql);
                if (is_array($response2) && count($response2) > 0) {
                    $res = new \stdClass();
                    $res->msg = "isSeller";
                    echo json_encode($res);
                    exit;
                } else {
                    $s_id = $obj->addSeller($data);
                    $_SESSION['s_id'] = $s_id;
                    if ($s_id) {
                        $_SESSION['u_status'] = "seller";
                        $res = new \stdClass();
                        $res->msg = "Success";
                        echo json_encode($res);
                    } else {
                        $res = new \stdClass();
                        $res->msg = "Failure";
                        echo json_encode($res);
                    }
                }
            }
        } else {
            $res = new \stdClass();
            $res->msg = "NotUser";
            echo json_encode($res);
        }
    }

    public function login($sellerData)
    {
        $obj = new Database();
        $data = $obj->loginSeller($sellerData);

        if (is_array($data) && count($data) > 0) {
            $_SESSION['s_id'] = $data[0]['s_id'];
            $_SESSION['u_id'] = $data[0]['u_id'];
            $_SESSION['u_status'] = "seller";
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

    public function displayAddProducts()
    {
        $prod = new Products();
        $prod->displayAddProducts();
    }

    public function getProducts()
    {
        $prod = new Products();
        $products = $prod->getProducts();
        if (is_array($products) && count($products) > 0) {
            $res = new \stdClass();
            $res->msg = "Success";
            $res->data = $products;
            echo json_encode($res);
        } else if (is_array($products) && count($products) === 0) {
            $res = new \stdClass();
            $res->msg = "No products";
            $res->data = $products;
            // var_dump($res->data);
            echo json_encode($res);
        } else {
            $res = new \stdClass();
            $res->msg = "Failure";
            echo json_encode($res);
        }
    }

    public function addproduct($product)
    {
        $prod = new Products();
        $response = $prod->addProduct();

        if ($response !== "Error") {
            $res = new \stdClass();
            $res->msg = "Success";
            $res->img = $_FILES['productImage']['name'];
            $res->p_id = $response;
            echo json_encode($res);
        } else {
            $res = new \stdClass();
            $res->msg = "Failure";
            echo json_encode($res);
        }
    }

    public function updateproduct()
    {
        $prod = new Products();
        $response = $prod->updateProduct();

        if ($response !== "Error") {
            $res = new \stdClass();
            $res->msg = "Success";
            // $res->img=" ";
            if(isset($_FILES['productImage']['name'])){
                $res->img = $_FILES['productImage']['name'];
            }else{
                $res->img = $_POST['productImageUrl'];
            }
            $res->p_id = $_POST['p_id'];
            echo json_encode($res);
        } else {
            $res = new \stdClass();
            $res->msg = "Failure";
            echo json_encode($res);
        }
    }
}

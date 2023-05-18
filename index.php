
<?php

session_start();

// require __DIR__ . '/vendor/autoload.php';
require_once "services/Router.php";
require_once "services/RouteNotFoundException.php";

$router=new \App\Router();

$router->get(
    '/home',
    function(){
        require_once "views/home.php";
    }
);

$router
->get('/', [\App\Buyer::class, 'displayHome'])
// ->get('/home', [\App\Buyer::class, 'displayHome'])
->get('/signup', [\App\Buyer::class, 'displaySignup'])
->get('/login', [\App\Buyer::class, 'displayLogin'])
->post('/signup', [\App\Buyer::class, 'signup'])
->post('/login', [\App\Buyer::class, 'login'])
->get('/logout', [\App\Buyer::class, 'logout'])
->get('/seller', [\App\Seller::class, 'displayDashboard'])
->get('/sellerSignup', [\App\Seller::class, 'displaySignup'])
->get('/sellerLogin', [\App\Seller::class, 'displayLogin'])
->post('/sellerSignup', [\App\Seller::class, 'signup'])
->post('/sellerLogin', [\App\Seller::class, 'login'])
->get('/sellerLogout', [\App\Seller::class, 'logout'])
->get('/resetPassword', [\App\Buyer::class, 'displayResetPassword'])
->get('/resetSellerPassword', [\App\Seller::class, 'displayResetPassword'])
->get('/addProductsPage', [\App\Seller::class, 'displayAddProducts'])
->get('/sellerProducts', [\App\Seller::class, 'getProducts'])
->post('/addProduct', [\App\Seller::class, 'addProduct'])
->post('/showProducts', [\App\Products::class, 'getNProducts'])
->post('/productInfo', [\App\Products::class, 'getProductInfo'])
->post('/addToCart', [\App\Buyer::class, 'addTocart'])
->get('/cart', [\App\Buyer::class, 'displayCart'])
->get('/getCartItem', [\App\Buyer::class, 'getCartItem'])
->get('/getCartItems', [\App\Buyer::class, 'getCartItems'])
->post('/cart', [\App\Buyer::class, 'addTocart'])
->post('/removeFromCart', [\App\Buyer::class, 'removeFromcart'])
->post('/placeOrder', [\App\Buyer::class, 'placeOrder'])
->get('/ordersPage', [\App\Buyer::class, 'displayOrdersPage'])
->get('/userAddress', [\App\Buyer::class, 'getUserAddress'])
->post('/userAddress', [\App\Buyer::class, 'addUserAddress'])
->get('/placeOrder', [\App\Buyer::class, 'getOrderInfo'])
->get('/getSubTotal', [\App\Buyer::class, 'getSubTotal'])
->get('/verifyMail', [\Authentication::class, 'verifyMail'])
->get('/verifyPage', [\Authentication::class, 'displayVerifyPage'])
->post('/resetPassword', [\App\Buyer::class, 'resetPassword'])
->get('/forgotPassword', [\App\Buyer::class, 'displayForgotPassword'])
->get('/verifyUser', [\App\Buyer::class, 'verifyUser'])
->post('/getEmail', [\App\Buyer::class, 'getEmail'])
->get('/currentAddressId', [\App\Buyer::class, 'getAddressId'])
->post('/currentAddressId', [\App\Buyer::class, 'setaddressId'])
->post('/confirmOrder', [\App\Buyer::class, 'confirmOrder'])
->post('/getOrderedItems', [\App\Products::class, 'getOrderedItems'])
->get('/orderHistory', [\App\Buyer::class, 'displayOrderHistory'])
->get('/orderedProducts', [\App\Seller::class, 'displayOrderedProducts'])
->post('/updateProduct', [\App\Seller::class, 'updateProduct'])
->post('/updateStatus', [\App\Products::class, 'updateStatus']);

// echo $router->resolve('/invoices');
if(isset($_SERVER['PATH_INFO'])){
    echo $router->resolve($_SERVER['PATH_INFO'], strtolower($_SERVER['REQUEST_METHOD']));
}
else{
    echo $router->resolve($_SERVER['REQUEST_URI'], strtolower($_SERVER['REQUEST_METHOD']));
}

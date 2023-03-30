<?php
require 'header2.php';
?>

<div class="slider-container">

    <div class="pic-ctn slider-item">
        <div class="pic"><img src="../images/1.jpg" alt=""></div>
        <div class="pic"><img src="../images/3.jpg" alt=""></div>
        <div class="pic"><img src="../images/4.jpg" alt=""></div>
        <div class="pic"><img src="../images/5.jpg" alt=""></div>
    </div>

    <div class="start-container flex-item">
        <h1>Welcome Back!</h1>
        <p>To keep connected with us please login with your personal info</p>
        <a href="login" class="btn btn-outline-light" id="signIn">Sign In</a>
        <p>or</p>
        <a href="/home" class="btn btn-outline-light" id="signIn">Continue without login</a>
    </div>

</div>
<?php
require_once 'footer.php';
?>
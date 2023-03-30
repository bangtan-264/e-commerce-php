<?php
require_once 'header2.php';
?>
<div class="flex-container">
  <div class="login-signup flex-item">
    <h1>Sign in</h1>
    <div>
      <input type="email" id="inputEmail" name="email" placeholder="Email">
    </div>
    <div>
      <input type="password" id="inputPassword" name="password" placeholder="Password">
    </div>
    <div class="mt-2">
      <a class="btn btn-outline-dark" id="login">Sign in</a>
    </div>
    <!-- <div class="mt-1">
      <a href="/forgotPassword">Forgot your password?</a>
    </div> -->
    <div class="error" id="login-error"> </div>
  </div>

  <div class="overlay-panel flex-item">
    <h1>Become a Seller</h1>
    <p>Enter your personal details and start your journey with us</p>
    <a href="/sellerSignup" class="btn btn-outline-light" id="signUp">Sign Up</a>
  </div>
</div>

<script src="public/sellerLogin.js"></script>

<?php
require_once 'footer.php';
?>
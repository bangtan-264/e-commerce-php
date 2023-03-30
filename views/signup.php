<?php
require_once 'header2.php';
?>

<div class="flex-container">

  <div class="overlay-panel flex-item">
    <h1>Welcome Back!</h1>
    <p>To keep connected with us please login with your personal info</p>
    <a href="/login" class="btn btn-outline-light" id="signIn">Sign In</a>
  </div>

  <div class="login-signup flex-item">
    <h1>Sign up</h1>
    <div>
      <input type="text" id="firstName" name="firstName" placeholder="First Name">
    </div>
    <div>
      <input type="text" id="lastName" name="lastName" placeholder="Last Name">
    </div>
    <div>
      <input type="email" id="inputEmail4" name="email" placeholder="Email">
    </div>
    <div>
      <input type="password" id="inputPassword4" name="password" placeholder="Password">
    </div>
    <div>
      <input type="tel" id="phoneNumber" name="phoneNumber" placeholder="Phone Number">
    </div>
    <div class="mt-3">
      <a class="btn btn-outline-dark" id="signup">Signup</a>
    </div>
    <div class="error" id="signup-error"></div>
  </div>
</div>

<script src="public/signup.js"></script>
<?php
require_once 'footer.php';
?>

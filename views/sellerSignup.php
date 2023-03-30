<?php
require_once 'header2.php';
?>
<div class="flex-container">

  <div class="overlay-panel flex-item">
    <h1>Welcome Back!</h1>
    <p>To keep connected with us please login with your personal info</p>
    <a href="/sellerLogin" class="btn btn-outline-light" id="signIn">Sign In</a>
  </div>

  <div class="login-signup flex-item">
    <h1>Sign up</h1>
    <div>
    <div>
      <input type="text" id="gstNo" name="gstNo" placeholder="GST Number">
    </div>
      <input type="text" id="businessName" name="businessName" placeholder="Business Name">
    </div>
    <div>
      <input type="text" id="businessAddress" name="businessAddress" placeholder="Business Address">
    </div>
    <div class="mt-2">
      <a class="btn btn-outline-dark" id="signup">Signup</a>
    </div>
    <div class="error" id="signup-error"></div>
  </div>
</div>

<script src="public/sellerSignup.js"></script>

<?php
require_once 'footer.php';
?>
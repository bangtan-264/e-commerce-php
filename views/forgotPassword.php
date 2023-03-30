<?php
require_once 'header2.php';
?>

<div class="flex-container">
  <div class="login-signup flex-item">
  <h1>Forgot Password ?</h1>
    <div>
      <input type="email" id="email" name="email" placeholder="Email Address*">
    </div>
    <div>
    <a class="btn btn-outline-dark" id="forgotPassword">Reset Password</a>
    </div>
    <div class="error" id="email-error"></div>
  </div>
</form>

<script src="public/forgotPassword.js"></script>

<?php
require_once 'footer.php';
?>

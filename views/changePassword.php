<?php
require_once 'header2.php';
?>

<div class="flex-container">
  <div class="login-signup flex-item">
    <h1>Reset Password</h1>
    <div>
      <input type="password" id="newPassword" name="newPassword" placeholder="New Password">
    </div>
    <div>
      <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password">
    </div>
    <div class="mt-3">
      <a class="btn btn-outline-dark" id="changePassword">Change Password</a>
    </div>
    <div class="error" id="password-error"> </div>
  </div>

</div>

<script src="public/changePassword.js"></script>
<?php
require_once 'footer.php';
?>

<?php
require_once 'header.php';
?>

<div id="alertBox" class="alert-box hidden">
  <button class="dismiss" type="button" id="cancel">×</button>
  <div class="header">
    <div class="image">
      <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
        <g id="SVGRepo_iconCarrier">
          <path d="M20 7L9.00004 18L3.99994 13" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        </g>
      </svg>
    </div>
    <div class="content">
      <span class="title">Order successfull</span>
      <p class="message">Thank you for your purchase. you package will be delivered within 2 days of your purchase</p>
    </div>
    <div class="actions">
      <a href="/orderHistory" class="history" type="button">View orders</a>
      <!-- <a class="track" type="button">Track my package</a> -->
    </div>
  </div>
</div>


<div class="checkout">
  <div class="payment-container">
    <h4 mb-5>Your subtotal: <i class="fa-solid fa-indian-rupee-sign rupee"></i> <span id="price"> </span></h4>
  </div>

  <div class="user-address hidden" id="old-address">
    <h4>Select Address:</h4>
    <select name="address-list" id="address-list">
    </select>
    <!-- <p style="margin: 0 auto;">or</p> -->
  </div>
  <div class="user-address" id="new-Address">
    <h4>Enter Address</h4>
    <input type="text" name="address" id="address" placeholder="Address">
    <a class="btn btn-outline-dark mt-2" id="submitAddress">Submit</a>
    <div class="error" id="address-error"></div>
  </div>

</div>

<button class="btn btn-warning" id="confirmOrder">Confirm</button>


<script src="public/orders.js"></script>

<?php
require_once 'footer.php';
?>
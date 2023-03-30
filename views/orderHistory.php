<?php
require 'header.php';
?>

<div id="alertBox" class="alert-box hidden">
  <button class="dismiss" type="button" id="cancel">×</button>
  <div class="item">
    <!-- <div class="header"> -->
    <!-- <div class="image">
      <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
        <g id="SVGRepo_iconCarrier">
          <path d="M20 7L9.00004 18L3.99994 13" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
        </g>
      </svg>
    </div> -->
    <div class="content">

      <div class="item-img" id="order-image"><img class="card-img-top" src="" alt="..."></div>
      <div class="item-info">
        <p class="text-title" id="order-name"></p>
        <p class="text-body"><i class="fa-solid fa-indian-rupee-sign rupee"></i><span id="order-price"></span></p>
        <p class="text-body" id="order-quantity"></p>
      </div>
      <div class="item-footer">
        <span class="text-title" id="order-status"></span>
        <span class="title">Order successfull</span>
        <p class="message" id="order-address"></p>
      </div>
    </div>
  </div>
  <!-- </div> -->
</div>

<h1 class="heading text-center">My Orders</h1>
<div class="container">
  <div id="items-list">
    <div id="cards-wrapper">
    </div>
  </div>
</div>


<script src="public/orderHistory.js"></script>

<?php
require_once 'footer.php';
?>
<<?php
require_once 'header.php';
?>

<div class="container">
  <div id="items-list">
    <div id="cards-wrapper">
    </div>
  </div>
  <div id="orderNow" class="text-center hidden">
    <div>
      <p>Subtotal(items <span id="itemsCount">0</span> ): <i class="fa-solid fa-indian-rupee-sign rupee"></i> <span id="totalPrice">0</span>
      </p>
    </div>
    <button onclick="buyCartItems()" class="btn btn-warning">Buy Now</button>
  </div>
</div>



<script src="public/cart.js"></script>

<<?php
require_once 'footer.php';
?>
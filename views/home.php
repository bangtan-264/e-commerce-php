<?php
require_once 'header.php';
?>

<div id="pop-up" class="hidden">
  <div class="card popup-card text-center">
    <span class="close-button" id="close-popup">&times;</span>
    <div class="mx-auto"><img class="product_img card-img-top" src="" alt="..." id="pop-img"></div>
    <div class="card-body">
      <h5 class="card-title" id="pop-title"></h5>
      <p class="card-text"><i class="fa-solid fa-indian-rupee-sign rupee"> <span id="pop-price"></span> </i></p>
      <p class="card-text" id="pop-desc"></p>
      <div class="error" id="cart-error"> </div>
      <a class="card-btn lavendar" id="add-to-cart">Add to cart</a>
      <a href="cart" class="card-btn yellow hidden" id="go-to-cart">Go To Cart</a>
      <a class="card-btn" id="buy-now">Buy Now</a>
    </div>
  </div>
</div>

<div>
  <div class="grid" id="card-container">
    
  </div>
</div>

<div class="text-center my-4">
  <a class="card-btn btn-yellow bouncy" id="loadMore">View more products</a>
</div>

<script src="public/home.js"></script>


<<?php
require_once 'footer.php';
?>
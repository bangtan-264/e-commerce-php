<<?php
require_once 'sellerHeader.php';
?>

<div id="pop-up" class="hidden">
  <span class="close-button" id="close-popup">&times;</span>
  <div class="login-signup flex-item form">
    <div>
      <input type="text" id="productName" name="productName" placeholder="Product Name">
    </div>
    <div>
      <input type="number" id="price" name="price" placeholder="Price">
    </div>
    <div>
      <input type="text" id="desc" name="desc" placeholder="Description">
    </div>
    <div>
      <input type="number" id="stock" name="stock" placeholder="stock">
    </div>
    <div>
      <input accept="image/*" type="file" id="img" name="img">
    </div>
    <div class="mt-3">
      <a class="btn btn-outline-dark" id="submit">Submit</a>
    </div>
    <div class="mt-3">
      <a class="btn btn-outline-dark hidden" id="update">Update</a>
    </div>
    <div class="error" id="product-error"></div>
  </div>
</div>


<h1 class="heading text-center">Welcome to Seller Dashboard</h1>


<div id="products-list">
  <div id="cards-wrapper"></div>
</div>

<div id="add-product-btn"><button id="addProducts">+</button></div>


<script src="public/addProducts.js"></script>
<!-- <script src="public/goToCart.js"></script> -->

<?php
require_once 'footer.php';
?>
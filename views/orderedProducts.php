<?php
require_once 'sellerHeader.php';
?>

<h1 class="heading text-center">Welcome to Seller Dashboard</h1>

<div class="container">
  <div id="products-list">
    <div id="cards-wrapper">
    </div>
  </div>
  <div id="order" class="text-center">
    <div>
    <h4>View orders placed in:</h4>
    <select name="orderDuration" id="order-duration">
      <option value="24 hours">24 hours</option>
      <option value="7 day" selected>last week</option>
      <option value="1 month">Last 30 days</option>
      <option value="3 month">Last 3 months</option>
      <option value="1 year">Last year</option>
      <option value="All">Get all orders</option>
    </select>
    </div>
    <button onclick="getOrders()" class="btn btn-warning mt-3">Get orders</button>
  </div>
</div>


<script src="public/orderedProducts.js"></script>

<?php
require_once 'footer.php';
?>
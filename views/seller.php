<?php
require_once 'sellerHeader.php';
?>
<h1 class="heading text-center">Welcome to Seller Dashboard</h1>

<div class="dashboard">

  <div class="dashboard-items">
    <h1>Add/Edit Products</h1>
    <p>You can add new products or you can also edit, enable and disbale your products</p>
    <a href="/addProductsPage" class="btn btn-outline-light">View</a>
  </div>

  <div class="dashboard-item-2">
    <h1>Products Sold</h1>
    <p>You can see the products sold till Now!</p>
    <a href="/orderedProducts" class="btn btn-outline-light">View</a>
  </div>
</div>

<?php
require_once 'footer.php';
?>
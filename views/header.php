<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>E-commerce</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="../public/style.css">
</head>

<body>
  <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark ">
    <div class="container-fluid">
      <a class="navbar-brand" href="/home">
        <!-- <img src="/docs/5.0/assets/brand/bootstrap-logo.svg" alt="" width="30" height="24" class="d-inline-block align-text-top"> -->
        E-commerce
      </a>
      <!-- <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form> -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 nav-list">
          <!-- <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="/shopNow" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Shop Now
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="#">Men</a></li>
              <li><a class="dropdown-item" href="#">Women</a></li>
            </ul>
          </li> -->
          <!-- <li class="nav-item">
            <a class="nav-link" href="/">Shop Now</a>
          </li> -->
          <?php
          if (!isset($_SESSION['u_id'])) {
          ?>
            <li class="nav-item">
              <a class="nav-link" href="/login">login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/signup">Signup</a>
            </li>
        </ul>
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 nav-list">
          <li class="nav-item">
            <a class="nav-link" href="/userAccount">My Account</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/seller">Become a Seller</a>
          </li>

        <?php
          } else {
        ?>
          <li class="nav-item">
            <a class="nav-link" href="/logout">logout</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/resetPassword">Reset Password</a>
          </li>
        </ul>
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 nav-list">
          <li class="nav-item">
            <a class="nav-link" href="/userAccount">
              <?php echo $_SESSION['u_name']; ?>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/seller">Become a Seller</a>
          </li>
          <li class="nav-item">
            <a href="/cart" class="nav-link"><i class="fa-solid fa-cart-shopping"></i></a>
          </li>
          <li class="nav-item">
            <a href="/orderHistory" class="nav-link">My Orders</a>
          </li>
        <?php
          }
        ?>
        </ul>
      </div>
    </div>
  </nav>
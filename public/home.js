const cardContainer = document.getElementById("card-container");
const loadMore = document.getElementById("loadMore");
const popUp = document.getElementById("pop-up");
const popImg = document.getElementById("pop-img");
const popTitle = document.getElementById("pop-title");
const popPrice = document.getElementById("pop-price");
const addToCart = document.getElementById("add-to-cart");
const goToCart = document.getElementById("go-to-cart");
const popDesc = document.getElementById("pop-desc");
const closePopup = document.getElementById("close-popup");
const buyNow = document.getElementById("buy-now");
const cartError = document.getElementById("cart-error");

let productCnt = 0;
let NoMoreProducts = false;

getProducts();

if (NoMoreProducts === false) {
  loadMore.addEventListener("click", () => {
    getProducts();
  });
}

// function displayInitialProducts
function getProducts() {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "/showProducts");
  xhr.setRequestHeader("Content-Type", "application/json");

  xhr.send(JSON.stringify({ index: productCnt }));

  xhr.addEventListener("load", () => {
    let response = JSON.parse(xhr.response);
    if (response.msg === "Success") {
      let products = response.data;
      console.log(products);
      if (products.length === 0) {
        loadMore.innerHTML = "No more Products";
        NoMoreProducts = true;
      }
      productCnt += 5;
      displayProducts(products, renderProduct);
    }
  });
}

function displayProducts(products, callback) {
  products.forEach((product) => {
    callback(product);
  });
}

function renderProduct(product) {
  let card = document.createElement("div");
  card.classList.add("grid-item");

  let cardData = `<div class="product-card">
  <img class="card-img" src="uploads/${product["productimage"]}" alt="clothes" />
  <div class="card-content">
    <h1 class="card-head"> ${product["productname"]} </h1>
    <p class="card-txt">
    <i class="fa-solid fa-indian-rupee-sign rupee"></i>
      ${product["price"]}
    </p>
    <div class="error" id="${product["p_id"]}e"></div>
    <a onclick="viewDetails(${product["p_id"]})" class="card-btn">View Details</a>
    <a onclick="addItemToCart(${product["p_id"]},0)" class="card-btn lavendar"  id="${product["p_id"]}p">Add To Cart</a>
    <a href="/cart" class="card-btn yellow hidden" id="${product["p_id"]}g">Go To Cart</a>
  </div>
  </div>`;

  card.innerHTML = cardData;
  cardContainer.appendChild(card);
}

function viewDetails(p_id) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "/productInfo");
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.send(JSON.stringify({p_id }));

  xhr.addEventListener("load", () => {
    console.log(xhr.response);
    let response = JSON.parse(xhr.response);
    if (response.msg === "Success") {
        let productInfo = response.data;
        console.log(productInfo);
        popUp.classList.toggle("hidden");
        popImg.src = `/uploads/${productInfo["productimage"]}`;
        popTitle.innerHTML = productInfo["productname"];
        popPrice.textContent = productInfo["price"];
        popDesc.innerHTML = productInfo["productdesc"];
        buyNow.onclick = () => {
          placeOrder(productInfo["p_id"]);
        };
        addToCart.onclick = () => {
          addItemToCart(p_id, 1);
        };
    } else {
      console.log("Error");
    }
  });
}

function addItemToCart(p_id, flag) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "/addToCart");
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.send(JSON.stringify({ p_id: p_id, quantity: 1 }));

  xhr.addEventListener("load", () => {
    console.log(xhr.response);
    let result = JSON.parse(xhr.response);
    if (result.msg === "verify") {
      window.location.href="/verifyPage";
    } else if (result.msg === "login") {
      window.location.href="/login";
    } else if (result.msg === "Success")
      if (flag === 0) {
        let itemId1 = p_id + "p";
        let itemId2 = p_id + "g";
        document.getElementById(itemId1).classList.toggle("hidden");
        document.getElementById(itemId2).classList.toggle("hidden");
      } else {
        addToCart.classList.toggle("hidden");
        goToCart.classList.toggle("hidden");
      }
    else if (result.msg === "outOfStock") {
      if (flag === 0) {
        let itemId = p_id + "e";
        let element = document.getElementById(itemId);
        element.innerHTML = "Out Of Stock";
      } else {
        cartError.innerHTML = "Out Of Stock";
      }
    } else {
      console.log("Error");
    }
  });
}

function placeOrder(p_id) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "/addToCart");
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.send(JSON.stringify({ p_id: p_id, quantity: 1 }));

  xhr.addEventListener("load", () => {
    let result = JSON.parse(xhr.response);
    if (result.msg === "verify") {
      window.location.href="/verifyPage";
    } else if (result.msg === "login") {
      window.location.href="/login";
    } else if (result.msg === "Success") {
      let c_id = result.c_id;
      let xhr = new XMLHttpRequest();
      xhr.open("POST", "/placeOrder");
      xhr.setRequestHeader("Content-Type", +"application/json");
      xhr.send(JSON.stringify({ items: [c_id], price: popPrice.innerText }));

      xhr.addEventListener("load", () => {
        let result = JSON.parse(xhr.response);
        if (result.msg === "Success") {
          window.location.href = "/ordersPage";
        } else {
          console.log("Error");
        }
      });
    } else if (result.msg === "outOfStock") {
      cartError.innerHTML = "Out Of Stock";
    } else {
      console.log("Error");
    }
  });
}

function buyCartItem(cartId) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "/orderType");
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.send(JSON.stringify({ orderType: "cartItem", cartId: cartId }));

  xhr.addEventListener("load", () => {
    let result = JSON.parse(xhr.response);
    console.log(result);
    window.location.href = "/orders";
  });
}

closePopup.addEventListener("click", function () {
  popUp.classList.toggle("hidden");
  if (addToCart.classList.contains("hidden")) {
    addToCart.classList.toggle("hidden");
    goToCart.classList.toggle("hidden");
  }
});

document.addEventListener("keydown", function (e) {
  if (e.key === "Escape" && !popUp.classList.contains("hidden")) {
    popUp.classList.toggle("hidden");
    if (addToCart.classList.contains("hidden")) {
      addToCart.classList.toggle("hidden");
      goToCart.classList.toggle("hidden");
    }
  }
});

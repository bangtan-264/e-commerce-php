const parent = document.getElementById("cards-wrapper");
const totalPrice = document.getElementById("totalPrice");
const itemsCount = document.getElementById("itemsCount");
const orderNow = document.getElementById("orderNow");

let cartList=[];

dispalyCartItems();

function dispalyCartItems() {
  let xhr = new XMLHttpRequest();
  xhr.open("get", "/getCartItems");
  xhr.send();
  xhr.addEventListener("load", () => {
    let result = JSON.parse(xhr.response);
    // console.log(result);
    if (result.msg === "Success") {
      let cartList=result.data;
      appendItems(cartList);
      displayTotalPrice(cartList);
    } else {
      parent.innerHTML = "Empty cart";
    }
  });
}

function appendItems(items) {
  console.log("Items", items);
  items.forEach((item) => {
    appendOneItem(item);
    cartList.push(item['c_id']);
  });
}

function appendOneItem(item) {
  const cart = document.createElement("div");
  cart.className = "cart";
  cart.id=item['c_id'];

  const cartData = `<div class="cart-img cart-item">
  <img class="card-img-top" src="uploads/${item['productimage']} " alt="...">
</div>
<div class="cart-body cart-item">
  <h2 class="card-title"> ${item['productname']} </h2>
  <p class="card-text">Price: <i class="fa-solid fa-indian-rupee-sign rupee"></i><span id="${item['p_id']}p"> ${item['price']}</span></p>
  <br>
  <span class="card-text"> Quantity: <span id="${item['p_id']}q"> ${item['quantity']} </span> </span>
  <button onclick="decCartQuantity(${item['p_id']})" class="btn btn-dark"> - </button>
  <button onclick="incCartQuantity(${item['p_id']})" class="btn btn-dark">+</button>
</div>
<div class="cart-btn cart-item">
<button onclick="deleteFromCart(${item['c_id']}, ${item['p_id']})" class="btn btn-dark">Delete</button>
<button onclick="buyCartItem(${item['c_id']}, ${item['price']})" class="btn btn-warning">Buy Now</button>
</div>
</div>`;

  cart.innerHTML = cartData;
  parent.appendChild(cart);
}

function displayTotalPrice(items) {
  let price = 0;
  items.forEach((item) => {
    price += item['price'] * item['quantity'];
  });
  if (price !== 0) {
    orderNow.classList.remove("hidden");
    totalPrice.innerHTML = price;
    itemsCount.innerHTML = items.length;
  } else {
    orderNow.classList.add("hidden");
  }
}

function changeTotalPrice(newPrice, change) {
  let price = parseInt(totalPrice.innerText);
  if (change === 1) {
    price += newPrice;
  } else {
    price -= newPrice;
  }
  if (price !== 0) {
    totalPrice.innerText = price;
  } else {
    orderNow.classList.add("hidden");
  }
}

function incCartQuantity(p_id) {
  changeCartQuantity(p_id, 1);
}

function decCartQuantity(p_id) {
  changeCartQuantity(p_id, -1);
}

function changeCartQuantity(p_id, change) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "/cart");
  xhr.setRequestHeader("Content-Type", "application/json");
  if (change == 1) {
    xhr.send(JSON.stringify({ p_id: p_id, quantity: 1 }));
  } else {
    xhr.send(JSON.stringify({ p_id: p_id, quantity: -1 }));
  }

  xhr.addEventListener("load", () => {
    console.log(xhr.response);
    let result = JSON.parse(xhr.response);
    console.log(result);
    if (result.msg === "removeFromCart") {
      removeFromCart(result.c_id, result.price);
      // changeTotalPrice(result.price, 0);
    } else if (result.msg === "outOfStock") {
      alert("Out Of Stock");
    } else if(result.msg="Success"){
      let quantity = result.quantity;
      let price = parseInt(result.price);
      displayQuantity(quantity, p_id);
      if (change == 1) {
        changeTotalPrice(price, 1);
      } else {
        changeTotalPrice(price, 0);
      }
    }
  });
}

function displayQuantity(quantity, productId) {
  console.log(productId);
  let quantityText = document.getElementById(`${productId}q`);
  quantityText.innerHTML = quantity;
}

function deleteFromCart(c_id, p_id){
  let id1=p_id+"q";
  let quantity=parseInt(document.getElementById(id1).innerText);
  let id2=p_id+"p";
  let price=parseInt(document.getElementById(id2).innerText);
  let newPrice=quantity*price;

  removeFromCart(c_id, newPrice);
}

function removeFromCart(c_id, price) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "/removeFromCart");
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.send(JSON.stringify({ c_id: c_id, price: price }));

  xhr.addEventListener("load", function () {
    console.log(xhr.response);
    let result = JSON.parse(xhr.response);
    console.log(result);
    if (result.msg === "Success") {
      changeTotalPrice(price, 0);
      let item=document.getElementById(c_id);
      parent.removeChild(item);
    } else {
      console.log("Can't remove item");
    }
  });
}

function buyCartItems(){
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "/placeOrder");
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.send(JSON.stringify({ items: cartList, price: totalPrice.innerText}));

  xhr.addEventListener("load", () => {
    let result = JSON.parse(xhr.response);
    if(result.msg==="Success"){
      window.location.href = "/ordersPage";
    }else{
      console.log("Error");
    }
  });
}

function buyCartItem(c_id,price) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "/placeOrder");
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.send(JSON.stringify({ items: [c_id], price: price}));

  xhr.addEventListener("load", () => {
    let result = JSON.parse(xhr.response);
    if(result.msg==="Success"){
      window.location.href = "/ordersPage";
    }else{
      console.log("Error");
    }
  });
}
const parent = document.getElementById("cards-wrapper");
const details = document.getElementById("alertBox");

dispalyOrderItems();

function dispalyOrderItems() {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "/getOrderedItems");
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.send(JSON.stringify({orderDuration: "All"}));
  xhr.addEventListener("load", () => {
    let result = JSON.parse(xhr.response);
    if (result.msg === "Success") {
      console.log(result.data);
      appendItems(result.data);
    } else {
      parent.innerHTML = "No orders yet";
    }
  });
}

function appendItems(items) {
  items.forEach((data) => {
    appendOneItem(data);
  });
}

function appendOneItem(item) {
  const cart = document.createElement("div");
  cart.className = "cart";

  const cartData = `<div class="cart-img cart-item">
  <img class="card-img-top" src="uploads/${item["productimage"]} " alt="...">
</div>
<div class="cart-body cart-item">
<h2 class="card-title"> ${item["productname"]} </h2>
<p class="card-text"><i class="fa-solid fa-indian-rupee-sign rupee"></i><span> ${item["price"]}</span></p>
<p class="card-text">Quantity: ${item["quantity"]}</p>
<p class="card-text">Address: ${item["shippingaddress"]}</p> 
</div>
<div class="cart-btn cart-item">
<p class="card-text">${item["ordertime"]}</p> 
  <p class="card-text">${item["status"]}</p>
</div>
</div>`;

  cart.innerHTML = cartData;
  parent.appendChild(cart);
}

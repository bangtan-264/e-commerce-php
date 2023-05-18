const parent = document.getElementById("cards-wrapper");
const orderDuration=document.getElementById("order-duration");

dispalyItems();

function getOrders(){
  let len=parent.childNodes.length;
  while(--len){
    parent.removeChild(parent.childNodes[len]);
  }
  dispalyItems();
}

function dispalyItems() {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "/getOrderedItems");
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.send(JSON.stringify({ orderDuration: orderDuration.value }));
  xhr.addEventListener("load", () => {
    let result = JSON.parse(xhr.response);
    if (result.msg === "Success") {
      appendItems(result.data);
    } else {
      parent.innerHTML = "No orders yet";
    }
  });
}

function appendItems(items) {
  console.log("Items", items);
  items.forEach((item) => {
    appendOneItem(item);
  });
}

function appendOneItem(item) {
  const cart = document.createElement("div");
  cart.className = "cart";
  let status = JSON.parse(item.status);

  const cartData = `<div class="cart-img cart-item">
  <img class="card-img-top" src="uploads/${item["productimage"]} " alt="...">
</div>
<div class="cart-body cart-item">
<h2 class="card-title"> ${item["productname"]} </h2>
<p class="card-text"><i class="fa-solid fa-indian-rupee-sign rupee"></i><span> ${item["price"]}</span></p>
<p class="card-text">Quantity: ${item["quantity"]}</p>
<p class="card-text shipping-address">Address: ${item["shippingaddress"]}</p> 
</div>
<div class="cart-btn cart-item">
<p class="card-text">${item["ordertime"]}</p> 
<div id="${item["item_id"]}status">
<p class="card-text" id="${item["item_id"]}s">${status}</p>
<button class="btn btn-warning" id="${item["item_id"]}e">Edit Status</button>
</div>
<div class="hidden" id="${item["item_id"]}u" style="position: relative;">
<button class="dismiss cancleUpdate" type="button" id="${item["item_id"]}c" >×</button>
<div>
  <input type="text" id="${item["item_id"]}i" name="status" placeholder="Order Status" style="max-width: 8rem; display: block;">
  <button onclick="updateStatus(${item["item_id"]})" class="btn btn-dark">Update Status</button>
</div>
</div>
</div>
</div>
</div>`;

  cart.innerHTML = cartData;
  parent.appendChild(cart);

  const editBtnId = `${item["item_id"]}e`;
  const editBtn = document.getElementById(editBtnId);

  editBtn.addEventListener("click", () => {
    let element2 = document.getElementById(item.item_id + "i");
    element2.value = status;
    toogleUpdateStatus(item.item_id);
  });

  const closeUpdateId = `${item["item_id"]}c`;
  const closeUpdateBtn = document.getElementById(closeUpdateId);

  closeUpdateBtn.addEventListener("click", function () {
    toogleUpdateStatus(item.item_id);
  });
}

function updateStatus(item_id) {
  let element = document.getElementById(item_id + "i");
  let status = element.value;

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "/updateStatus");
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.send(JSON.stringify({ item_id: item_id, status: status }));
  xhr.addEventListener("load", () => {
    let result = JSON.parse(xhr.response);
    if (result.msg === "Success") {
      let element2 = document.getElementById(`${item_id}s`);
      element2.innerText = status;
      toogleUpdateStatus(item_id);
    } else {
      console.log("Error");
    }
  });
}

function toogleUpdateStatus(item_id) {
  let element1 = document.getElementById(item_id + "u");
  element1.classList.toggle("hidden");
  let element3 = document.getElementById(item_id + "status");
  element3.classList.toggle("hidden");
}

const inputAddress = document.getElementById("address");
const submitAddress = document.getElementById("submitAddress");
const addressError = document.getElementById("address-error");
const confirmOrder = document.getElementById("confirmOrder");
const oldAddress = document.getElementById("old-address");
const newAddress = document.getElementById("new-address");
const addressList = document.getElementById("address-list");
const price = document.getElementById("price");
const alertBox=document.getElementById("alertBox");
const cancel=document.getElementById("cancel");

displaySubTotal();
getUserAddress();

function displaySubTotal() {
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "/getSubTotal");
  xhr.send();
  xhr.addEventListener("load", () => {
    let result = JSON.parse(xhr.response);
    console.log(result);
    if (result.msg === "Success") {
      price.innerText = result.data;
    } else {
      console.log("Error");
    }
  });
}

function getUserAddress() {
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "/userAddress");
  xhr.send();
  xhr.addEventListener("load", () => {
    let result = JSON.parse(xhr.response);
    console.log(result);
    if (result.msg === "Success") {
      let userAddress = result.data;
      displayAddress(userAddress, renderAddress, false);
    } else if (result.msg === "noAddress") {
      console.log("No adddress yet!");
    } else {
      console.log("Error");
    }
  });
}

function displayAddress(userAddress, callback, flag) {
  if (oldAddress.classList.contains("hidden")) {
    oldAddress.classList.toggle("hidden");
  }
  userAddress.forEach((address) => {
    callback(address, flag);
  });
}

function renderAddress(address, flag) {
  let item = document.createElement("option");
  // item.id=address['a_id'];
  item.innerHTML = address["address"];
  item.value = address["a_id"];
  if (flag === true) {
    item.selected = true;
  }
  addressList.appendChild(item);
}

submitAddress.addEventListener("click", () => {
  let address = inputAddress.value.trim();
  if (address) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "/userAddress");
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.send(JSON.stringify({ address: address }));

    xhr.addEventListener("load", () => {
      // console.log(xhr.response);
      let result = JSON.parse(xhr.response);
      console.log(result);
      if (result.msg === "Success") {
        let addressDetails = {
          address: address,
          a_id: result.data,
        };
        if (oldAddress.classList.contains("hidden")) {
          oldAddress.classList.toggle("hidden");
        }
        renderAddress(addressDetails, true);
        addressError.innerHTML = "Successfully added address";
        console.log("Successfully added address");
      } else {
        console.log("Error");
      }
      inputAddress.value = "";
    });
  } else {
    addressError.innerHTML = "Please Enter address in correct format";
  }
});

confirmOrder.addEventListener("click", () => {
  let a_id = addressList.value;
  if (a_id) {
    let xhr = new XMLHttpRequest();
    xhr.open("GET", "/placeOrder");
    xhr.send();

    xhr.addEventListener("load", () => {
      let result = JSON.parse(xhr.response);
      console.log(result);
      if (result.msg === "Success") {
        let cartList = result.items;
        let price = result.price;
        let data = {
          cartList: cartList,
          price: price,
          a_id: a_id,
        };

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "/confirmOrder");
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.send(JSON.stringify(data));

        xhr.addEventListener("load", () => {
          console.log(xhr.response);
          let result = JSON.parse(xhr.response);
          console.log(result);
          if (result.msg === "Success") {
            price.innerHTML="";
            // alert("Order placed successfully");
            alertBox.classList.toggle("hidden");
          } else {
            console.log("Error");
          }
        });
      }
    });
  }
});

cancel.addEventListener("click", function () {
  if (!alertBox.classList.contains("hidden")) {
    alertBox.classList.toggle("hidden");
  }
});

document.addEventListener("keydown", function (e) {
  if (e.key === "Escape" && !alertBox.classList.contains("hidden")) {
    alertBox.classList.toggle("hidden");
  }
});

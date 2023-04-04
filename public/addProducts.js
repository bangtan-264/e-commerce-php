const submit = document.getElementById("submit");
const prodName = document.getElementById("productName");
const prodPrice = document.getElementById("price");
const prodDesc = document.getElementById("desc");
const prodStock = document.getElementById("stock");
const prodImage = document.getElementById("img");
const productError = document.getElementById("product-error");
const popUp = document.getElementById("pop-up");
const closePopup = document.getElementById("close-popup");
const parent = document.getElementById("cards-wrapper");
const addProducts = document.getElementById("addProducts");
const update= document.getElementById("update");

displayProducts();

function displayProducts() {
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "/sellerProducts");
  xhr.send();

  xhr.addEventListener("load", (req, res) => {
    let result = JSON.parse(xhr.response);
    // console.log(xhr.response);
    if (result.msg === "No products") {
      console.log("You don't have any products");
      parent.innerHTML = "No products";
    } else if(result.msg==="Success"){
      parent.innerHTML = "";
      appendProducts(result.data);
    }else{
      console.log("Error");
    }
  });
}

function appendProducts(products) {
  products.forEach((product) => {
    appendOneProduct(product);
  });
}

function appendOneProduct(item) {
  let product = document.createElement("div");
  product.className = "cart";
  product.id=item['p_id'];

  let productData = `<div class="cart-img cart-item">
  <img id="${item.p_id}i" class="card-img-top" src="uploads/${item.productimage}" alt="...">
  </div>
  <div class="cart-body cart-item">
  <h2 class="card-title" id="${item.p_id}n"> ${item.productname} </h2>
  <p class="card-text"><i class="fa-solid fa-indian-rupee-sign rupee"></i><span id="${item.p_id}p"> ${item.price}</span></p>
  <p class="card-text"><span id="${item.p_id}d"> ${item.productdesc} </span> </p>
  <p class="card-text"> Stock: <span id="${item.p_id}s"> ${item.stock} </span> </p>
  </div>
  <div class="cart-btn cart-item">
  <button class="btn btn-dark" onclick=editProduct(${item.p_id})>Edit</button>
  </div>
  </div>`;

  product.innerHTML = productData;
  parent.appendChild(product);
}

submit.addEventListener("click", () => {
  let productName = prodName.value.trim();
  let price = prodPrice.value.trim();
  let productDesc = prodDesc.value.trim();
  let productStock = prodStock.value.trim();

  if (productName && price && productDesc && productStock) {
    let form = new FormData();
    let productImage = prodImage.files[0];
    form.append("productName", productName);
    form.append("price", price);
    form.append("productDesc", productDesc);
    form.append("productStock", productStock);
    form.append("productImage", productImage);

    insertProduct(form);
  } else {
    productError.innerHTML = "Please enter details in correct format!";
  }
});

addProducts.addEventListener("click", () => {
  popUp.classList.toggle("hidden");
});

function insertProduct(form) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "/addProduct");
  xhr.send(form);

  xhr.addEventListener("load", () => {
    let result = JSON.parse(xhr.response);
    console.log(result);
    if (result.msg === "Success") {
      popUp.classList.toggle("hidden");
      prodName.value = "";
      prodPrice.value = "";
      prodDesc.value = "";
      prodStock.value = "";
      prodImage.value = "";
      let product = {
        productname: form.get("productName"),
        price: form.get("price"),
        productdesc: form.get("productDesc"),
        stock: form.get("productStock"),
        productimage: result.img,
        p_id: result.p_id
      };
      appendOneProduct(product);
    } else {
      productError.innerHTML = "Can't upload product";
    }
  });
}

update.addEventListener("click", () => {
  let productName = prodName.value.trim();
  let price = prodPrice.value.trim();
  let productDesc = prodDesc.value.trim();
  let productStock = prodStock.value.trim();

  if (productName && price && productDesc && productStock) {
    let form = new FormData();
    let productImage = prodImage.files[0];

    form.append("productName", productName);
    form.append("price", price);      
    form.append("productDesc", productDesc);
    form.append("productStock", productStock);
    form.append("productImage", productImage);
    form.append("productImageUrl", prodImage.key);
    form.append("p_id", update.value);

    updateProduct(form);
  } else {
    productError.innerHTML = "Please enter details in correct format!";
  }
});


function editProduct(p_id){
  let xhr=new XMLHttpRequest();
  xhr.open("POST", '/productInfo');
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.send(JSON.stringify({p_id: p_id}));
  xhr.addEventListener("load", ()=>{
    let result=JSON.parse(xhr.response);
    if(result.msg==="Success"){
      let productData=result.data;
      let p_id=productData['p_id'];
      prodName.value = productData['productname'];
      prodPrice.value = productData['price'];
      prodDesc.value = productData['productdesc'];
      prodStock.value = productData['stock'];
      prodImage.key = productData['productimage'];
      update.value=p_id;
      submit.classList.toggle("hidden");
      update.classList.toggle("hidden");
      popUp.classList.toggle("hidden");
    }
  })
}

function updateProduct(form) {
  console.log(form);
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "/updateProduct");
  xhr.send(form);

  xhr.addEventListener("load", () => {
    let result = JSON.parse(xhr.response);

    if (result.msg === "Success") {
      submit.classList.toggle("hidden");
      update.classList.toggle("hidden");
      popUp.classList.toggle("hidden");
      prodName.value = "";
      prodPrice.value = "";
      prodDesc.value = "";
      prodStock.value = "";
      prodImage.value = "";
      let product = {
        productname: form.get("productName"),
        price: form.get("price"),
        productdesc: form.get("productDesc"),
        stock: form.get("productStock"),
        productimage: result.img,
        p_id: result.p_id
      };

      updateProductData(product);
    } else {
      productError.innerHTML = "Can't upload product";
    }
  });
}

function updateProductData(product){
  let name=document.getElementById(product['p_id']+"n");
  let image=document.getElementById(product['p_id']+"i");
  let price=document.getElementById(product['p_id']+"p");
  let desc=document.getElementById(product['p_id']+"d");
  let stock=document.getElementById(product['p_id']+"s");

  image.src=`uploads/${product.productimage}`;
  name.innerText=product.productname;
  price.innerText=product.price;
  desc.innerText=product.productDesc;
  stock.innerText=product.stock;
}

// function disableEnableProduct(id){
  
// }

closePopup.addEventListener("click", function () {
  popUp.classList.toggle("hidden");
});

document.addEventListener("keydown", function (e) {
  if (e.key === "Escape" && !popUp.classList.contains("hidden")) {
    popUp.classList.toggle("hidden");
  }
});

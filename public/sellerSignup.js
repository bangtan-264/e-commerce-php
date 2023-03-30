const gstNo = document.getElementById("gstNo");
const businessName = document.getElementById("businessName");
const businessAddress = document.getElementById("businessAddress");
const signup = document.getElementById("signup");
const signupError = document.getElementById("signup-error");

signup.addEventListener("click", () => {
  let gstno = gstNo.value.trim();
  let businessname = businessName.value.trim();
  let businessaddress = businessAddress.value.trim();

  if (gstno && businessname && businessaddress) {
    let businessDetails = {
      gst_no: gstno,
      businessName: businessname,
      businessAddress: businessaddress,
    };
    saveBusinessData(businessDetails);
  } else {
    signupError.innerHTML = "Please enter details in correct format!";
  }
});

function saveBusinessData(businessDetails) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "/sellerSignup");
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.send(JSON.stringify(businessDetails));

  xhr.addEventListener("load", () => {
    console.log(xhr.response);
    let res = JSON.parse(xhr.response);
    
    if (res.msg === "Success") {
      window.location.href = "/seller";
    }
    else if(res.msg==="NotUser"){
      signupError.innerHTML = "Please signup as a user first!";
    } 
    else if (res.msg === "isSeller") {
      signupError.innerHTML = "Seller Already exists!";
    }else{
      signupError.innerHTML = "Error in logging in!";
    }
  });
}

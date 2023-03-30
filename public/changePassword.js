const changePassword = document.getElementById("changePassword");
const newPassword = document.getElementById("newPassword");
const confirmPassword = document.getElementById("confirmPassword");
const passwordError = document.getElementById("password-error");

changePassword.addEventListener("click", () => {
  let new_password = newPassword.value.trim();
  let confirm_password = confirmPassword.value.trim();

  if (new_password && confirm_password && new_password===confirm_password ) {
    let newPassword= new_password;
    resetPassword(newPassword);
  } else if(new_password!==confirm_password){
    passwordError.innerHTML = "Password mismatch!";
  }else {
    passwordError.innerHTML = "Please enter details in correct format!";
  }
});

function resetPassword(newPassword) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "/resetPassword");
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.send(JSON.stringify({newPassword}));

  xhr.addEventListener("load", () => {
    // console.log(xhr.response);
    let result = JSON.parse(xhr.response);
    console.log(result);

    if (result.msg === "Success") {
      console.log("Success");
      window.location.href = "/home";
    } else {
      // password.innerHTML = "User dosen't exist!";
      console.log("Error");
    }
  });
}

const userEmail = document.getElementById("email");
const forgotPassword = document.getElementById("forgotPassword");
const emailError = document.getElementById("email-error");

forgotPassword.addEventListener("click", () => {
  let email = userEmail.value.trim();
  if (email) {
    resetPassword(email);
  }else {
    emailError.innerHTML = "Please enter details in correct format!";
  }
});

function resetPassword(email) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "/getEmail");
  xhr.setRequestHeader("Content-Type", "application/json");
  xhr.send(JSON.stringify({email}));

  xhr.addEventListener("load", () => {
    // console.log(xhr.response);
    let result = JSON.parse(xhr.response);
    console.log(result);

    if (result.msg === "Success") {
      console.log("Success");
    //   window.location.href = "/resetPassword";
      emailError.innerText="A reset password email has been sent to your registered email Id!"
    } else {
        emailError.innerText="Email Id doesn't exist!"
        console.log("Error");
    }
  });
}

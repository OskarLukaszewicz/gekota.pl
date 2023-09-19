let loginForm = document.getElementById("loginForm");

console.log(loginForm);

if (detectMobile()) {
  loginForm.setAttribute("name", "_target_path");
  loginForm.setAttribute("value", "/admin/animals/mobile");
}

function detectMobile() {
  return window.innerWidth < 800 && window.innerHeight < 600;
}

const navbarToggler = document.querySelector(".navbar-toggler")
const headingMobile = document.querySelector(".headingMobile")


navbarToggler.addEventListener("click", () => {
    headingMobile.classList.toggle("d-none")
})



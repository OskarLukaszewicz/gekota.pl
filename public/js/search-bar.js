let searchBar = document.getElementById("searchBar");
let images = document.querySelectorAll(".addImageImage");

if (searchBar) {
  searchBar.addEventListener("keyup", (e) => {
    images.forEach((image) => {
      if (
        image.dataset.url.toUpperCase().includes(searchBar.value.toUpperCase())
      ) {
        image.parentElement.classList.remove("d-none");
      } else {
        image.parentElement.classList.add("d-none");
      }
    });
  });
}

let sizeButton = document.getElementById("imageViewSizeButton");
let sizingImages = document.querySelectorAll(".imageContainer");
let sectionHeader = document.querySelector(".sectionHeader");
let step = 1;

sizeButton.addEventListener("click", () => {
  sizingImages.forEach((element) => {
    switch (step) {
      case 1:
        element.classList.toggle("col-3");
        break;
      case 2:
        if (!sectionHeader || sectionHeader.innerHTML.trim() == "Galerie") {
          element.classList.toggle("col-3");
          break;
        }
        element.classList.remove("col-2");
        break;
      case 3:
        if (!sectionHeader || sectionHeader.innerHTML.trim() == "Galerie") {
          element.classList.toggle("col-3");
          break;
        }
        element.classList.add("col-2");
        element.classList.add("col-3");
        break;
    }
  });
  step < 3 ? step++ : (step = 1);
});

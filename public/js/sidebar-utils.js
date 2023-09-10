let buttons = Array.from(document.querySelectorAll(".menuTab"));
let itemTabs = document.querySelectorAll(".itemTab");
let addImagesButton = document.getElementById("addImage");
let itemListTab = document.getElementById("itemListTab");
let addImageTab = document.getElementById("addImageTab");

let tabs = {
  info: "infoContainer",
  table: "tableContainer",
  images: "imageContainer",
};

addImagesButton.addEventListener("click", (e) => {
  e.target.innerHTML =
    e.target.innerHTML == "Dodaj zdjęcie" ? "Wróć" : "Dodaj zdjęcie";
  itemListTab.classList.add("d-none");
  addImageTab.classList.add("d-none");
  if (e.target.innerHTML == "Dodaj zdjęcie") {
    itemListTab.classList.remove("d-none");
    e.target.classList.remove("itemActive");
  } else {
    addImageTab.classList.remove("d-none");
    e.target.classList.add("itemActive");
  }
});

buttons.forEach((b) => {
  b.addEventListener("click", (e) => {
    tabName = e.target.dataset.tabName;
    buttons.forEach((b) => {
      b.classList.remove("itemActive");
    });

    itemTabs.forEach((t) => {
      t.classList.add("d-none");
      if (t.classList.contains(tabs[tabName])) {
        t.classList.remove("d-none");
        e.target.classList.add("itemActive");
      }
    });
  });
});

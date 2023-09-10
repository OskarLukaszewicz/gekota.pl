let flashButtons = document.querySelectorAll(".deleteFlashButton");

flashButtons.forEach((button) => {
  button.addEventListener("click", (e) => {
    e.target.parentElement.classList.add("d-none");
  });
});

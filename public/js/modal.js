const deleteButtons = document.querySelectorAll(".deleteButton");

deleteButtons.forEach((button) => {
  button.addEventListener("click", function (e) {
    e.preventDefault();

    const confirmDelete = confirm("Czy na pewno chcesz usunąć ten obiekt?");

    if (confirmDelete) {
      window.location.href = e.target.getAttribute("href");
    }
  });
});

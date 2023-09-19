document.addEventListener("DOMContentLoaded", function () {
  const checkboxes = document.querySelectorAll(".ajaxQueryCheckbox");
  const inStockInput = document.querySelectorAll(".ajaxQueryNumber");
  const priceInput = document.querySelectorAll(".ajaxQueryPrice");

  priceInput.forEach((i) => {
    i.addEventListener("blur", (e) => {
      e.target.disabled = "disabled";
      const xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            e.target.disabled = "";
          }
        }
      };
      xhr.open(
        "POST",
        `/admin/animals/ajax/price/${e.target.dataset.id}/${e.target.value}`,
        true
      );
      xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
      xhr.send();
    });
  });

  inStockInput.forEach((input) => {
    input.addEventListener("blur", (e) => {
      e.target.disabled = "disabled";
      const xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function () {
        siblingCheckbox =
          e.target.parentElement.nextElementSibling.firstElementChild
            .firstElementChild;
        animalContainer = e.target.parentElement.parentElement;
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            e.target.disabled = "";
            if (xhr.responseText == 0) {
              siblingCheckbox.checked = false;

              animalContainer.classList.add("itemInactive");
            }
            if (xhr.responseText > 0 && siblingCheckbox.checked == false) {
              console.log("jax");
              siblingCheckbox.checked = true;
              animalContainer.classList.remove("itemInactive");
            }
          } else {
            console.error("Wystąpił błąd podczas wykonywania żądania.");
          }
        }
      };
      xhr.open(
        "POST",
        `/admin/animals/ajax/${e.target.dataset.id}/${e.target.value}`,
        true
      );
      xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
      xhr.send();
    });
  });

  checkboxes.forEach((checkbox) => {
    checkbox.checked = checkbox.dataset.isAvaible;
    checkbox.addEventListener("change", function (e) {
      e.target.disabled = "disabled";
      const xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            e.target.disabled = "";
            if (checkbox.checked) {
              e.target.parentElement.parentElement.parentElement.classList.remove(
                "itemInactive"
              );
            } else {
              e.target.parentElement.parentElement.parentElement.classList.add(
                "itemInactive"
              );
            }
          } else {
            console.error("Wystąpił błąd podczas wykonywania żądania.");
          }
        }
      };
      xhr.open("POST", `/admin/animals/ajax/${e.target.value}`, true);
      xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
      xhr.send();
    });
  });
});

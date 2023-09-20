const notes = document.getElementById("notes");
let note;
let params;

notes.addEventListener("blur", (e) => {
  e.target.disabled = "disabled";
  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        e.target.disabled = "";
      }
    }
  };

  !e.target.value ? (note = "Miejsce na notatke") : (note = e.target.value);

  params = `note=${note}`;

  xhr.open("POST", `/admin/dashboard/ajax/note`, true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send(params);
});

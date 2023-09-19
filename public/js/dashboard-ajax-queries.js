const notes = document.getElementById("notes");

notes.addEventListener("blur", (e) => {
  e.target.disabled = "disabled";
  console.log(e.target);
  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        e.target.disabled = "";
      }
    }
  };
  xhr.open("POST", `/admin/dashboard/ajax/${e.target.value}`, true);
  xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
  xhr.send();
});

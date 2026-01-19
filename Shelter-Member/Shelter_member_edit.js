function enableEdit() {
  const editableElements = document.querySelectorAll(".editable-input");

  editableElements.forEach((el) => {
    el.removeAttribute("readonly");

    el.removeAttribute("disabled");

    el.classList.add("editing");
  });

  document.getElementById("viewMode").style.display = "none";
  document.getElementById("editMode").style.display = "flex";
  document.getElementById("uploadArea").style.display = "block";
}

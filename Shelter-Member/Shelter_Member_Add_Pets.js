function preview(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      document.getElementById("imgPreview").src = e.target.result;
      document.getElementById("imgPreview").style.display = "block";
      document.getElementById("placeholderText").style.display = "none";
    };
    reader.readAsDataURL(input.files[0]);
  }
}

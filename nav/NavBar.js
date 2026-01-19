document.addEventListener("DOMContentLoaded", function () {
  const notifIcon = document.getElementById("notif-icon");
  const notifDropdown = document.getElementById("notif-dropdown");
  const notifBadge = document.querySelector(".notif-badge");

  if (notifIcon && notifDropdown) {
    notifIcon.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();

      notifDropdown.classList.toggle("show");
    });

    window.addEventListener("click", function (event) {
      if (
        !notifDropdown.contains(event.target) &&
        !notifIcon.contains(event.target)
      ) {
        notifDropdown.classList.remove("show");
      }
    });
  }
});

function showSection(sectionId) {
  // Toggle Sections
  document.getElementById("pets-section").style.display =
    sectionId === "pets" ? "block" : "none";
  document.getElementById("requests-section").style.display =
    sectionId === "requests" ? "block" : "none";

  // Toggle Active Button State
  const buttons = document.querySelectorAll(".nav-btn");
  buttons.forEach((btn) => btn.classList.remove("active"));
  event.currentTarget.classList.add("active");
}

function openModal(id) {
  document.getElementById(id).style.display = "block";
}

function closeModal(id) {
  document.getElementById(id).style.display = "none";
}

function openAppointModal(petId) {
  document.getElementById("appoint_pet_id").value = petId;
  openModal("appointModal");
}

// Close modal if user clicks outside of it
window.onclick = function (event) {
  if (event.target.className === "modal") {
    event.target.style.display = "none";
  }
};

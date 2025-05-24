const toggleBtn = document.getElementById("menu-toggle");
const closeBtn = document.getElementById("close-btn");
const sidebar = document.getElementById("sidebar");

toggleBtn.addEventListener("click", () => {
  sidebar.classList.add("open");
});

closeBtn.addEventListener("click", () => {
  sidebar.classList.remove("open");
});

document.addEventListener('DOMContentLoaded', () => {
  const toggleButtons = document.querySelectorAll('.toggle-applicants-btn');

  toggleButtons.forEach(button => {
    button.addEventListener('click', () => {
      const offerId = button.dataset.offerId;
      const list = document.getElementById(`applicants-${offerId}`);
      if (list.style.display === 'none' || list.style.display === '') {
        list.style.display = 'block';
        button.textContent = 'Ocultar aplicantes';
      } else {
        list.style.display = 'none';
        button.textContent = 'Ver aplicantes';
      }
    });
  });
});

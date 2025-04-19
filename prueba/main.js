const toggleButton = document.getElementById('toggle-button');
const navLinks = document.getElementById('nav-links');

toggleButton.addEventListener('click', () => {
  navLinks.classList.toggle('show');
});

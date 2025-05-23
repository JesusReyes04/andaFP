function showError(mensaje) {
  const errorDiv = document.createElement("div");
  errorDiv.innerText = mensaje;
  if(mensaje === "¡Postulación exitosa!") {
    errorDiv.classList.add("success-message");
  }else{
    errorDiv.classList.add("error-message");
  }

  document.body.appendChild(errorDiv);

  // Mostrar el mensaje de error
  errorDiv.style.display = "block";

  setTimeout(() => {
    errorDiv.remove();
  }, 3000);
}
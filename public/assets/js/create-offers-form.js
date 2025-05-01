document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector(".offers-form");

  fetch("/andaFP/public/assets/data/suggestions.json")
    .then((response) => {
      if (!response.ok) throw new Error("Error al cargar sugerencias");
      return response.json();
    })
    .then((data) => {
      if (!data.placeSuggestions) {
        throw new Error("Formato JSON inválido");
      }
      placeSuggestions = data.placeSuggestions;
    })
    .catch((error) => {
      console.error("Error:", error);
      showError("Error cargando sugerencias. Recargue la página.");
      document.getElementById("province").placeholder =
        "Error cargando datos...";
    });

  // Validaciones al enviar el formulario
  form.addEventListener("submit", function (event) {
    const errorMessage = getFormValidationErrors();
    if (errorMessage !== "") {
      event.preventDefault(); // Evita el envío si hay errores
      showError(errorMessage);
    }
  });
});

// Validaciones del formulario: devuelve un string con el error o vacío si todo OK
function getFormValidationErrors() {
  const title = document.getElementById("title").value.trim();
  const description = document.getElementById("description").value.trim();
  const province = document.getElementById("province").value.trim();
  const city = document.getElementById("city").value.trim();
  const location = document.getElementById("location").value.trim();
  const startDate = document.getElementById("startDate").value;
  const schedule = document.getElementById("schedule").value.trim();
  const modality = document.getElementById("modality").value;

  if (!title || title.length < 5) {
    return "El título debe tener al menos 5 caracteres.";
  }

  if (!description || description.length < 10) {
    return "La descripción debe tener al menos 10 caracteres.";
  }

  if (!province) {
    return "La provincia es obligatoria.";
  }

  if (!city) {
    return "La ciudad es obligatoria.";
  }

  if (!location) {
    return "La ubicación es obligatoria.";
  }

  if (!startDate) {
    return "La fecha de inicio es obligatoria.";
  }

  if (!isFutureDate(startDate)) {
    return "La fecha de inicio debe ser hoy o una fecha futura.";
  }

  if (!schedule || schedule.length < 5) {
    return "El horario debe tener al menos 5 caracteres.";
  }

  if (!modality) {
    return "Selecciona una modalidad.";
  }

  return ""; // Todo validado correctamente
}

// Validación para fechas: debe ser hoy o futura
function isFutureDate(dateStr) {
  const selected = new Date(dateStr);
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  return selected >= today;
}

function showPlaceSuggestions() {
  const input = document.getElementById("province").value;
  const suggestionsList = document.getElementById("placeSuggestionsList");

  const filteredSuggestions = placeSuggestions.filter((suggestion) =>
    suggestion.toLowerCase().includes(input.toLowerCase())
  );

  if (filteredSuggestions.length > 0 && input !== "") {
    suggestionsList.innerHTML = filteredSuggestions
      .map(
        (suggestion) =>
          `<li onclick="selectSuggestion('${suggestion}', 'province', 'placeSuggestionsList')">${suggestion}</li>`
      )
      .join("");
    suggestionsList.style.display = "block";
  } else {
    suggestionsList.innerHTML = "";
    suggestionsList.style.display = "none";
  }
}

function selectSuggestion(suggestion, inputId, listId) {
  const input = document.getElementById(inputId);
  input.value = suggestion;
  document.getElementById(listId).style.display = "none";
}

function showError(mensaje) {
  const errorDiv = document.createElement("div");
  errorDiv.innerText = mensaje;
  errorDiv.classList.add("error-message");
  document.body.appendChild(errorDiv);
  errorDiv.style.display = "block";
  setTimeout(() => {
    errorDiv.remove();
  }, 3000);
}

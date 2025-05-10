const toggleBtn = document.getElementById("menu-toggle");
const closeBtn = document.getElementById("close-btn");
const sidebar = document.getElementById("sidebar");

toggleBtn.addEventListener("click", () => {
  sidebar.classList.add("open");
});

closeBtn.addEventListener("click", () => {
  sidebar.classList.remove("open");
});

let suggestions;
let placeSuggestions;

document.addEventListener("DOMContentLoaded", function () {
  fetch("../../public/assets/data/suggestions.json")
    .then((response) => {
      if (!response.ok) {
        showError("No se pudo cargar las sugerencias");
        throw new Error("Error al cargar el archivo JSON");
      }
      return response.json();
    })
    .then((data) => {
      if (!data.suggestions || !data.placeSuggestions) {
        showError("Formato de JSON inválido");
        throw new Error("Formato de JSON inválido");
      }

      suggestions = data.suggestions;
      placeSuggestions = data.placeSuggestions;
    })
    .catch((error) => {
      console.error("Error:", error);
      showError(
        "No se pudieron cargar las sugerencias. Intente recargar la página."
      );
      document.getElementById("searchInput").placeholder =
        "Error cargando datos...";
      document.getElementById("placeInput").placeholder =
        "Error cargando datos...";
    });
});

// Función para mostrar sugerencias del primer input
function showSuggestions() {
  const input = document.getElementById("searchInput").value;
  const suggestionsList = document.getElementById("suggestionsList");

  const filteredSuggestions = suggestions.filter((suggestion) =>
    suggestion.toLowerCase().includes(input.toLowerCase())
  );

  if (filteredSuggestions.length > 0 && input !== "") {
    suggestionsList.innerHTML = filteredSuggestions
      .map(
        (suggestion) =>
          `<li onclick="selectSuggestion('${suggestion}', 'searchInput', 'suggestionsList')">${suggestion}</li>`
      )
      .join("");
    suggestionsList.style.display = "block";
  } else {
    suggestionsList.innerHTML = "";
    suggestionsList.style.display = "none";
  }
}

// Función para mostrar sugerencias del segundo input
function showPlaceSuggestions() {
  const input = document.getElementById("placeInput").value;
  const suggestionsList = document.getElementById("placeSuggestionsList");

  const filteredSuggestions = placeSuggestions.filter((suggestion) =>
    suggestion.toLowerCase().includes(input.toLowerCase())
  );

  if (filteredSuggestions.length > 0 && input !== "") {
    suggestionsList.innerHTML = filteredSuggestions
      .map(
        (suggestion) =>
          `<li onclick="selectSuggestion('${suggestion}', 'placeInput', 'placeSuggestionsList')">${suggestion}</li>`
      )
      .join("");
    suggestionsList.style.display = "block";
  } else {
    suggestionsList.innerHTML = "";
    suggestionsList.style.display = "none";
  }
}

// Función genérica para seleccionar sugerencia en cualquier input
function selectSuggestion(suggestion, inputId, listId) {
  const input = document.getElementById(inputId);
  input.value = suggestion;
  document.getElementById(listId).style.display = "none";
}

function validateSearch(placeInput, searchInput) {
  const place = placeInput.value.trim().toLowerCase();
  const grado = searchInput.value.trim().toLowerCase();

  const validPlace = placeSuggestions.some((p) => p.toLowerCase() === place);
  const validGrado = suggestions.some((g) => g.toLowerCase() === grado);

  if (!validPlace || !validGrado) {
    showError(
      "El contenido de los campos debe coincidir con alguna de las sugerencias"
    );
    return;
  }

  // Aquí haces la búsqueda real
  console.log("Buscar con:", { lugar: place, grado: grado });
}

function showError(mensaje) {
  const errorDiv = document.createElement("div");
  errorDiv.innerText = mensaje;
  errorDiv.classList.add("error-message");

  document.body.appendChild(errorDiv);

  // Mostrar el mensaje de error
  errorDiv.style.display = "block";

  setTimeout(() => {
    errorDiv.remove();
  }, 3000);
}

function validateInputsValues(e, placeInput, searchInput) {
  const placeValue = placeInput.value.trim().toLowerCase();
  const searchValue = searchInput.value.trim().toLowerCase();

  const validGrado = suggestions.some((g) => g.toLowerCase() === searchValue);
  const validPlace = placeSuggestions.some(
    (p) => p.toLowerCase() === placeValue
  );

  if (
    !(validGrado && validPlace) &&
    !(validGrado && placeValue === "") &&
    !(validPlace && searchValue === "")
  ) {
    showError(
      "El contenido de los campos debe coincidir con alguna de las sugerencias."
    );
    e.preventDefault();
    return;
  }
}

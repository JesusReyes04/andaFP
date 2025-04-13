let suggestions = [];
let placeSuggestions = [];
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('.loginForm');

    // Cargar sugerencias
    fetch('/andaFP/public/assets/data/suggestions.json')
        .then(response => {
            if (!response.ok) throw new Error('Error al cargar sugerencias');
            return response.json();
        })
        .then(data => {
            if (!data.suggestions || !data.placeSuggestions) {
                throw new Error('Formato JSON inválido');
            }
            suggestions = data.suggestions;
            placeSuggestions = data.placeSuggestions;
        })
        .catch(error => {
            console.error('Error:', error);
            showError("Error cargando sugerencias. Recargue la página.");
            document.getElementById('specialty').placeholder = 'Error cargando datos...';
            document.getElementById('province').placeholder = 'Error cargando datos...';
        });

    // Validación de formulario
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        let errors = [];
        
        // Validar campos de sugerencias
        const province = document.getElementById('province').value.trim().toLowerCase();
        const specialty = document.getElementById('specialty').value.trim().toLowerCase();
        
        if (!placeSuggestions.some(p => p.toLowerCase() === province)) {
            errors.push("Seleccione una provincia válida de las sugerencias");
        }
        
        if (!suggestions.some(s => s.toLowerCase() === specialty)) {
            errors.push("Seleccione un ciclo formativo válido de las sugerencias");
        }

        if (errors.length > 0) {
            alert(errors.join('\n'));
            return;
        }
        
    });

    form.addEventListener('submit', function (e) {
        const firstName = document.getElementById('first_name').value.trim();
        const lastName = document.getElementById('last_name').value.trim();
        const username = document.getElementById('username').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const province = document.getElementById('province').value.trim();
        const city = document.getElementById('city').value.trim();
        const specialty = document.getElementById('specialty').value.trim();
        const educationalCenter = document.getElementById('educational_center').value.trim();
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password_confirm').value;
        const cv = document.getElementById('cv').files[0];
        const profilePicture = document.getElementById('profile_picture').files[0];

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const phoneRegex = /^[0-9]{9}$/;
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{12,}$/;

        let errors = [];

        if (firstName.replace(/\s+/g, '').length < 4) {
            errors.push("El nombre debe tener al menos 4 caracteres.");
        } else {
            document.getElementById('first_name').value = firstName.charAt(0).toUpperCase() + firstName.slice(1).toLowerCase();
        }

        if (lastName.replace(/\s+/g, '').length < 4) {
            errors.push("El apellido debe tener al menos 4 caracteres.");
        }else {
            document.getElementById('last_name').value = lastName.charAt(0).toUpperCase() + lastName.slice(1).toLowerCase();
        }

        if (username.replace(/\s+/g, '').length < 4) {
            errors.push("El nombre de usuario debe tener al menos 4 caracteres.");
        } else {
            document.getElementById('username').value = username.charAt(0).toUpperCase() + username.slice(1).toLowerCase();
        }

        if (!emailRegex.test(email)) {
            errors.push("Correo electrónico no válido.");
        }

        if (!phoneRegex.test(phone)) {
            errors.push("El teléfono debe tener 9 dígitos.");
        }

        if (!passwordRegex.test(password)) {
            errors.push("La contraseña debe tener al menos 12 caracteres, incluyendo mayúsculas, minúsculas, un número y un carácter especial.");
        }

        if (password !== passwordConfirm) {
            errors.push("Las contraseñas no coinciden.");
        }

        if (cv && cv.type !== "application/pdf") {
            errors.push("El currículum debe estar en formato PDF.");
        }

        if (profilePicture && !["image/jpeg", "image/jpg", "image/png"].includes(profilePicture.type)) {
            errors.push("La foto de perfil debe estar en formato JPG, JPEG o PNG.");
        }

        if (errors.length > 0) {
            e.preventDefault();
            alert(errors.join('\n'));
        }else{
            form.submit();
        }
    });

});


function showSuggestions() {
    const input = document.getElementById('specialty').value;
    const suggestionsList = document.getElementById('suggestionsList');

    const filteredSuggestions = suggestions.filter(suggestion =>
        suggestion.toLowerCase().includes(input.toLowerCase())
    );

    if (filteredSuggestions.length > 0 && input !== "") {
        suggestionsList.innerHTML = filteredSuggestions.map(suggestion =>
            `<li onclick="selectSuggestion('${suggestion}', 'specialty', 'suggestionsList')">${suggestion}</li>`
        ).join('');
        suggestionsList.style.display = 'block';
    } else {
        suggestionsList.innerHTML = '';
        suggestionsList.style.display = 'none';
    }
}

// Función para mostrar sugerencias del segundo input
function showPlaceSuggestions() {
    const input = document.getElementById('province').value;
    const suggestionsList = document.getElementById('placeSuggestionsList');

    const filteredSuggestions = placeSuggestions.filter(suggestion =>
        suggestion.toLowerCase().includes(input.toLowerCase())
    );

    if (filteredSuggestions.length > 0 && input !== "") {
        suggestionsList.innerHTML = filteredSuggestions.map(suggestion =>
            `<li onclick="selectSuggestion('${suggestion}', 'province', 'placeSuggestionsList')">${suggestion}</li>`
        ).join('');
        suggestionsList.style.display = 'block';
    } else {
        suggestionsList.innerHTML = '';
        suggestionsList.style.display = 'none';
    }
}
// Función genérica para seleccionar sugerencia en cualquier input
function selectSuggestion(suggestion, inputId, listId) {
    const input = document.getElementById(inputId);
    input.value = suggestion;
    document.getElementById(listId).style.display = 'none';
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

function validateInputsValues(event, provinceInput, specialtyInput) {
    const province = provinceInput.value.trim().toLowerCase();
    const specialty = specialtyInput.value.trim().toLowerCase();
    
    const validProvince = placeSuggestions.some(p => p.toLowerCase() === province);
    const validSpecialty = suggestions.some(s => s.toLowerCase() === specialty);

    if (!validProvince || !validSpecialty) {
        showError("Debe seleccionar valores de las sugerencias");
        event.preventDefault();
    }
}
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('.loginForm');

    // Cargar sugerencias
    fetch('/andaFP/public/assets/data/suggestions.json')
        .then(response => {
            if (!response.ok) throw new Error('Error al cargar sugerencias');
            return response.json();
        })
        .then(data => {
            if (!data.placeSuggestions) {
                throw new Error('Formato JSON inválido');
            }
            placeSuggestions = data.placeSuggestions;
        })
        .catch(error => {
            console.error('Error:', error);
            showError("Error cargando sugerencias. Recargue la página.");
            document.getElementById('province').placeholder = 'Error cargando datos...';
        });



    form.addEventListener('submit', function (e) {
        const taxId = document.getElementById('tax_id').value.trim();
        const name = document.getElementById('name').value.trim();
        const username = document.getElementById('username').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password_confirm').value;
        const phone = document.getElementById('phone').value.trim();
        const province = document.getElementById('province').value.trim();
        const city = document.getElementById('city').value.trim();
        const sector = document.getElementById('sector').value.trim();
        const description = document.getElementById('description').value.trim();
        const profilePicture = document.getElementById('profile_picture').files[0];

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const phoneRegex = /^[0-9]{9}$/;
        const cifRegex = /^[A-Za-z0-9]{8,10}$/; // Básico para CIF/NIF
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{12,}$/;

        let errors = [];

        if (!cifRegex.test(taxId)) {
            errors.push("CIF/NIF no válido (debe tener entre 8 y 10 caracteres alfanuméricos).");
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

        if (profilePicture && !["image/jpeg", "image/jpg", "image/png"].includes(profilePicture.type)) {
            errors.push("La imagen de perfil debe estar en formato JPG, JPEG o PNG.");
        }

        if (description.length < 20 || description.length > 500) {
            errors.push("La descripción debe tener entre 20 y 500 caracteres.");
        }

        if (errors.length > 0) {
            e.preventDefault();
            showError(errors.join('\n'));
        }
    });
});

function showPlaceSuggestions() {
    console.log('haciendo el intento de mostrar las sugerencias');
    const input = document.getElementById('province').value;
    const suggestionsList = document.getElementById('placeSuggestionsList');

    const filteredSuggestions = placeSuggestions.filter(suggestion =>
        suggestion.toLowerCase().includes(input.toLowerCase())
    );

    if (filteredSuggestions.length > 0 && input !== "") {
        suggestionsList.innerHTML = filteredSuggestions.map(suggestion =>
            `<li onclick="selectSuggestion('${suggestion}', 'province', 'placeSuggestionsList')">${suggestion}</li>`
        ).join('');
        console.log(suggestionsList.innerHTML);
        suggestionsList.style.display = 'block';
    } else {
        suggestionsList.innerHTML = '';
        suggestionsList.style.display = 'none';
    }
}

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
    errorDiv.style.display = "block";
    setTimeout(() => {
        errorDiv.remove();
    }, 3000);
}
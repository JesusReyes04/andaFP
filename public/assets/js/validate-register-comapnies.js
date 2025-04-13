document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('.loginForm');
    

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
            alert(errors.join('\n'));
        }
    });
});

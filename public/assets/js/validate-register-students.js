document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('.loginForm');

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
        }
    });
});
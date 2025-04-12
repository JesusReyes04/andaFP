document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            const inputs = form.querySelectorAll('input[type="text"], input[type="email"], input[type="tel"], textarea, input[type="password"]');

            let errors = [];

            const sqlKeywords = /(\b(SELECT|INSERT|UPDATE|DELETE|DROP|ALTER|EXEC|UNION|--|#)\b)/i;
            const sqlSpecialChars = /['"=;`]/;

            inputs.forEach(input => {
                const value = input.value.trim();

                if (sqlKeywords.test(value) || sqlSpecialChars.test(value)) {
                    errors.push(`El campo "${input.name}" contiene caracteres no permitidos.`);
                }
            });

            if (errors.length > 0) {
                e.preventDefault();
                alert(errors.join('\n'));
            }
        });
    });
});
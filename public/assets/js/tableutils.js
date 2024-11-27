function registerRowEditUtils() {
    const nullInputs = Array.from(document.querySelectorAll('input[name^="field-null-"]'));
    nullInputs.forEach((input) => {
        input.addEventListener('change', () => {
            const field = input.name.replace('field-null-', '');
            if (input.checked) {
                document.querySelector(`textarea[name="field-${field}"]`).value = '';
            }
        });
    });

    const valueInputs = Array.from(document.querySelectorAll('textarea[name^="field-"]'));
    valueInputs.forEach((input) => {
        input.addEventListener('change', () => {
            const field = input.name.replace('field-', '');
            const nullInput = document.querySelector(`input[name="field-null-${field}"]`);
            nullInput.checked = false;
        });
    });
}

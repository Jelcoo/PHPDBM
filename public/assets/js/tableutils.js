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

function registerSaveRequest(targetUrl) {
    const saveButton = document.querySelector('button[type="submit"]');
    saveButton.addEventListener('click', () => {
        const tableRows = Array.from(document.querySelectorAll('tr')).filter((row) => {
            return row.id.startsWith('field-');
        });

        const data = tableRows.map((row) => {
            const field = row.id.replace('field-', '');
            const nullInput = document.querySelector(`input[name="field-null-${field}"]`);
            const nullValue = nullInput.checked ? 1 : 0;
            const value = document.querySelector(`textarea[name="field-${field}"]`).value;
            return {
                field: field,
                null: nullValue,
                value: value
            };
        });

        fetch(targetUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                const statusClass = data.type === 'success' ? 'alert-success'
                : data.type === 'warning' ? 'alert-warning'
                : 'alert-danger';
                const statusBanner = document.querySelector('.alert');
                statusBanner.classList = 'alert ' + statusClass;
                statusBanner.textContent = data.message;
            });
    });
}

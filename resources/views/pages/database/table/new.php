<script src="/assets/js/sqlcolumnutils.js"></script>

<div class="alert d-none" role="alert"></div>

<form id="newTableForm">
    <span>Please enter a name for your new table</span>
    <div class="form-floating my-1">
        <input type="text" class="form-control" name="table_name" placeholder="Table name" />
        <label for="table_name">Table name</label>
    </div>

    <span>Table columns</span>
    <table class="table table-striped table-bordered">
        <thead>
            <th></th>
            <th>Name</th>
            <th>Type</th>
            <th>Length/value</th>
            <th>Default</th>
            <th>Null</th>
            <th>AI <i class="fa-solid fa-question" data-bs-toggle="tooltip" data-bs-title="Auto Increment"></i></th>
        </thead>
        <tbody>
        </tbody>
    </table>

    <div class="d-flex justify-content-between">
        <button type="button" class="btn btn-primary" id="addColumn">Add column</button>
        <button type="submit" class="btn btn-primary">Create table</button>
    </div>
</form>

<script>
    const databaseName = <?php echo json_encode($databaseName); ?>;
    let columnCounter = 0;

    const addColumnButton = document.getElementById('addColumn');
    document.addEventListener('DOMContentLoaded', () => {
        addColumnButton.click();
    });
    addColumnButton.addEventListener('click', () => {
        const table = document.querySelector('table');
        const tbody = table.querySelector('tbody');
        const tr = document.createElement('tr');
        tr.id = `field-${columnCounter}`;
        tr.innerHTML = `
            <td><button type="button" class="btn btn-danger" onclick="this.parentNode.parentNode.remove()"><i class="fa-solid fa-trash"></i></button></td>
            <td><input type="text" class="form-control" data-column-index="${columnCounter}" data-column-field="name" placeholder="Column name" /></td>
            <td><select class="form-select" data-column-index="${columnCounter}" data-column-field="type">${columnTypeOptions().map(element => element.outerHTML).join('')}</select></td>
            <td><input type="text" class="form-control" data-column-index="${columnCounter}" data-column-field="length" placeholder="Column length/value" /></td>
            <td>
                <select class="form-select" data-column-index="${columnCounter}" data-column-field="default">
                    <option value="NULL">NULL</option>
                    <option value="CURRENT_TIMESTAMP">CURRENT_TIMESTAMP</option>
                </select>
            </td>
            <td><input type="checkbox" data-column-index="${columnCounter}" data-column-field="isNull" /></td>
            <td><input type="checkbox" data-column-index="${columnCounter}" data-column-field="isAi" /></td>
        `;
        tbody.appendChild(tr);
        columnCounter++;
    });

    const newTableForm = document.getElementById('newTableForm');
    newTableForm.addEventListener('submit', (event) => {
        event.preventDefault();

        const data = {
            name: newTableForm.querySelector('input[name="table_name"]').value,
            columns: []
        };
        const formRows = newTableForm.querySelectorAll('tbody tr');
        formRows.forEach((row) => {
            const dataRow = {
                index: row.id.split('-')[1]
            };
            const inputs = row.querySelectorAll('input');
            inputs.forEach((input) => {
                if (input.type === 'checkbox') {
                    dataRow[input.dataset.columnField] = input.checked;
                    return;
                }
                dataRow[input.dataset.columnField] = input.value;
            });
            const selects = row.querySelectorAll('select');
            selects.forEach((select) => {
                dataRow[select.dataset.columnField] = select.value;
            });
            data.columns.push(dataRow);
        });

        fetch(`/database/${databaseName}/new`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(handleResponse);
    });
</script>

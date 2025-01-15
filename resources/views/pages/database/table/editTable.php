<script src="/assets/js/sqlcolumnutils.js"></script>

<nav style="--bs-breadcrumb-divider: '>';">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item"><a href="/database/<?php echo $databaseName; ?>"><?php echo $databaseName; ?></a></li>
        <li class="breadcrumb-item"><a href="/database/<?php echo $databaseName; ?>/<?php echo $tableName; ?>"><?php echo $tableName; ?></a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
</nav>

<div class="alert d-none" role="alert"></div>

<form id="editTableForm">
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
        <button type="submit" class="btn btn-primary">Update table</button>
    </div>
</form>

<script>
    const databaseName = <?php echo json_encode($databaseName); ?>;
    const tableColumns = <?php echo json_encode($tableColumns); ?>;
    let columnCounter = 0;

    tableColumns.forEach((column) => {
        const type = column.Type.match(/^\w+/)[0];
        const lengthArray = column.Type.match(/\((\d+)\)/);
        const length = lengthArray ? lengthArray[1] : "";

        const table = document.querySelector('table');
        const tbody = table.querySelector('tbody');
        const tr = addColumn(columnCounter, column, type, length);
        tbody.appendChild(tr);
        columnCounter++;
    });

    const addColumnButton = document.getElementById('addColumn');
    addColumnButton.addEventListener('click', () => {
        const table = document.querySelector('table');
        const tbody = table.querySelector('tbody');
        const tr = addColumn(columnCounter, {}, 'text', '');
        tbody.appendChild(tr);
        columnCounter++;
    });

    // const newTableForm = document.getElementById('newTableForm');
    // newTableForm.addEventListener('submit', (event) => {
    //     event.preventDefault();

    //     const data = {
    //         name: newTableForm.querySelector('input[name="table_name"]').value,
    //         columns: []
    //     };
    //     const formRows = newTableForm.querySelectorAll('tbody tr');
    //     formRows.forEach((row) => {
    //         const dataRow = {
    //             index: row.id.split('-')[1]
    //         };
    //         const inputs = row.querySelectorAll('input');
    //         inputs.forEach((input) => {
    //             if (input.type === 'checkbox') {
    //                 dataRow[input.dataset.columnField] = input.checked;
    //                 return;
    //             }
    //             dataRow[input.dataset.columnField] = input.value;
    //         });
    //         const selects = row.querySelectorAll('select');
    //         selects.forEach((select) => {
    //             dataRow[select.dataset.columnField] = select.value;
    //         });
    //         data.columns.push(dataRow);
    //     });

    //     fetch(`/database/${databaseName}/new`, {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/json'
    //         },
    //         body: JSON.stringify(data)
    //     })
    //     .then(handleResponse);
    // });
</script>

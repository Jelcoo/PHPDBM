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
    const tableName = <?php echo json_encode($tableName); ?>;
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

    const oldColumns = [];
    document.addEventListener('DOMContentLoaded', () => {
        const formRows = editTableForm.querySelectorAll('tbody tr');
        formRows.forEach((row) => {
            oldColumns.push(parseTableColumn(row));
        });
    });
    const editTableForm = document.getElementById('editTableForm');
    editTableForm.addEventListener('submit', (event) => {
        const newColumns = [];
        event.preventDefault();

        const formRows = editTableForm.querySelectorAll('tbody tr');
        formRows.forEach((row) => {
            newColumns.push(parseTableColumn(row));
        });

        const difference = [];
        oldColumns.forEach((oldColumn) => {
            const newColumn = newColumns.find((newColumn) => newColumn.index === oldColumn.index);

            // Column has been deleted
            if (!newColumn) {
                difference.push({
                    index: oldColumn.index,
                    action: 'delete',
                    column: oldColumn
                });
                return;
            }

            Object.keys(oldColumn).forEach((key) => {
                // Column has been updated
                if (oldColumn[key] !== newColumn[key]) {
                    const existingDifference = difference.find((difference) => difference.index === oldColumn.index);
                    if (existingDifference) {
                        existingDifference.updates.push({
                            key: key,
                            old: oldColumn[key],
                            new: newColumn[key]
                        });
                        return;
                    }

                    difference.push({
                        index: oldColumn.index,
                        action: 'update',
                        column: oldColumn,
                        updates: [{
                            key: key,
                            old: oldColumn[key],
                            new: newColumn[key]
                        }]
                    });
                }
            });
        });

        newColumns.forEach((newColumn) => {
            const oldColumn = oldColumns.find((oldColumn) => oldColumn.index === newColumn.index);

            // Column has been added
            if (!oldColumn) {
                difference.push({
                    index: newColumn.index,
                    action: 'add',
                    column: newColumn
                });
            }
        });

        fetch(`/database/${databaseName}/${tableName}/edit`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                difference: difference
            })
        })
        .then(handleResponse);
    });
</script>

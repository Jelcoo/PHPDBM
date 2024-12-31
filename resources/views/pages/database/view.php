<nav style="--bs-breadcrumb-divider: '>';">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active"><?php echo $databaseName; ?></li>
    </ol>
</nav>

<div class="d-flex align-items-center gap-2">
    <input class="form-control" id="search" type="text" placeholder="Search..." autofocus>
    <a class="btn btn-primary" href="/database/<?php echo $databaseName; ?>/export" data-bs-toggle="tooltip" data-bs-title="Export database (SQL script)"><i class="fa-solid fa-file-export"></i></a>
    <a class="btn btn-primary" href="/database/<?php echo $databaseName; ?>/new" data-bs-toggle="tooltip" data-bs-title="Create table"><i class="fa-solid fa-plus"></i></a>
</div>

<div class="table-responsive mt-2">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th></th>
                <th>Table Name</th>
                <th>Size</th>
                <th>Row Count</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($databaseTables) === 0) { ?>
                <tr>
                    <td colspan="100%">No tables found</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>
    const databaseName = <?php echo json_encode($databaseName); ?>;
    const allTables = <?php echo json_encode($databaseTables); ?>;
    const tbody = document.querySelector('tbody');

    document.addEventListener('DOMContentLoaded', () => {
        allTables.forEach((table) => {
            tbody.appendChild(tableToTableRow(table));
        });
    });

    const search = document.getElementById('search');
    search.addEventListener('input', () => {
        const filteredTables = allTables.filter((table) => table.toLowerCase().includes(search.value.toLowerCase()));
        tbody.innerHTML = '';
        filteredTables.forEach((table) => {
            tbody.appendChild(tableToTableRow(table));
        });
    });

    function tableToTableRow(table) {
        const row = document.createElement('tr');

        const edit = document.createElement('td');
        const editA = document.createElement('a');
        editA.className = 'btn btn-primary btn-sm';
        editA.href = `/database/${databaseName}/${table.name}/edit`;
        editA.innerHTML = '<i class="fa-solid fa-pencil"></i>';
        editA.setAttribute('data-bs-toggle', 'tooltip');
        editA.setAttribute('data-bs-title', 'Edit table');
        edit.appendChild(editA);

        const name = document.createElement('td');
        const nameA = document.createElement('a');
        nameA.href = `/database/${databaseName}/${table.name}`;
        nameA.textContent = table.name;
        name.appendChild(nameA);

        const size = document.createElement('td');
        size.textContent = formatBytes(table.size);

        const rowCount = document.createElement('td');
        rowCount.textContent = table.rowCount;

        row.appendChild(edit);
        row.appendChild(name);
        row.appendChild(size);
        row.appendChild(rowCount);

        return row;
    }
</script>

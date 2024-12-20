<nav style="--bs-breadcrumb-divider: '>';">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active"><?php echo $databaseName; ?></li>
    </ol>
</nav>

<div class="d-flex align-items-center gap-2">
    <input class="form-control" id="search" type="text" placeholder="Search..." autofocus>
    <a class="btn btn-primary" href="/database/<?php echo $databaseName; ?>/new" data-bs-toggle="tooltip" data-bs-title="Create table"><i class="fa-solid fa-plus"></i></a>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Table Name</th>
                <th scope="col">Size</th>
                <th scope="col">Row Count</th>
            </tr>
        </thead>
        <tbody>
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

        const name = document.createElement('td');
        const a = document.createElement('a');
        a.href = `/database/${databaseName}/${table.name}`;
        a.textContent = table.name;
        name.appendChild(a);

        const size = document.createElement('td');
        size.textContent = formatBytes(table.size);

        const rowCount = document.createElement('td');
        rowCount.textContent = table.rowCount;

        row.appendChild(name);
        row.appendChild(size);
        row.appendChild(rowCount);

        return row;
    }
</script>

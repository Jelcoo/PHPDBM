<h1><?php echo $databaseName; ?></h1>

<div class="d-flex align-items-center gap-2">
    <input class="form-control" id="search" type="text" placeholder="Search..." autofocus>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Table Name</th>
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
        const tr = document.createElement('tr');
        const td = document.createElement('td');
        const a = document.createElement('a');
        a.href = `/database/${databaseName}/${table}`;
        a.textContent = table;
        td.appendChild(a);
        tr.appendChild(td);

        return tr;
    }
</script>

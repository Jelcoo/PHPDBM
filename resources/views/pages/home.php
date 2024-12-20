<p>Welcome to the homepage <?php echo $user; ?></p>
<p>Here are your databases</p>

<div class="d-flex align-items-center gap-2">
    <input class="form-control" id="search" type="text" placeholder="Search..." autofocus>
</div>

<div class="table-responsive mt-2">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Database Name</th>
                <th>Size</th>
                <th>Table Count</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<script>
    const allDatabases = <?php echo json_encode($databases); ?>;
    const tbody = document.querySelector('tbody');

    document.addEventListener('DOMContentLoaded', () => {
        allDatabases.forEach((database) => {
            tbody.appendChild(dbToTableRow(database));
        });
    });

    const search = document.getElementById('search');
    search.addEventListener('input', () => {
        const filteredDatabases = allDatabases.filter((database) => database.toLowerCase().includes(search.value.toLowerCase()));
        tbody.innerHTML = '';
        filteredDatabases.forEach((database) => {
            tbody.appendChild(dbToTableRow(database));
        });
    });

    function dbToTableRow(database) {
        const row = document.createElement('tr');

        const name = document.createElement('td');
        const a = document.createElement('a');
        a.href = `/database/${database.name}`;
        a.textContent = database.name;
        name.appendChild(a);

        const size = document.createElement('td');
        size.textContent = formatBytes(database.size);

        const tableCount = document.createElement('td');
        tableCount.textContent = database.tableCount;

        row.appendChild(name);
        row.appendChild(size);
        row.appendChild(tableCount);

        return row;
    }
</script>

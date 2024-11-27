<p>Welcome to the homepage <?php echo $user; ?></p>
<p>Here are your databases</p>

<div class="d-flex align-items-center gap-2">
    <input class="form-control" id="search" type="text" placeholder="Search..." autofocus>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Database Name</th>
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
        const tr = document.createElement('tr');
        const td = document.createElement('td');
        const a = document.createElement('a');
        a.href = `/database/${database}`;
        a.textContent = database;
        td.appendChild(a);
        tr.appendChild(td);

        return tr;
    }
</script>
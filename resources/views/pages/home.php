<ul class="nav nav-tabs mb-2">
    <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="/">Home</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/run">Run SQL</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/users">Users</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/connections">Connections</a>
    </li>
</ul>

<p>You are connected to <?php echo $ipAddress . ":" . $port; ?> as <?php echo $username; ?></p>

<div class="alert d-none" role="alert"></div>

<div class="d-flex align-items-center gap-2 mt-2">
    <input class="form-control" id="search" type="text" placeholder="Search..." autofocus>
    <a class="btn btn-primary" href="/database/new" data-bs-toggle="tooltip" data-bs-title="Create database"><i class="fa-solid fa-plus"></i></a>
</div>

<div class="table-responsive mt-2">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th></th>
                <th>Database Name</th>
                <th>Size</th>
                <th>Table Count</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($databases) === 0) { ?>
                <tr>
                    <td colspan="100%">No databases found</td>
                </tr>
            <?php } ?>
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
        const filteredDatabases = allDatabases.filter((database) => database.name.toLowerCase().includes(search.value.toLowerCase()));
        tbody.innerHTML = '';
        filteredDatabases.forEach((database) => {
            tbody.appendChild(dbToTableRow(database));
        });
    });

    function dbToTableRow(database) {
        const row = document.createElement('tr');

        const deleteTd = document.createElement('td');
        const deleteA = document.createElement('a');
        deleteA.className = 'btn btn-danger btn-sm';
        deleteA.innerHTML = '<i class="fa-solid fa-trash"></i>';
        deleteA.setAttribute('data-bs-toggle', 'tooltip');
        deleteA.setAttribute('data-bs-title', 'Drop database');
        deleteA.addEventListener('click', async (e) => {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'Are you sure you want to drop the database?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#dd3333',
                confirmButtonText: 'Drop'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.scrollTo(0, 0);
                    fetch(`/database/${database.name}/delete`, {
                        method: 'POST'
                    })
                    .then(handleResponse);
                }
            });
        });
        deleteTd.appendChild(deleteA);

        const name = document.createElement('td');
        const a = document.createElement('a');
        a.href = `/database/${database.name}`;
        a.textContent = database.name;
        name.appendChild(a);

        const size = document.createElement('td');
        size.textContent = formatBytes(database.size);

        const tableCount = document.createElement('td');
        tableCount.textContent = database.tableCount;

        row.appendChild(deleteTd);
        row.appendChild(name);
        row.appendChild(size);
        row.appendChild(tableCount);

        return row;
    }
</script>

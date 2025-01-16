<ul class="nav nav-tabs mb-2">
    <li class="nav-item">
        <a class="nav-link" href="/">Home</a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="/run">Run SQL</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/users">Users</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/connections">Connections</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/bookmarks">Bookmarks</a>
    </li>
</ul>

<p>You are connected to <?php echo $ipAddress . ':' . $port; ?> as <?php echo $username; ?></p>

<div class="alert alert-warning" role="alert">
    <p class="mb-0">Please note that the SQL editor is not secure. All SQL statements will be executed.</p>
</div>

<hr />

<div class="mb-3">
    <label for="inputFile" class="form-label">Upload file to run</label>
    <input class="form-control" type="file" id="inputFile" placeholder="SQL file" />
</div>

<hr />

<div class="form-floating my-1">
    <select class="form-select" id="database_name">
        <option value="" selected>None</option>
        <?php foreach ($databases as $database) { ?>
            <option value="<?php echo $database; ?>"><?php echo $database; ?></option>
        <?php } ?>
    </select>
    <label for="database_name">Database name</label>
</div>

<p>Please enter the SQL you want to run below</p>
<div id="editor" style="height: 600px"></div>

<div class="d-flex align-items-center gap-2 mt-2">
    <button class="btn btn-primary" id="runSqlButton">Run SQL</button>
</div>

<div id="results" class="pt-2 pb-2"></div>

<?php require __DIR__ . '/../templates/monaco.php'; ?>

<script>
    const inputFile = document.getElementById('inputFile');
    inputFile.addEventListener('change', (e) => {
        const file = e.target.files[0];
        const reader = new FileReader();
        reader.onload = function() {
            window.editor.setValue(reader.result);
        };
        reader.readAsText(file);
    });

    const runSqlButton = document.getElementById('runSqlButton');
    runSqlButton.addEventListener('click', () => {
        const databaseName = document.getElementById('database_name').value;

        fetch(`/run`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    sql: window.editor.getValue(),
                    database: databaseName
                })
            })
            .then(response => {
                const statusBanner = document.querySelector('.alert');

                try {
                    response.json()
                        .then((data) => {
                            if (data.type === 'success') {
                                const resultDiv = document.getElementById('results');

                                resultDiv.innerHTML = '';
                                data.messages.forEach((msg) => {
                                    const msgDiv = document.createElement('div');
                                    const statusClass = msg.type === 'success' ? 'alert-success' : 'alert-danger';
                                    msgDiv.classList = 'alert ' + statusClass;
                                    msgDiv.role = 'alert';
                                    const msgP = document.createElement('p');
                                    msgP.classList = 'mb-0';
                                    if (msg.type === 'failure') {
                                        msgP.textContent = msg.message;
                                    } else if (msg.data.queryType === 'SELECT') {
                                        const table = document.createElement('table');
                                        table.classList = 'table table-striped table-bordered';
                                        const thead = document.createElement('thead');
                                        const tr = document.createElement('tr');
                                        const rows = Object.keys(msg.data.result[0]);
                                        rows.forEach((row) => {
                                            const th = document.createElement('th');
                                            th.textContent = row;
                                            tr.appendChild(th);
                                        });
                                        thead.appendChild(tr);
                                        table.appendChild(thead);
                                        const tbody = document.createElement('tbody');
                                        msg.data.result.forEach((row) => {
                                            const tr = document.createElement('tr');
                                            rows.forEach((col) => {
                                                const td = document.createElement('td');
                                                td.classList = 'align-middle text-truncate text-truncate-width';
                                                td.textContent = row[col];
                                                td.title = row[col];
                                                tr.appendChild(td);
                                            });
                                            tbody.appendChild(tr);
                                        });
                                        table.appendChild(tbody);
                                        msgP.appendChild(table);
                                    } else {
                                        msgP.textContent = `Updated ${msg.data.result} rows`;
                                    }
                                    const queryP = document.createElement('code');
                                    queryP.classList = 'fst-italic mb-0';
                                    queryP.textContent = msg.original;
                                    msgDiv.appendChild(queryP);
                                    msgDiv.appendChild(msgP);
                                    resultDiv.appendChild(msgDiv);
                                });
                            } else {
                                const statusClass = data.type === 'warning' ? 'alert-warning' : 'alert-danger';
                                statusBanner.classList = 'alert ' + statusClass;
                                statusBanner.textContent = data.message;
                            }
                        });
                } catch (e) {
                    statusBanner.classList = 'alert alert-danger';
                    statusBanner.textContent = response;
                    return;
                }
            });
    });
</script>
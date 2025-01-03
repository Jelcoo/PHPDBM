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
</ul>

<p>You are connected to <?php echo $ipAddress . ":" . $port; ?> as <?php echo $username; ?></p>

<div class="alert alert-warning" role="alert">
    <p class="mb-0">Please note that the SQL editor is not secure. All SQL statements will be executed.</p>
</div>

<hr />

<div class="mb-3">
    <label for="inputFile" class="form-label">Upload file to run</label>
    <input class="form-control" type="file" id="inputFile" placeholder="SQL file" />
</div>

<hr />

<p>Please enter the SQL you want to run below</p>
<div id="editor" style="height: 600px"></div>

<div class="d-flex align-items-center gap-2 mt-4 mb-4">
    <button class="btn btn-primary" id="runSqlButton">Run SQL</button>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/monaco-editor/min/vs/editor/editor.main.min.css">

<script>
    var require = {
        paths: {
            vs: 'https://cdn.jsdelivr.net/npm/monaco-editor/min/vs'
        }
    };
</script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/monaco-editor/min/vs/loader.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/monaco-editor/min/vs/editor/editor.main.js"></script>

<script>
    require(['vs/editor/editor.main'], function() {
        window.editor = monaco.editor.create(document.getElementById('editor'), {
            value: '',
            language: 'sql',
            automaticLayout: true,
            theme: "vs-dark",
        });
    });

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
        fetch(`/run`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    sql: window.editor.getValue()
                })
            })
            .then(handleResponse);
    });
</script>

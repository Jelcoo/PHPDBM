<nav style="--bs-breadcrumb-divider: '>';">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Run SQL</li>
    </ol>
</nav>

<p>You are connected to <?php echo $ipAddress . ":" . $port; ?> as <?php echo $username; ?></p>
<p>Please enter the SQL you want to run below</p>

<div class="alert d-none" role="alert"></div>

<div id="editor" style="height: 600px"></div>

<!-- <textarea class="form-control" id="sql" rows="10" autofocus placeholder="SQL"></textarea> -->

<div class="d-flex align-items-center gap-2 mt-2">
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
        .then(response => response.json())
        .then(response => {
            console.log(response);
        });
    });
</script>

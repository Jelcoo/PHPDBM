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
</script>

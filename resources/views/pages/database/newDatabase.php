<nav style="--bs-breadcrumb-divider: '>';">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">New database</li>
    </ol>
</nav>

<div class="alert d-none" role="alert"></div>

<form id="newDatabaseForm">
    <span>Please enter a name for your new database</span>
    <div class="form-floating my-1">
        <input type="text" class="form-control" name="database_name" placeholder="Database name" />
        <label for="database_name">Database name</label>
    </div>

    <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primary">Create database</button>
    </div>
</form>

<script>
    const newDatabaseForm = document.getElementById('newDatabaseForm');
    newDatabaseForm.addEventListener('submit', (event) => {
        event.preventDefault();

        fetch(`/database/new`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                name: newDatabaseForm.querySelector('input[name="database_name"]').value,
            })
        })
        .then(handleResponse);
    });
</script>

<nav style="--bs-breadcrumb-divider: '>';">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item"><a href="/database/<?php echo $databaseName; ?>"><?php echo $databaseName; ?></a></li>
        <li class="breadcrumb-item"><a href="/database/<?php echo $databaseName; ?>/<?php echo $tableName; ?>"><?php echo $tableName; ?></a></li>
        <li class="breadcrumb-item active">Delete row</li>
    </ol>
</nav>

<div class="alert d-none" role="alert"></div>

<table class="table table-striped table-bordered">
    <thead>
        <?php foreach ($tableColumns as $column) { ?>
            <th scope="col"><?php echo $column['Field']; ?></th>
        <?php } ?>
    </thead>
    <tbody>
        <tr class="row-<?php echo isset($primaryKey) ? $tableRow[$primaryKey] : 'unknown'; ?>">
            <?php foreach ($tableColumns as $column) { ?>
                <?php $value = $tableRow[$column['Field']]; ?>
                <td class="align-middle text-truncate text-truncate-width <?php echo $value === null || empty($value) ? 'fst-italic text-danger-emphasis' : ''; ?> field-<?php echo $column['Field']; ?>"><?php echo ($value === null ? 'NULL' : empty($value)) ? 'EMPTY' : $value; ?></td>
            <?php } ?>
        </tr>
    </tbody>
</table>

<?php if ($primaryKey) { ?>
    <button id="deleteRowButton" class="btn btn-danger">Delete row</button>

    <script>
        const deleteButton = document.getElementById('deleteRowButton');
        deleteButton.addEventListener('click', () => {
            fetch('/database/<?php echo $databaseName; ?>/<?php echo $tableName; ?>/delete/<?php echo $primaryKey; ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
            })
            .then(handleResponse);
        });
    </script>
<?php } ?>

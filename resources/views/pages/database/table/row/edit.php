<h1><a href="/database/<?php echo $databaseName; ?>"><?php echo $databaseName; ?></a> - <a href="/database/<?php echo $databaseName; ?>/<?php echo $tableName; ?>"><?php echo $tableName; ?></a></h1>
<div class="alert d-none" role="alert"></div>
<table class="table table-striped table-bordered">
    <thead>
        <th>Column</th>
        <th>Type</th>
        <th>Null</th>
        <th>Value</th>
    </thead>
    <tbody>
        <?php foreach ($tableColumns as $column) { ?>
            <tr id="field-<?php echo $column['Field']; ?>">
                <td class="align-middle text-truncate text-truncate-width"><?php echo $column['Field']; ?> <?php echo $column['Key'] === 'PRI' ? '<i class="fa-solid fa-key text-warning"></i>' : ''; ?></td>
                <td class="align-middle text-truncate text-truncate-width"><?php echo $column['Type']; ?></td>
                <td class="align-middle text-truncate text-truncate-width">
                    <input type="checkbox" name="field-null-<?php echo $column['Field']; ?>" value="1" <?php echo $column['Null'] === 'YES' ? '' : 'disabled'; ?> <?php echo $tableRow[$column['Field']] === null ? 'checked' : ''; ?>>
                </td>
                <td class="align-middle text-truncate text-truncate-width">
                    <textarea class="form-control" name="field-<?php echo $column['Field']; ?>" rows="<?php echo strlen($tableRow[$column['Field']]) > 100 ? '3' : '1'; ?>"><?php echo $tableRow[$column['Field']]; ?></textarea>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<div class="d-flex justify-content-end">
    <button type="submit" class="btn btn-primary">Save</button>
</div>

<script>
    registerSaveRequest('/database/<?php echo $databaseName; ?>/<?php echo $tableName; ?>/edit/<?php echo $primaryKey; ?>');
    registerRowEditUtils();
</script>

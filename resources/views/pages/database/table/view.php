<h1><?= $databaseName ?> - <?= $tableName ?></h1>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <?php foreach ($tableColumns as $column): ?>
                <th scope="col"><?= $column['Field'] ?></th>
            <?php endforeach; ?>
        </thead>
        <tbody>
            <?php foreach ($tableRows as $row): ?>
                <tr>
                    <?php foreach ($tableColumns as $column): ?>
                        <td class="text-truncate"><?= $row[$column['Field']] ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

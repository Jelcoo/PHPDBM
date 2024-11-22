<h1><?= $databaseName ?></h1>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Table Name</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($databaseTables as $table): ?>
                <tr>
                    <td><a href="/database/<?= $databaseName ?>/table/<?= $table ?>"><?= $table ?></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

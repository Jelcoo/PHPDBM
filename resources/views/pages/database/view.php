<h1><?php echo $databaseName; ?></h1>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Table Name</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($databaseTables as $table) { ?>
                <tr>
                    <td><a href="/database/<?php echo $databaseName; ?>/<?php echo $table; ?>"><?php echo $table; ?></a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

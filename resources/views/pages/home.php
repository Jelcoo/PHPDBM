<p>Welcome to the homepage <?= $user ?></p>
<p>Here are your databases</p>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Database Name</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($databases as $database): ?>
                <tr>
                    <td><a href="/database/<?= $database ?>"><?= $database ?></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

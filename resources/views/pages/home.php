<p>Welcome to the homepage <?= $user ?></p>
<p>Here are your databases</p>
<table class="table">
    <thead>
        <tr>
            <th scope="col">Database Name</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($databases as $database): ?>
            <tr>
                <td><?= $database ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

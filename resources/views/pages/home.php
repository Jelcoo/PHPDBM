<p>Welcome to the homepage <?= $user ?></p>
<p>Here are your databases</p>

<?php foreach ($databases as $database): ?>
    <p><?= $database ?></p>
<?php endforeach; ?>

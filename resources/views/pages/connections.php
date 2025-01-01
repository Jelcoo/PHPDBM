<ul class="nav nav-tabs mb-2">
    <li class="nav-item">
        <a class="nav-link" href="/">Home</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/run">Run SQL</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/users">Users</a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="/connections">Connections</a>
    </li>
</ul>

<p>You are connected to <?php echo $ipAddress . ":" . $port; ?> as <?php echo $username; ?></p>

<div class="alert d-none" role="alert"></div>

<div class="table-responsive mt-2">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Id</th>
                <th>User</th>
                <th>Host</th>
                <th>Database</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($connections as $connection) { ?>
                <tr>
                    <td><?php echo $connection['Id']; ?></td>
                    <td><?php echo $connection['User']; ?></td>
                    <td><?php echo $connection['Host']; ?></td>
                    <td><?php echo $connection['db']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

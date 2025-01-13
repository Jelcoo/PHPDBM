<ul class="nav nav-tabs mb-2">
    <li class="nav-item">
        <a class="nav-link" href="/">Home</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/run">Run SQL</a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="/users">Users</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/connections">Connections</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="/bookmarks">Bookmarks</a>
    </li>
</ul>

<p>You are connected to <?php echo $ipAddress . ":" . $port; ?> as <?php echo $username; ?></p>

<div class="alert d-none" role="alert"></div>

<div class="table-responsive mt-2">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>User</th>
                <th>Host</th>
                <th>Has password</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) { ?>
                <tr>
                    <td><?php echo $user['User']; ?></td>
                    <td><?php echo $user['Host']; ?></td>
                    <td class="<?php echo $user['hasPassword'] ? 'text-success-emphasis' : 'text-danger-emphasis'; ?>"><?php echo $user['hasPassword'] ? 'Yes' : 'No'; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

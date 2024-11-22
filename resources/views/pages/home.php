<p>Welcome to the homepage <?php echo $user; ?></p>
<p>Here are your databases</p>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Database Name</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($databases as $database) { ?>
                <tr>
                    <td><a href="/database/<?php echo $database; ?>"><?php echo $database; ?></a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

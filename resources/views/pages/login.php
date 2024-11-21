<div class="d-flex align-items-center py-4">
    <main class="form-signin w-100 m-auto">
        <?php
        if (isset($error)) {
            echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
        } elseif (isset($success)) {
            echo '<div class="alert alert-success" role="alert">' . $success . '</div>';
        }
        ?>
        <form action="/login" method="POST">
            <h1 class="h3 fw-normal">Sign in</h1>
            <p class="text-body-secondary">Please sign in with the credentials of your database</p>

            <div class="form-floating my-1">
                <input type="text" class="form-control" name="ip_address" placeholder="IP address" <?php echo isset($fields['ip_address']) ? 'value="' . $fields['ip_address'] . '"' : ''; ?>>
                <label for="ip_address">IP address</label>
            </div>
            <div class="form-floating my-1">
                <input type="number" class="form-control" name="port" value="3306" placeholder="Port" <?php echo isset($fields['port']) ? 'value="' . $fields['port'] . '"' : ''; ?>>
                <label for="port">Port</label>
            </div>
            <div class="form-floating my-1">
                <input type="text" class="form-control" name="username" placeholder="Username" <?php echo isset($fields['username']) ? 'value="' . $fields['username'] . '"' : ''; ?>>
                <label for="username">Username</label>
            </div>
            <div class="form-floating my-1">
                <input type="password" class="form-control rounded-top" name="password" placeholder="Password">
                <label for="password">Password</label>
            </div>

            <button class="btn btn-primary w-100 py-2 my-3" type="submit">Sign in</button>
        </form>
    </main>
</div>

<div class="d-flex align-items-center py-4">
    <main class="form-signin w-100 m-auto">
        <?php
        if (isset($error)) {
            echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
        }
        ?>
        <form action="/login" method="POST">
            <h1 class="h3 fw-normal">Sign in</h1>
            <p class="text-body-secondary">Please sign in with the credentials of your database</p>

            <div class="form-floating my-1">
                <input type="text" class="form-control" name="ip_address" placeholder="IP address">
                <label for="floatingInput">IP address</label>
            </div>
            <div class="form-floating my-1">
                <input type="number" class="form-control" name="port" value="3306" placeholder="Port">
                <label for="floatingPassword">Port</label>
            </div>
            <div class="form-floating my-1">
                <input type="text" class="form-control" name="username" placeholder="Username">
                <label for="floatingPassword">Username</label>
            </div>
            <div class="form-floating my-1">
                <input type="password" class="form-control" name="password" placeholder="Password">
                <label for="floatingPassword">Password</label>
            </div>

            <button class="btn btn-primary w-100 py-2 my-3" type="submit">Sign in</button>
        </form>
    </main>
</div>

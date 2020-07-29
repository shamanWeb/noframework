<?php
/**
 * @var string $error
 * @var string $username
 */
?>

<div class="row">
    <div class="col-sm-6">
        <form method="POST">
            <h2>Please sign in</h2>
            <label for="inputUsername" class="sr-only">Username:</label>
            <input name="username" id="inputUsername" class="form-control" placeholder="Username" required autofocus
                   value="<?php echo $username; ?>">
            <br>
            <label for="inputPassword" class="sr-only">Password</label>
            <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password"
                   required>

            <?php if (!empty($error)) { ?>
                <div class="alert alert-warning" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php } ?>
            <br>
            <button name="action" class="btn btn-lg btn-primary btn-block">Sign in</button>
        </form>
    </div>
</div>
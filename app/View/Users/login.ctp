<div class="container d-flex justify-content-center align-items-center vh-100">
    <form action="<?= $url ?>" method="POST" class="p-5" style="border: solid black 1px; border-radius: 25px; box-shadow: 5px 5px skyblue;">
        <?php
        if (isset($_SESSION['validationErrors'])) {
        ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($_SESSION['validationErrors'] as $errors) {
                        foreach ($errors as $error) {
                            echo "<li>$error</li>";
                        }
                    } ?>
                </ul>
            </div>
        <?php
            unset($_SESSION['validationErrors']);
        }
        ?>
        <legend>Login</legend>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label text-capitalize">password</label>
            <input type="password" id="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
            <a href="<?= Router::url('/signup') ?>">Register</a>
        </div>
    </form>
</div>
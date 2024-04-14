<div class="container d-flex justify-content-center align-items-center vh-100">


    <form action="<?= Router::url('/register') ?>" method="POST" class="p-5" style="border: solid black 1px; border-radius: 25px; box-shadow: 5px 5px skyblue;">
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
        <legend>Register</legend>
        <div class="mb-3">
            <label for="name" class="form-label text-capitalize">name</label>
            <input type="name" id="name" name="name" class="form-control">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label text-capitalize">password</label>
            <input type="password" id="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label for="confirm-password" class="form-label text-capitalize">confirm password</label>
            <input type="password" id="confirm-password" name="confirm-password" class="form-control">
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
            <a href="<?= Router::url('/login') ?>">Sign In</a>
        </div>
    </form>
</div>
<?= $this->Html->script('jquery') ?>
<?= $this->Html->script('sweetalert') ?>
<script>
    $(document).ready(function() {
        <?php
        if (isset($_SESSION['registered'])) {
            $href = Router::url('/directlogin');
            echo "Swal.fire({
                        title: 'Thank you for registering',
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Back to homepage',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '$href';
                        }
                    });";
            $_SESSION['direct'] = $_SESSION['registered']['email'];
            unset($_SESSION['registered']);
        } else {
            if (isset($_SESSION['direct'])) {
                unset($_SESSION['direct']);
            }
        }
        ?>
    })
</script>
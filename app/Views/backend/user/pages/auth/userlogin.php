<?= $this->extend('backend/layout/auth-layout.php') ?>
<?= $this->section('content') ?>

<div class="login-box bg-white box-shadow border-radius-10 ">
    <div class="d-flex justify-content-between mb-4">
        <a href="<?= route_to('user.register.form') ?>" class="btn btn-success w-45">
            <i class="fa fa-user-plus"></i> Register
        </a>
        <a href="<?= route_to('admin.login.form') ?>" class="btn btn-warning w-45">
            <i class="fa fa-user-shield"></i> Admin Login
        </a>
    </div>
    <div class="login-title">
        <h2 class="text-center text-primary">User Login</h2>
    </div>
    <?php $validation = \Config\Services::validation(); ?>
    <form action="<?= route_to('user.login.handler') ?>" method="POST">
        <?= csrf_field() ?>
        <?php if (!empty(session()->getFlashdata('success'))): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <?php if (!empty(session()->getFlashdata('fail'))): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('fail'); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <div class="input-group custom">
            <input type="email" class="form-control form-control-lg" placeholder="Email" name="email" value="<?= set_value('email') ?>">
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
            </div>
        </div>
        <?php if ($validation->getError('email')): ?>
            <div class="d-block text-danger" style="margin-top: -25px; margin-bottom: 15px;">
                <?= $validation->getError('email') ?>
            </div>
        <?php endif; ?>
        <div class="input-group custom">
            <input type="password" class="form-control form-control-lg" placeholder="Password" id="password" name="password">
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility()">Show</button>
            </div>
        </div>
        <?php if ($validation->getError('password')): ?>
            <div class="d-block text-danger" style="margin-top: -25px; margin-bottom: 15px;">
                <?= $validation->getError('password') ?>
            </div>
        <?php endif; ?>
        <div class="row pb-30">
            <div class="col-6">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="customCheck2">
                    <label class="custom-control-label" for="customCheck2">Remember</label>
                </div>
            </div>
            <div class="col-6">
                <div class="forgot-password">
                    <a href="<?= route_to('user.forgot_password.form') ?>">Forgot Password</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="input-group mb-0">
                    <input class="btn btn-primary btn-lg btn-block" type="submit" value="Sign In">
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function togglePasswordVisibility() {
        var passwordField = document.getElementById("password");
        var toggleButton = event.target;
        if (passwordField.type === "password") {
            passwordField.type = "text";
            toggleButton.innerText = "Hide";
        } else {
            passwordField.type = "password";
            toggleButton.innerText = "Show";
        }
    }
</script>

<?= $this->endSection(); ?>

<?= $this->extend('backend/layout/auth-layout.php') ?>
<?= $this->section('content') ?>

<div class="login-box bg-white box-shadow border-radius-10">
    <div class="login-title">
        <h2 class="text-center text-primary">Reset Password</h2>
        <p class="text-center text-muted">Enter your new password below</p>
    </div>

    <!-- Display flash messages -->
    <?php if (session()->getFlashdata('fail')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('fail') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <form action="<?= route_to('user.reset_password.handler') ?>" method="POST">
        <?= csrf_field() ?>
        <input type="hidden" name="token" value="<?= $token ?>">

        <!-- New Password Field -->
        <div class="input-group custom mb-4">
            <input 
                type="password" 
                class="form-control form-control-lg" 
                placeholder="New Password" 
                name="new_password" 
                id="new_password"
                required>
            <div class="input-group-append custom">
                <span class="input-group-text">
                    <i class="dw dw-padlock1"></i>
                </span>
            </div>
        </div>
        <?php if (isset($validation) && $validation->hasError('new_password')): ?>
            <div class="text-danger mb-3">
                <?= $validation->getError('new_password') ?>
            </div>
        <?php endif; ?>

        <!-- Confirm Password Field -->
        <div class="input-group custom mb-4">
            <input 
                type="password" 
                class="form-control form-control-lg" 
                placeholder="Confirm Password" 
                name="confirm_new_password" 
                id="confirm_new_password"
                required>
            <div class="input-group-append custom">
                <span class="input-group-text">
                    <i class="dw dw-padlock1"></i>
                </span>
            </div>
        </div>
        <?php if (isset($validation) && $validation->hasError('confirm_new_password')): ?>
            <div class="text-danger mb-3">
                <?= $validation->getError('confirm_new_password') ?>
            </div>
        <?php endif; ?>

        <!-- Show/Hide Password Toggle -->
        <div class="d-flex justify-content-between mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="showPasswordToggle" onclick="togglePasswordVisibility()">
                <label class="form-check-label" for="showPasswordToggle">Show Passwords</label>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="input-group mb-0">
                    <input class="btn btn-primary btn-lg btn-block" type="submit" value="Reset Password">
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function togglePasswordVisibility() {
        const newPassword = document.getElementById('new_password');
        const confirmPassword = document.getElementById('confirm_new_password');
        const type = newPassword.type === 'password' ? 'text' : 'password';
        newPassword.type = type;
        confirmPassword.type = type;
    }
</script>

<?= $this->endSection(); ?>

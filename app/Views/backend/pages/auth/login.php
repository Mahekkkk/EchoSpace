<?= $this->extend('backend/layout/auth-layout.php') ?>
<?= $this->section('content') ?>

<div class="login-box bg-white box-shadow border-radius-10">
     <!-- Buttons for Register as User and Login as User -->
     <div class="text-center mb-3" style="margin-top: 20px;">
    <a href="<?= route_to('user.register.form') ?>" 
       class="btn btn-secondary" 
       style="background-color: #4CAF50; 
              color: white; 
              font-weight: bold; 
              padding: 10px 20px; 
              margin-right: 10px; 
              border-radius: 5px; 
              text-decoration: none; 
              transition: background-color 0.3s ease;" 
       onmouseover="this.style.backgroundColor='#45a049';" 
       onmouseout="this.style.backgroundColor='#4CAF50';">
        Register as a New User
    </a>
    <a href="<?= route_to('user.login.form') ?>" 
       class="btn btn-info" 
       style="background-color: #17a2b8; 
              color: white; 
              font-weight: bold; 
              padding: 10px 20px; 
              border-radius: 5px; 
              text-decoration: none; 
              transition: background-color 0.3s ease;" 
       onmouseover="this.style.backgroundColor='#138496';" 
       onmouseout="this.style.backgroundColor='#17a2b8';">
        Login as User
    </a>
</div>

    <div class="login-title">
        <h2 class="text-center text-primary">Login as Admin EchoSpace</h2>
    </div>
    <?php $validation = \Config\Services::validation(); ?>
    <form action="<?= route_to('admin.login.handler') ?>" method="POST">
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
            <input type="text" class="form-control form-control-lg" placeholder="Username or Email" name="login_id" value="<?= set_value('login_id') ?>">
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
            </div>
        </div>
        <?php if ($validation->getError('login_id')): ?>
            <div class="d-block text-danger" style="margin-top: -25px; margin-bottom: 15px;">
                <?= $validation->getError('login_id') ?>
            </div>
        <?php endif; ?>
        <div class="input-group custom">
            <input type="password" class="form-control form-control-lg" placeholder="**********" id="password" name="password" value="<?= set_value('password') ?>">
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
                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                    <label class="custom-control-label" for="customCheck1">Remember</label>
                </div>
            </div>
            <div class="col-6">
                <div class="forgot-password">
                    <a href="<?= route_to('admin.forgot.form') ?>">Forgot Password</a>
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
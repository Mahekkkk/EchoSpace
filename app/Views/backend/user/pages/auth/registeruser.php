<?= $this->extend('backend/layout/auth-layout.php') ?>
<?= $this->section('content') ?>

<div class="register-box bg-white box-shadow border-radius-10">
    <!-- Add Login Button -->
    <div class="text-center mb-3" style="margin-top: 20px;">
        <a href="<?= route_to('user.login.form') ?>" 
           style="background-color: #4CAF50; 
                  color: white; 
                  font-weight: bold; 
                  padding: 10px 20px; 
                  border-radius: 5px; 
                  text-decoration: none; 
                  transition: background-color 0.3s ease;"
           onmouseover="this.style.backgroundColor='#45a049';" 
           onmouseout="this.style.backgroundColor='#4CAF50';">
            Already have an account? Login
        </a>
    </div>

    <div class="register-title">
        <h2 class="text-center text-primary">Register User</h2>
    </div>
    <?php $validation = \Config\Services::validation(); ?>
    <form action="<?= route_to('user.register.handler') ?>" method="POST" id="register-form">
        <?= csrf_field() ?>
        <?php if (!empty(session()->getFlashdata('success'))): ?>
            <div class="alert alert-success">
                <?= esc(session()->getFlashdata('success')) ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <?php if (!empty(session()->getFlashdata('fail'))): ?>
            <div class="alert alert-danger">
                <?php 
                    $failMessages = session()->getFlashdata('fail');
                    if (is_array($failMessages)):
                        foreach ($failMessages as $message): ?>
                            <p><?= esc($message) ?></p>
                        <?php endforeach; 
                    else: ?>
                        <p><?= esc($failMessages) ?></p>
                    <?php endif; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <!-- Name Field -->
        <div class="input-group custom">
            <input type="text" class="form-control form-control-lg" placeholder="Full Name" name="name" value="<?= set_value('name') ?>" required>
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
            </div>
        </div>
        <?php if ($validation->getError('name')): ?>
            <div class="d-block text-danger" style="margin-top: -25px; margin-bottom: 15px;">
                <?= esc($validation->getError('name')) ?>
            </div>
        <?php endif; ?>

        <!-- Email Field -->
        <div class="input-group custom">
            <input type="email" class="form-control form-control-lg" placeholder="Email Address" name="email" value="<?= set_value('email') ?>" required>
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="dw dw-email"></i></span>
            </div>
        </div>
        <?php if ($validation->getError('email')): ?>
            <div class="d-block text-danger" style="margin-top: -25px; margin-bottom: 15px;">
                <?= esc($validation->getError('email')) ?>
            </div>
        <?php endif; ?>

        <!-- Password Field -->
        <div class="input-group custom">
            <input type="password" class="form-control form-control-lg" placeholder="Password" id="password" name="password" onkeyup="validatePassword()" required>
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility()">Show</button>
            </div>
        </div>
        <div id="password-validation" class="d-block text-danger" style="margin-top: -10px; margin-bottom: 15px; font-size: 0.9rem;"></div>

        <?php if ($validation->getError('password')): ?>
            <div class="d-block text-danger" style="margin-top: -25px; margin-bottom: 15px;">
                <?= esc($validation->getError('password')) ?>
            </div>
        <?php endif; ?>

        <!-- Submit Button -->
        <div class="row">
            <div class="col-sm-12">
                <div class="input-group mb-0">
                    <input class="btn btn-primary btn-lg btn-block" type="submit" value="Register">
                </div>
            </div>
        </div>
    </form>
</div>

<!-- JavaScript -->
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

    function validatePassword() {
        const password = document.getElementById('password').value;
        const validationMessage = document.getElementById('password-validation');
        
        const requirements = [
            { regex: /.{8,}/, message: "At least 8 characters" },
            { regex: /[A-Z]/, message: "At least one uppercase letter" },
            { regex: /[a-z]/, message: "At least one lowercase letter" },
            { regex: /[0-9]/, message: "At least one digit" },
            { regex: /[@$!%*?&]/, message: "At least one special character (@, $, !, %, *, ?, &)" },
        ];

        const failedRequirements = requirements.filter(req => !req.regex.test(password));
        
        if (failedRequirements.length > 0) {
            validationMessage.innerHTML = failedRequirements.map(req => req.message).join("<br>");
        } else {
            validationMessage.innerHTML = "<span class='text-success'>Password looks good!</span>";
        }
    }
</script>

<?= $this->endSection(); ?>

<?= $this->extend('frontend/layout/pages-layout.php') ?>

<?= $this->section('content') ?>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<div class="row">
    <div class="col-lg-8 mb-5">
        <h1 style="animation: fadeInDown 1s;">Contact Us</h1>
        <p style="animation: fadeIn 1.5s;">Have any questions or concerns? Feel free to reach out to us using the form below or via the contact information provided.</p>

        <div class="mt-5" style="animation: fadeIn 2s;">
            <p class="h3 mb-3 font-weight-normal">
                <a class="text-dark" href="mailto:<?= esc($settings['blog_email'] ?? 'info@echospace.com') ?>" style="animation: slideInLeft 1s;">
                    <?= esc($settings['blog_email'] ?? 'info@echospace.com') ?>
                </a>
            </p>
            <p class="mb-3">
                <a class="text-dark" href="tel:<?= esc($settings['blog_phone'] ?? '+0000000000') ?>" style="animation: slideInRight 1s;">
                    <?= esc($settings['blog_phone'] ?? '+0000000000') ?>
                </a>
            </p>
            <p class="mb-2" style="animation: fadeInUp 1s;">
                <?= esc($settings['blog_address'] ?? 'Pimpiri, Pune, Maharashtra, India') ?>
            </p>
            <p class="mb-3" style="font-size: 18px; font-weight: bold;">
                You can connect here with us!
            </p>

            <div class="social-media-icons" style="display: flex; justify-content: space-around; margin-top: 20px;">
                <a href="<?= esc($settings['facebook_url'] ?? '#') ?>" target="_blank" class="text-dark">
                    <i class="fab fa-facebook-f" style="font-size: 30px;"></i>
                </a>
                <a href="<?= esc($settings['twitter_url'] ?? '#') ?>" target="_blank" class="text-dark">
                    <i class="fab fa-twitter" style="font-size: 30px;"></i>
                </a>
                <a href="<?= esc($settings['instagram_url'] ?? '#') ?>" target="_blank" class="text-dark">
                    <i class="fab fa-instagram" style="font-size: 30px;"></i>
                </a>
                <a href="<?= esc($settings['linkedin_url'] ?? '#') ?>" target="_blank" class="text-dark">
                    <i class="fab fa-linkedin-in" style="font-size: 30px;"></i>
                </a>
                <a href="<?= esc($settings['github_url'] ?? '#') ?>" target="_blank" class="text-dark">
                    <i class="fab fa-github" style="font-size: 30px;"></i>
                </a>
                <a href="<?= esc($settings['youtube_url'] ?? '#') ?>" target="_blank" class="text-dark">
                    <i class="fab fa-youtube" style="font-size: 30px;"></i>
                </a>
            </div>

        </div>

        <!-- Display success or error messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success" style="animation: bounceIn 1s;">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" style="animation: shake 0.5s;">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= route_to('contact-us-send') ?>" method="post" style="animation: fadeIn 2s;">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="name" style="animation: fadeInLeft 1s;">Your Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= old('name') ?>" required>
            </div>

            <div class="form-group">
                <label for="email" style="animation: fadeInRight 1s;">Your Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= old('email') ?>" required>
            </div>

            <div class="form-group">
                <label for="message" style="animation: fadeInUp 1s;">Your Message</label>
                <textarea name="message" id="message" class="form-control" rows="5" required><?= old('message') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary mt-3" style="animation: pulse 1.5s infinite;">Send Message</button>
        </form>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInLeft {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(20px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideInLeft {
        from {
            transform: translateX(-100%);
        }

        to {
            transform: translateX(0);
        }
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
        }

        to {
            transform: translateX(0);
        }
    }

    @keyframes bounceIn {
        from {
            opacity: 0;
            transform: scale(0.3);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        25%,
        75% {
            transform: translateX(-10px);
        }

        50% {
            transform: translateX(10px);
        }
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }
    }
</style>


<?= $this->endSection() ?>
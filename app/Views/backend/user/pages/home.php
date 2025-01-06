<?= $this->extend('backend/user/layout/pages-layout.php') ?>
<?= $this->section('content') ?>

<div class="container">
    <h1 class="mt-4">Welcome, <?= esc($user['name']) ?></h1>
    <p>Your email: <?= esc($user['email']) ?></p>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Profile</h5>
                    <p class="card-text">Manage your profile details.</p>
                    <a href="/user/profile" class="btn btn-primary">View Profile</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">My Blogs</h5>
                    <p class="card-text">View and manage your blog posts.</p>
                    <a href="/user/blogs" class="btn btn-primary">View Blogs</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Logout</h5>
                    <p class="card-text">Log out of your account.</p>
                    <a href="<?= route_to('user.logout') ?>" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection(); ?>
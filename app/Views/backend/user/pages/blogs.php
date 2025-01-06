<?= $this->extend('backend/user/layout/pages-layout.php') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>My Blogs</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('user.dashboard'); ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        My Blogs
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <a href="<?= route_to('user.all-posts'); ?>" class="btn btn-primary btn-lg btn-block">
            View All Posts
        </a>
    </div>
    <div class="col-md-6">
        <a href="<?= route_to('user.create-post'); ?>" class="btn btn-success btn-lg btn-block">
            Create New Post
        </a>
    </div>
</div>

<?= $this->endSection() ?>

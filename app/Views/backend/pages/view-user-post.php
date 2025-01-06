<?= $this->extend('backend/layout/pages-layout.php') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12">
            <div class="title">
                <h4>View User Post</h4>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.review-user-posts') ?>">Review User Posts</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">View Post</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="post-header">
        <h3 style="margin: 0; font-size: 24px;"><?= esc($post->title) ?></h3>
            <p><strong>Author:</strong> <?= esc($post->author_name) ?></p>
            <p><strong>Subcategory:</strong> <?= esc($post->subcategory_name) ?></p>
            <p><strong>Status:</strong>
                <?= $post->status == 1 
                    ? '<span class="badge badge-success">Approved</span>' 
                    : ($post->status == 2 
                        ? '<span class="badge badge-danger">Rejected</span>' 
                        : '<span class="badge badge-warning">Pending</span>') ?>
            </p>
        </div>

        <!-- Display Featured Image -->
        <div class="post-image mb-4">
            <?php if ($post->featured_image): ?>
                <img src="<?= base_url('images/posts/' . esc($post->featured_image)) ?>" 
                     alt="Featured Image" 
                     class="img-thumbnail" 
                     style="max-width: 100%; max-height: 400px; object-fit: cover;">
            <?php else : ?>
                <p class="text-muted">No featured image available.</p>
            <?php endif; ?>
        </div>

        <div class="post-content">
            <h5>Content:</h5>
            <div><?= $post->content ?></div> <!-- Render HTML content without escaping -->
        </div>

        <div class="post-meta">
            <h5>Meta Information:</h5>
            <p><strong>Keywords:</strong> <?= esc($post->meta_keywords ?? 'N/A') ?></p>
            <p><strong>Description:</strong> <?= esc($post->meta_description ?? 'N/A') ?></p>
        </div>

        <div class="post-tags">
            <h5>Tags:</h5>
            <p><?= esc($post->tags ?? 'N/A') ?></p>
        </div>

        <!-- Back Button -->
        <div style="text-align: center;">
            <a href="<?= route_to('admin.review-user-posts') ?>" 
               style="padding: 10px 20px; background-color: #007bff; color: #ffffff; text-decoration: none; border-radius: 4px;">
                Back to Posts
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

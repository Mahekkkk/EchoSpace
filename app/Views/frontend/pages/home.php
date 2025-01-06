
<?= $this->extend('frontend/layout/pages-layout.php') ?>
<?= $this->section('page_meta') ?>
<meta name="robots" content="index,follow" />
<meta name="title" content="<?= get_settings()->blog_title ?>" />
<meta name="description" content="<?= get_settings()->blog_meta_description ?>" />
<meta name="author" content="<?= get_settings()->blog_title ?>" />
<link rel="canonical" href="<?= base_url() ?>" />
<meta property="og:title" content="<?= get_settings()->blog_title ?>">
<meta property="og:type" content="website" />
<meta property="og:description" content="<?= get_settings()->blog_meta_description ?>">
<meta property="og:url" content="<?= base_url() ?>">
<meta property="og:image" content="<?= base_url() ?>/images/blog/<?= get_settings()->blog_logo ?>">
<meta name="twitter:domain" content="<?= base_url() ?>">
<meta name="twitter:card" content="summary" />
<meta name="twitter:title" property="og:title" itemprop="name" content="<?= get_settings()->blog_title ?>" />
<meta name="twitter:description" property="og:description" itemprop="description" content="<?= get_settings()->blog_meta_description ?>" />
<meta name="twitter:image" content="<?= base_url() ?>/images/blog/<?= get_settings()->blog_logo ?>">
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8 mb-5 mb-lg-0">
            <div class="row">
                <div class="col-12">
                    <h2 class="section-title mb-4">Latest Articles</h2>
                </div>
                <!-- Main Latest Post -->
                <div class="col-12 mb-4">
                    <?php $latestPost = get_home_main_latest_post(); ?>
                    <?php if ($latestPost): ?>
                        <article class="card article-card">
                            <a href="<?= route_to('read-post', $latestPost->slug) ?>">
                                <div class="card-image">
                                    <div class="post-info d-flex justify-content-between">
                                        <span class="text-uppercase"><?= date_formatter($latestPost->created_at); ?></span>
                                        <span class="text-uppercase"><?= get_reading_time($latestPost->content); ?></span>
                                    </div>
                                    <img loading="lazy" decoding="async" src="/images/posts/<?= $latestPost->featured_image ?>" alt="Post Thumbnail" class="w-100">
                                    
                                </div>
                            </a>
                            <div class="card-body px-0">
                                <h2 class="h1">
                                    <a class="post-title" href="<?= route_to('read-post', $latestPost->slug) ?>">
                                        <?= $latestPost->title ?>
                                    </a>
                                </h2>
                                <p class="card-text"><?= limit_words($latestPost->content, 35); ?></p>
                                <a class="read-more-btn btn btn-primary" href="<?= route_to('read-post', $latestPost->slug) ?>">Read Full Article</a>
                            </div>
                        </article>
                    <?php else: ?>
                        <p>No latest post available.</p>
                    <?php endif; ?>
                </div>
                <!-- Other Latest Posts -->
                <?php if (count(get_6_home_latest_posts()) > 0): ?>
                    <?php foreach (get_6_home_latest_posts() as $post): ?>
                        <div class="col-md-6 mb-4">
                            <article class="card article-card-sm h-100">
                                <a href="<?= route_to('read-post', $post->slug) ?>">
                                    <div class="card-image">
                                        <div class="post-info d-flex justify-content-between">
                                            <span class="text-uppercase"><?= date_formatter($post->created_at); ?></span>
                                            <span class="text-uppercase"><?= get_reading_time($post->content); ?></span>
                                        </div>
                                        <img loading="lazy" decoding="async" src="/images/posts/<?= $post->featured_image ?>" alt="Post Thumbnail" class="w-100">
                                    </div>
                                </a>
                                <div class="card-body px-0">
                                    <h2>
                                        <a class="post-title" href="<?= route_to('read-post', $post->slug) ?>"><?= $post->title ?></a>
                                    </h2>
                                    <p class="card-text"><?= limit_words($post->content, 20); ?></p>
                                    <a class="read-more-btn btn btn-outline-secondary" href="<?= route_to('read-post', $post->slug) ?>">Read Full Article</a>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="widget-blocks">
                <!-- Random Posts Widget -->
                <div class="widget mb-4">
                    <h2 class="section-title mb-3">Random Posts</h2>
                    <div class="widget-body">
                        <?php if (count(get_sidebar_random_posts()) > 0): ?>
                            <?php foreach (get_sidebar_random_posts() as $post): ?>
                                <a class="d-flex align-items-center mb-3 text-decoration-none" href="<?= route_to('read-post', $post->slug) ?>">
                                    <div class="flex-shrink-0">
                                        <img
                                            loading="lazy"
                                            decoding="async"
                                            src="/images/posts/<?= $post->featured_image ?>"
                                            alt="Post Thumbnail"
                                            class="rounded"
                                            style="width: 80px; height: 80px; object-fit: cover; border: 1px solid #ddd;">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h3 class="h3 mb-1 text-dark"><?= $post->title ?></h3>
                                        <p class="mb-0 small text-muted fw-light"><?= limit_words($post->content, 7); ?></p>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">No random posts available at the moment.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Categories Widget -->
                <div class="widget mb-4">
                    <h2 class="section-title mb-3"></h2>
                    <?php include 'partials/sidebar-categories.php'; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .widget a:hover h3 {
        color: #007bff;
        /* Highlight title on hover */
        text-decoration: underline;
    }
    .widget img {
        transition: transform 0.2s ease-in-out;
    }
    .widget a:hover img {
        transform: scale(1.05);
        /* Slight zoom on hover */
    }
</style>
<?= $this->endSection() ?>

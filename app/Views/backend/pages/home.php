<?= $this->extend('backend/layout/pages-layout.php') ?>

<?= $this->section('content') ?>
<div style="display: flex; flex-wrap: wrap; margin: -15px;">
    <div style="flex: 0 0 100%; padding: 15px;">
        <h2 style="font-size: 2rem; font-weight: bold;">Latest Articles</h2>
    </div>
    <div style="flex: 0 0 66.6667%; margin-bottom: 30px; padding: 15px;">
        <div style="display: flex; flex-wrap: wrap;">
            <!-- Main Latest Post -->
            <div style="flex: 0 0 100%; margin-bottom: 20px;">
                <?php $latestPost = get_home_main_latest_post(); ?>
                <?php if ($latestPost): ?>
                    <article style="border: 1px solid #ddd; border-radius: 5px; overflow: hidden;">
                        <a href="<?= route_to('backend/read-post', $latestPost->slug) ?>">
                            <div style="position: relative;">
                                <div style="position: absolute; top: 10px; left: 10px; background: rgba(0,0,0,0.6); color: white; padding: 5px; font-size: 0.8rem;">
                                    <span style="display: block;"><?= date_formatter($latestPost->created_at); ?></span>
                                    <span style="display: block;"><?= get_reading_time($latestPost->content); ?></span>
                                </div>
                                <img
                                    loading="lazy"
                                    decoding="async"
                                    src="/images/posts/<?= $latestPost->featured_image ?>"
                                    alt="Post Thumbnail"
                                    style="width: 100%; display: block;" />
                            </div>
                        </a>
                        <div style="padding: 15px;">
                            <h2 style="font-size: 1.5rem; font-weight: bold;">
                                <a href="<?= route_to('backend/read-post', $latestPost->slug) ?>" style="text-decoration: none; color: #333;">
                                    <?= esc($latestPost->title) ?>
                                </a>
                            </h2>
                            <p style="color: #666;"><?= limit_words($latestPost->content, 35); ?> </p>
                            <div>
                                <a href="<?= route_to('backend/read-post', $latestPost->slug) ?>" style="color: #007BFF; text-decoration: none;">Read Full Article</a>
                            </div>
                        </div>
                    </article>
                <?php else: ?>
                    <p>No latest post available.</p>
                <?php endif; ?>
            </div>
            <div style="flex: 0 0 100%; margin-bottom: 20px;">
                <!-- Latest Post PHP Logic -->
            </div>
            <!-- Other Posts PHP Logic -->
        </div>
    </div>

    <!-- Sidebar Widgets -->
    <div style="flex: 0 0 33.3333%; padding: 15px;">
        <div style="display: flex; flex-direction: column; height: 100%; gap: 20px;">
            <!-- Random Post Section -->
            <div style="flex-shrink: 1;">
                <h2 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 15px;">Random Post</h2>
                <div>
                    <?php if (count(get_sidebar_random_posts()) > 0): ?>
                        <?php foreach (get_sidebar_random_posts() as $post): ?>
                            <a href="<?= route_to('backend/read-post', $post->slug) ?>" style="display: flex; align-items: center; text-decoration: none; color: inherit; margin-bottom: 15px;">
                                <img loading="lazy" decoding="async" src="<?= '/images/posts/' . $post->featured_image ?>" alt="Post Thumbnail" style="width: 80px; height: 80px; margin-right: 15px; object-fit: cover; border-radius: 5px;">
                                <div>
                                    <h3 style="margin: 0; font-size: 1rem;"><?= esc($post->title) ?></h3>
                                    <p style="margin: 5px 0 0; font-size: 0.8rem; color: #666;">
                                        <?= limit_words($post->content, 6); ?>
                                    </p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Categories Section -->
            <div style="flex-shrink: 1;">
                <?= view('backend/pages/partials/sidebar-categories') ?>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>

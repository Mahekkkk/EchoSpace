
<?= $this->extend('frontend/layout/pages-layout.php') ?>
<?= $this->section('page_meta') ?>
<meta name="description" content="<?= esc($post->meta_description) ?>" />
<meta name="keywords" content="<?= esc($post->meta_keywords) ?>" />
<link rel="canonical" href="<?= current_url() ?>" />
<!-- Schema.org meta -->
<meta itemprop="name" content="<?= esc($post->title) ?>" />
<meta itemprop="description" content="<?= esc($post->meta_description) ?>" />
<meta itemprop="image" content="<?= base_url('images/posts/' . esc($post->featured_image)) ?>" />
<!-- Open Graph meta -->
<meta property="og:type" content="website" />
<meta property="og:title" content="<?= esc($post->title) ?>" />
<meta property="og:description" content="<?= esc($post->meta_description) ?>" />
<meta property="og:image" content="<?= base_url('images/posts/' . esc($post->featured_image)) ?>" />
<meta property="og:url" content="<?= current_url() ?>" />
<!-- Twitter meta -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:domain" content="<?= base_url() ?>" />
<meta name="twitter:title" content="<?= esc($post->title) ?>" />
<meta name="twitter:description" content="<?= esc($post->meta_description) ?>" />
<meta name="twitter:image" content="<?= base_url('images/posts/' . esc($post->featured_image)) ?>" />

<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-8 mb-5 mb-lg-0">
        <article class="article">
            <h1 class="mb-4"><?= esc($post->title) ?></h1>
            <div class="post-meta mb-4">
                <span><?= date_formatter($post->created_at) ?></span> |
                <span>Category: <a href="<?= route_to('category-posts', $category->slug) ?>"><?= esc($category->name) ?></a></span>
            </div>
            <img loading="lazy" decoding="async" src="/images/posts/<?= esc($post->featured_image) ?>" alt="<?= esc($post->title) ?>" class="img-fluid mb-4">
            <div class="content">
                <?= $post->content ?>
            </div>
        </article>
        <div class="prev-next-posts mt-3 mb-3">
            <!-- Previous and Next Posts Section -->
            <div class="row justify-content-between p-4">
                <!-- Previous Post -->
                <div class="col-md-6 mb-2">
                    <div style="background: linear-gradient(to right, #f0f8ff, #e6f7ff); padding: 10px; border-radius: 8px; transition: background-color 0.3s ease, transform 0.3s ease;">
                        <h4 style="margin-bottom: 5px; font-size: 1.2rem; color: #6c757d; font-weight: bold; transition: color 0.3s ease;">&laquo; Previous</h4>
                        <a href="<?= isset($previousPost) ? route_to('read-post', $previousPost->slug) : '#' ?>"
                            style="display: inline-block; text-decoration: none; color: #007bff; font-weight: bold; position: relative; transition: color 0.3s ease;">
                            <?= isset($previousPost) ? esc($previousPost->title) : 'No Previous Post' ?>
                        </a>
                    </div>
                </div>
                <!-- Next Post -->
                <div class="col-md-6 mb-2 text-end">
                    <div style="background: linear-gradient(to right, #f0f8ff, #e6f7ff); padding: 10px; border-radius: 8px; transition: background-color 0.3s ease, transform 0.3s ease;">
                        <h4 style="margin-bottom: 5px; font-size: 1.2rem; color: #6c757d; font-weight: bold; transition: color 0.3s ease;">Next &raquo;</h4>
                        <a href="<?= isset($nextPost) ? route_to('read-post', $nextPost->slug) : '#' ?>"
                            style="display: inline-block; text-decoration: none; color: #007bff; font-weight: bold; position: relative; transition: color 0.3s ease;">
                            <?= isset($nextPost) ? esc($nextPost->title) : 'No Next Post' ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php if (!empty($relatedPosts)): ?>
            <div class="related-posts mt-5">
                <h3>Related Posts</h3>
                <div class="row">
                    <?php foreach ($relatedPosts as $related): ?>
                        <div class="col-md-4">
                            <article class="card article-card h-100">
                                <a href="<?= route_to('read-post', $related->slug) ?>">
                                    <div class="card-image">
                                        <img loading="lazy" decoding="async" src="/images/posts/<?= esc($related->featured_image) ?>" alt="<?= esc($related->title) ?>" class="w-100">
                                    </div>
                                </a>
                                <div class="card-body">
                                    <h2><a href="<?= route_to('read-post', $related->slug) ?>"><?= esc($related->title) ?></a></h2>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        <!-- Disqus Comments Section -->
        <div id="disqus_thread"></div>
        <script>
            var disqus_config = function () {
                this.page.url = "<?= current_url() ?>";  // Use the current URL dynamically
                this.page.identifier = "post-<?= $post->id ?>"; // Unique identifier for each post
            };
            (function() {
                var d = document, s = d.createElement('script');
                s.src = 'https://echospace-co.disqus.com/embed.js'; // Replace 'echospace-co' with your Disqus shortname
                s.setAttribute('data-timestamp', +new Date());
                (d.head || d.body).appendChild(s);
            })();
        </script>
        <noscript>
            Please enable JavaScript to view the 
            <a href="https://disqus.com/?ref_noscript">.</a>
        </noscript>
    </div>
    <div class="col-lg-4">
        <div class="widget-blocks">
            <?php include('partials\sidebar-latest-posts.php') ?>
            <?php include('partials\sidebar-categories.php') ?>
            <?php include('partials\sidebar-tags.php') ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('stylesheets') ?>
<link rel="stylesheet" href="/extra-assets/share_buttons/jquery.floating-social-share.min.css">
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="/extra-assets/share_buttons/jquery.floating-social-share.min.js"></script>
<script>
    $('body').floatingSocialShare({
        buttons:["facebook","twitter","linkedin","telegram","pinterest","whatsapp","reddit"],
        test:"share with:  ",
        url:"<?= current_url() ?>"
    });
</script>
<?= $this->endSection() ?>

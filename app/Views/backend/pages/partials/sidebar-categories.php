<div class="widget">
    <h2 class="section-title mb-3">Categories</h2>
    <div class="widget-body">
        <?php
        $categories = get_sidebar_categories(); // Fetch categories using the function
        ?>

        <ul class="widget-list">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <?php
                    // Fetch the post count for the current category
                    $postCount = posts_by_category_id($category->id);
                    ?>
                    <li>
                        <a href="<?= route_to('category-posts', $category->slug) ?>">
                            <?= htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8') ?>
                            <span class="ml-auto">(<?= $postCount ?>)</span>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>No categories available.</li>
            <?php endif; ?>
        </ul>

    </div>
</div>
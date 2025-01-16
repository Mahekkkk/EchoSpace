<header class="navigation">
  <div class="container">
    <nav class="navbar navbar-expand-lg navbar-light px-0">
      <a class="navbar-brand order-1 py-0" href="/">
        <img
          loading="preload"
          decoding="async"
          class="img-fluid"
          src="/images/blog/<?= get_settings()->blog_logo ?>"
          alt="<?= get_settings()->blog_title ?>"
          style="max-width: 170px;">
      </a>
      <div class="navbar-actions order-3 ml-0 ml-md-4">
        <button
          aria-label="navbar toggler"
          class="navbar-toggler border-0"
          type="button"
          data-toggle="collapse"
          data-target="#navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>


      <form action="<?= route_to('search-posts') ?>" method="get" class="search order-lg-3 order-md-2 order-3 ml-auto">
        <input
          id="search-query"
          name="query"
          type="search"
          class="form-control"
          placeholder="Search..."
          autocomplete="off"
          value="<?= esc($query ?? '') ?>">
        <button type="submit" class="btn btn-primary">Search</button>
      </form>

      <?php if (!empty($error)): ?>
        <p class="text-danger"><?= esc($error) ?></p>
      <?php endif; ?>

      <div class="collapse navbar-collapse text-center order-lg-2 order-4" id="navigation">
        <ul class="navbar-nav mx-auto mt-3 mt-lg-0">
          <!-- <li class="nav-item"> 
            <a class="nav-link" href="/">Home</a>
          </li> -->

          <!-- Parent Categories with Subcategories -->
          <?php foreach (get_parent_categories() as $parent_category): ?>
            <li class="nav-item dropdown">
              <a
                class="nav-link dropdown-toggle"
                href="#"
                role="button"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
                <?= esc($parent_category->name) ?>
              </a>
              <div class="dropdown-menu">
                <?php foreach (get_subcategories_by_parent_category_id($parent_category->id) as $subcategory): ?>
                  <a class="dropdown-item" href="<?= route_to('category-posts', $subcategory->slug) ?>"><?= esc($subcategory->name) ?></a>
                <?php endforeach; ?>
              </div>
            </li>
          <?php endforeach; ?>

          <!-- Independent Subcategories -->
          <?php foreach (get_dependent_sub_categories() as $sub_category): ?>
            <li class="nav-item">
              <a class="nav-link" href="<?= route_to('category-posts', $sub_category->slug) ?>"><?= esc($sub_category->name) ?></a>
            </li>
          <?php endforeach; ?>

          <li class="nav-item">
            <a class="nav-link" href="<?= route_to('contact-us') ?>">Contact</a>
          </li>
        
        </ul>
        <div class="nav-item" style="background-color: green; padding: 1px; border-radius: 100px;">
            <a class="nav-link" href="<?= route_to('user.login.form') ?>" style="color: white; text-decoration: none;">Login</a>
          </div>
      </div>
    </nav>
  </div>

</header>
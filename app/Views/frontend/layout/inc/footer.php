<footer class="bg-dark mt-5">
  <div class="container section">
    <div class="row">
      <div class="col-lg-10 mx-auto text-center">
        <a class="d-inline-block mb-4 pb-2" href="/">
          <img loading="prelaod" decoding="async" class="img-fluid" src="/images/blog/<?= get_settings()->blog_logo ?>" alt="<?= get_settings()->blog_title ?>" style="max-width: 170px;">
        </a>
        <ul class="p-0 d-flex navbar-footer mb-0 list-unstyled">
          <li class="nav-item my-0"><a class="nav-link" href="<?= route_to('about') ?>">About</a></li>
          <li class="nav-item my-0"><a class="nav-link" href="<?= route_to('privacy-policy') ?>">Privacy Policy</a></li>
          <li class="nav-item my-0"><a class="nav-link" href="<?= route_to('terms-conditions') ?>">Terms & Conditions</a></li>
          <li class="nav-item my-0"><a class="nav-link" href="<?= route_to('timepass-game') ?>">TimePass Game</a></li>
        </ul>

      </div>
    </div>
  </div>
  <div class="copyright bg-dark content">
    &copy; Copyright <script>
      document.write(new Date().getFullYear());
    </script> - <?= esc(get_settings()->blog_title) ?>. All rights reserved.

  </div>
</footer>
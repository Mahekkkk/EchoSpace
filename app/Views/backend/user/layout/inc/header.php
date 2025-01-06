<header class="header clearfix">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div class="brand-logo">
            <a href="<?= route_to('user.dashboard') ?>">
                <img src="/images/blog/<?= get_settings()->blog_logo ?>" alt="EchoSpace Logo" />
            </a>
        </div>
        <div class="header-right">
            <div class="user-info-dropdown">
                <div class="dropdown">
                    <a class="dropdown-toggle no-arrow" href="#" data-toggle="dropdown">
                        <span class="user-icon">
                            <?php $userPicture = get_user() ? get_user()->picture : null; ?>
                            <img src="<?= $userPicture == null ? '/images/users/default_avatar1.gif' : '/images/users/' . $userPicture ?>" alt="Profile Picture" class="avatar-photo ci-avatar-photo">
                        </span>
                        <span class="user-name"><?= session()->get('site_user')['name'] ?? 'Guest' ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="/user/profile">
                            <i class="dw dw-user1"></i> Profile
                        </a>
                        <a class="dropdown-item" href="<?= route_to('user.logout') ?>">
                            <i class="dw dw-logout"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
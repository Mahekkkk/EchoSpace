<div class="left-side-bar">
    <div class="brand-logo">
        <a href="<?= route_to('user.dashboard') ?>">
            <img src="/images/blog/<?= get_settings()->blog_logo ?>" alt="EchoSpace Logo" />
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
            <i class="icon-copy ion-close-round"></i>
        </div>
    </div>
    <div class="menu-block customscroll">
        <div class="sidebar-menu">
            <ul id="accordion-menu">
                <li>
                    <a href="<?= route_to('user.dashboard') ?>" class="dropdown-toggle no-arrow">
                        <i class="micon dw dw-house-1"></i><span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="/user/blogs" class="dropdown-toggle no-arrow">
                        <i class="micon dw dw-edit2"></i><span>My Blogs</span>
                    </a>
                </li>
                <li>
                    <a href="/user/profile" class="dropdown-toggle no-arrow">
                        <i class="micon dw dw-user1"></i><span>Profile</span>
                    </a>
                </li>
                <li>
                    <a href="<?= route_to('user.logout') ?>" class="dropdown-toggle no-arrow">
                        <i class="micon dw dw-logout"></i><span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="left-side-bar">
	<div class="brand-logo">
		<a href="/">
			<?php $settings = get_settings(); ?>
			<img src="/images/blog/<?= esc($settings->blog_logo ?? 'default-logo.png') ?>" alt="Dark Logo" class="dark-logo" />
			<img src="/images/blog/<?= esc($settings->blog_logo ?? 'default-logo.png') ?>" alt="Light Logo" class="light-logo" />
		</a>
		<div class="close-sidebar" data-toggle="left-sidebar-close">
			<i class="ion-close-round"></i>
		</div>
	</div>
	<div class="menu-block customscroll">
		<div class="sidebar-menu">
			<ul id="accordion-menu">
				<!-- Home -->
				<li>
					<a href="<?= route_to('admin.home') ?>" class="dropdown-toggle no-arrow <?= current_route_name() == 'admin.home' ? 'active' : '' ?>">
						<span class="micon dw dw-home"></span>
						<span class="mtext">Home</span>
					</a>
				</li>

				<!-- Categories -->
				<li>
					<a href="<?= route_to('categories') ?>" class="dropdown-toggle no-arrow <?= current_route_name() == 'categories' ? 'active' : '' ?>">
						<span class="micon dw dw-list"></span>
						<span class="mtext">Categories</span>
					</a>
				</li>

				<!-- Posts -->
				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle <?= current_route_name() == 'all-posts' || current_route_name() == 'all-post' ? 'active' : '' ?>">
						<span class="micon dw dw-newspaper"></span>
						<span class="mtext">Posts</span>
					</a>
					<ul class="submenu">
						<li><a href="<?= route_to('all-posts') ?>" class="<?= current_route_name() == 'all-posts' ? 'active' : '' ?>">All Posts</a></li>
						<li><a href="<?= route_to('new-post') ?>" class="<?= current_route_name() == 'new-post' ? 'active' : '' ?>">Add New</a></li>
					</ul>
				</li>
				<!-- Review User Posts -->
				<!-- <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle <?= current_route_name() == 'admin.review-user-posts' ? 'active' : '' ?>">
                        <span class="micon dw dw-file-38"></span>
                        <span class="mtext">User Posts</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="<?= route_to('admin.review-user-posts') ?>" class="<?= current_route_name() == 'admin.review-user-posts' ? 'active' : '' ?>">Review Posts</a></li>
                    </ul>
                </li> -->

				<li class="dropdown">
					<a href="javascript:;" class="dropdown-toggle <?= current_route_name() == 'admin.review-user-posts' || current_route_name() == 'admin.approved-user-posts' ? 'active' : '' ?>">
						<span class="micon dw dw-file-38"></span>
						<span class="mtext">User Posts</span>
					</a>
					<ul class="submenu">
						<li>
							<a href="<?= route_to('admin.review-user-posts') ?>" class="<?= current_route_name() == 'admin.review-user-posts' ? 'active' : '' ?>">
								Review Posts
							</a>
						</li>
						<li>
							<a href="<?= route_to('admin.approved-user-posts') ?>" class="<?= current_route_name() == 'admin.approved-user-posts' ? 'active' : '' ?>">
								Approved Posts
							</a>
						</li>
					</ul>
				</li>

				<li>
					<a href="<?= route_to('admin.manage.users') ?>" class="dropdown-toggle no-arrow <?= current_route_name() == 'admin.manage.users' ? 'active' : '' ?>">
						<span class="micon dw dw-user1"></span>
						<span class="mtext">Manage Site Users</span>
					</a>
				</li>


				<!-- Divider -->
				<li>
					<div class="dropdown-divider"></div>
				</li>

				<!-- Settings Section -->
				<li>
					<div class="sidebar-small-cap">Settings</div>
				</li>

				<!-- Profile -->
				<li>
					<a href="<?= route_to('admin.profile') ?>" class="dropdown-toggle no-arrow <?= current_route_name() == 'admin.profile' ? 'active' : '' ?>">
						<span class="micon dw dw-user"></span>
						<span class="mtext">Profile</span>
					</a>
				</li>

				<!-- Settings -->
				<li>
					<a href="<?= route_to('settings') ?>" class="dropdown-toggle no-arrow <?= current_route_name() == 'settings' ? 'active' : '' ?>">
						<span class="micon dw dw-settings"></span>
						<span class="mtext">Settings</span>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>

<style>
	/* Submenu Styling */
	.submenu {
		display: none;
		list-style: none;
		padding: 0;
		margin: 0;
		background-color: #f9f9f9;
		/* Light background for submenu */
		border-radius: 5px;
		overflow: hidden;
	}

	.submenu li {
		border-bottom: 1px solid #e0e0e0;
	}

	.submenu li a {
		display: block;
		padding: 10px 15px;
		color: #333333;
		/* Dark text */
		font-size: 14px;
		text-decoration: none;
		transition: background-color 0.3s ease;
	}

	.submenu li a:hover {
		background-color: #e9ecef;
		/* Slightly darker hover effect */
	}

	/* Open Submenu */
	.dropdown.open .submenu {
		display: block;
		/* Display submenu when dropdown is open */
	}
</style>

<script>
	document.addEventListener('DOMContentLoaded', function() {


		// Dropdown submenu toggle functionality
		const dropdowns = document.querySelectorAll('.dropdown');

		dropdowns.forEach(dropdown => {
			const toggle = dropdown.querySelector('.dropdown-toggle');
			const submenu = dropdown.querySelector('.submenu');

			toggle.addEventListener('click', (e) => {
				e.preventDefault();
				dropdown.classList.toggle('open'); // Toggle submenu visibility
			});
		});
	});
</script>
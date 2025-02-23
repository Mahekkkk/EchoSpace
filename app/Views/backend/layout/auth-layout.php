<!DOCTYPE html>
<html>

<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8" />
	<title><?= isset($pageTitle) ? $pageTitle : 'New Page Title'; ?></title>

	<!-- Site favicon -->
	<link
		rel="apple-touch-icon"
		sizes="180x180"
		href="/images/blog/<?= get_settings()->blog_favicon ?>" />
	<link
		rel="icon"
		type="image/png"
		sizes="32x32"
		href="/images/blog/<?= get_settings()->blog_favicon ?>" />
	<link
		rel="icon"
		type="image/png"
		sizes="16x16"
		href="/images/blog/<?= get_settings()->blog_favicon ?>" />

	<!-- Mobile Specific Metas -->
	<meta
		name="viewport"
		content="width=device-width, initial-scale=1, maximum-scale=1" />

	<!-- Google Font -->
	<link
		href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
		rel="stylesheet" />
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="/backend/vendors/styles/core.css" />
	<link
		rel="stylesheet"
		type="text/css"
		href="/backend/vendors/styles/icon-font.min.css" />
	<link rel="stylesheet" type="text/css" href="/backend/vendors/styles/style.css" />

	<?= $this->renderSection('stylesheets') ?>

</head>
<body class="login-page">
	<div class="login-header box-shadow">
		<div
			class="container-fluid d-flex justify-content-between align-items-center">
			<div class="brand-logo">
				<a href="/">
					<img src="/images/blog/<?= get_settings()->blog_logo ?>" alt="" />
				</a>
			</div>
			<div class="login-menu">

			</div>
		</div>
	</div>
	<div
		class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-6 col-lg-7">
					<img src="/backend/vendors/images/FrontLogin.png" alt="" />
				</div>
				<div class="col-md-6 col-lg-5">
					<?= $this->renderSection('content') ?>
				</div>
			</div>
		</div>
	</div>

	<!-- js -->
	<script src="/backend/vendors/scripts/core.js"></script>
	<script src="/backend/vendors/scripts/script.min.js"></script>
	<script src="/backend/vendors/scripts/process.js"></script>
	<script src="/backend/vendors/scripts/layout-settings.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="/extra-assets/ijaboCropTool/ijaboCropTool.min.js"></script>

	<?= $this->renderSection('scripts'); ?>
</body>

</html>
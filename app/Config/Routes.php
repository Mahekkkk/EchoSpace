<?php

use CodeIgniter\Router\RouteCollection;

$routes->get('/', 'BlogController::index');
$routes->get('posts/(:any)', 'BlogController::readPost/$1', ['as' => 'read-post']);
$routes->get('category/(:any)', 'BlogController::categoryPosts/$1', ['as' => 'category-posts']);
$routes->get('tag/(:any)', 'BlogController::tagPosts/$1', ['as' => 'tag-posts']);
$routes->get('search', 'BlogController::searchPosts', ['as' => 'search-posts']);
$routes->get('contact-us', 'BlogController::contactUs', ['as' => 'contact-us']);
$routes->post('contact-us', 'BlogController::contactUsSend', ['as' => 'contact-us-send']);
$routes->get('/about', 'BlogController::about', ['as' => 'about']);
$routes->get('/privacy-policy', 'BlogController::privacyPolicy', ['as' => 'privacy-policy']);
$routes->get('/terms-conditions', 'BlogController::termsConditions', ['as' => 'terms-conditions']);
$routes->get('/timepass-game', 'BlogController::timePassGame', ['as' => 'timepass-game']);
$routes->post('/user/update-profile-picture', 'AdminController::updateProfilePicture', ['as' => 'update-profile-picture']);

// Add the test email route
$routes->get('test-email', 'TestEmailController::sendTestEmail');

// Admin routes
$routes->group('admin', static function ($routes) {
    // Routes for authenticated users
    $routes->group('', ['filter' => 'cifilter:auth'], static function ($routes) {
        $routes->get('home', 'AdminController::index', ['as' => 'admin.home']);
        $routes->get('logout', 'AdminController::logoutHandler', ['as' => 'admin.logout']);
        $routes->get('profile', 'AdminController::profile', ['as' => 'admin.profile']);
        $routes->post('update-personal-details', 'AdminController::updatePersonalDetails', ['as' => 'update-personal-details']);
        $routes->post('update-profile-picture', 'AdminController::updateProfilePicture', ['as' => 'update-profile-picture']);
        $routes->post('remove-profile-picture', 'AdminController::removeProfilePicture', ['as' => 'remove-profile-picture']);
        $routes->post('change-password', 'AdminController::changePassword', ['as' => 'change-password']);
        $routes->get('settings', 'AdminController::settings', ['as' => 'settings']);
        $routes->post('update-general-settings', 'AdminController::updateGeneralSettings', ['as' => 'update-general-settings']);
        $routes->post('update-blog-logo', 'AdminController::updateBlogLogo', ['as' => 'update-blog-logo']);
        $routes->post('update-blog-favicon', 'AdminController::updateBlogFavicon', ['as' => 'update-blog-favicon']);
        $routes->post('update-social-media', 'AdminController::updateSocialMedia', ['as' => 'update-social-media']);
        $routes->get('categories', 'AdminController::categories', ['as' => 'categories']);
        $routes->post('add-category', 'AdminController::addCategory', ['as' => 'add-category']);
        $routes->get('get-categories', 'AdminController::getCategories', ['as' => 'get-categories']);
        $routes->get('get-category', 'AdminController::getCategory', ['as' => 'get-category']);
        $routes->post('update-category', 'AdminController::updateCategory', ['as' => 'update-category']);
        $routes->post('delete-category', 'AdminController::deleteCategory', ['as' => 'delete-category']);
        $routes->post('reorder-categories', 'AdminController::reorderCategories', ['as' => 'reorder-categories']);
        $routes->get('get-parent-categories', 'AdminController::getParentCategories', ['as' => 'get-parent-categories']);
        $routes->post('add-subcategory', 'AdminController::addSubCategory', ['as' => 'add-subcategory']);
        $routes->get('get-subcategories', 'AdminController::getSubCategories', ['as' => 'get-subcategories']);
        $routes->get('get-subcategory', 'AdminController::getSubCategory', ['as' => 'get-subcategory']);
        $routes->post('update-subcategory', 'AdminController::updateSubCategory', ['as' => 'update-subcategory']);
        $routes->post('delete-subcategory', 'AdminController::deleteSubCategory', ['as' => 'delete-subcategory']);
        $routes->get('manage-users', 'AdminController::manageUsers', ['as' => 'admin.manage.users']);
        $routes->get('edit-user/(:num)', 'AdminController::editUser/$1', ['as' => 'admin.edit.user']);
        $routes->get('delete-user/(:num)', 'AdminController::deleteUser/$1', ['as' => 'admin.delete.user']);

        // Routes for Post
        $routes->group('posts', static function ($routes) {
            $routes->get('new-post', 'AdminController::addPost', ['as' => 'new-post']);
            $routes->post('create-post', 'AdminController::createPost', ['as' => 'create-post']);
            $routes->get('/', 'AdminController::allPosts', ['as' => 'all-posts']);
            $routes->get('get-posts', 'AdminController::getPosts', ['as' => 'get-posts']);
            $routes->get('edit-post/(:num)', 'AdminController::editPost/$1', ['as' => 'edit-post']);
            $routes->post('update-post/(:num)', 'AdminController::updatePost/$1', ['as' => 'update-post']);
            $routes->post('delete-post', 'AdminController::deletePost', ['as' => 'delete-post']);
        });

        $routes->group('user-posts', static function ($routes) {
            $routes->get('/', 'AdminController::reviewUserPosts', ['as' => 'admin.review-user-posts']);
            $routes->post('approve/(:num)', 'AdminController::approveUserPost/$1', ['as' => 'admin.user-posts.approve']);
            $routes->post('delete-user-post', 'AdminController::deleteUserPost', ['as' => 'admin.delete-user-post']);
            $routes->post('reject', 'AdminController::rejectUserPost', ['as' => 'admin.reject-user-post']);
            $routes->get('view/(:num)', 'AdminController::viewUserPost/$1', ['as' => 'admin.user-posts.view']);
            $routes->post('approve', 'AdminController::approveUserPost', ['as' => 'admin.approve-user-post']);
            $routes->get('approved', 'AdminController::approvedUserPosts', ['as' => 'admin.approved-user-posts']);
            $routes->post('delete-approved-post', 'AdminController::deleteApprovedUserPost', ['as' => 'admin.delete-approved-user-post']);
        });
    });

    // Routes for guest users
    $routes->group('', ['filter' => 'cifilter:guest'], static function ($routes) {
        $routes->get('login', 'AuthController::loginForm', ['as' => 'admin.login.form']);
        $routes->post('login', 'AuthController::loginHandler', ['as' => 'admin.login.handler']);
        $routes->get('forgot-password', 'AuthController::forgotForm', ['as' => 'admin.forgot.form']);
        $routes->post('send-password-reset-link', 'AuthController::sendPasswordResetLink', ['as' => 'send_password_reset_link']);
        $routes->get('password/reset/(:any)', 'AuthController::resetPassword/$1', ['as' => 'admin.reset-password']);
        $routes->post('reset-password-handler/(:any)', 'AuthController::resetPasswordHandler/$1', ['as' => 'reset-password-handler']);
    });
});

// Fallback route for undefined paths
$routes->set404Override('Errors::show404');

$routes->group('user', static function ($routes) {
    $routes->get('register', 'UserController::registerForm', ['as' => 'user.register.form']);
    $routes->post('register', 'UserController::registerHandler', ['as' => 'user.register.handler']);
    $routes->get('login', 'UserController::loginForm', ['as' => 'user.login.form']);
    $routes->post('login', 'UserController::loginHandler', ['as' => 'user.login.handler']);
    $routes->get('logout', 'UserController::logoutHandler', ['as' => 'user.logout']);
    $routes->get('dashboard', 'UserController::dashboard', ['as' => 'user.dashboard']);
    $routes->get('profile', 'UserController::profile', ['as' => 'user.profile']);
    $routes->post('update-personal-details', 'UserController::updatePersonalDetails', ['as' => 'user.update-personal-details']);
    $routes->post('update-profile-picture', 'UserController::updateProfilePicture', ['as' => 'user.update-profile-picture']);
    $routes->post('remove-profile-picture', 'UserController::removeProfilePicture', ['as' => 'user.remove-profile-picture']);
    $routes->post('change-password', 'UserController::changePassword', ['as' => 'user.change-password']);
    $routes->get('blogs', 'UserController::blogs', ['as' => 'user.blogs']);
    $routes->get('all-posts', 'UserController::allPosts', ['as' => 'user.all-posts']);
    $routes->get('create-post', 'UserController::createPost', ['as' => 'user.create-post']);
    $routes->post('store-post', 'UserController::storePost', ['as' => 'user.store-post']);
    $routes->post('delete-post', 'UserController::deletePost', ['as' => 'user.delete-post']);
    $routes->get('forgot-password', 'AuthController::userForgotPasswordForm', ['as' => 'user.forgot_password.form']);
    $routes->post('forgot-password', 'AuthController::userSendPasswordResetLink', ['as' => 'user.forgot_password.handler']);
    $routes->get('reset-password/(:any)', 'AuthController::userResetPasswordForm/$1', ['as' => 'user.reset_password.form']);
    $routes->post('reset-password', 'AuthController::userResetPasswordHandler', ['as' => 'user.reset_password.handler']);
    $routes->get('verify-email/(:any)', 'UserController::verifyEmail/$1', ['as' => 'user.verify-email']);
});

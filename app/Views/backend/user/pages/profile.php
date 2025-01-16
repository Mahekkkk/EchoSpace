<?= $this->extend('backend/user/layout/pages-layout.php') ?>
<?= $this->section('content') ?>

<meta name="csrf-token" content="<?= csrf_token() ?>">
<meta name="csrf-hash" content="<?= csrf_hash() ?>">

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>User Profile</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('user.dashboard'); ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Profile
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row">
    <!-- Profile Picture Section -->
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
        <div class="pd-20 card-box height-100-p">
            <div class="profile-photo">
                <div class="dropdown">
                    <a href="javascript:;" class="edit-avatar dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="javascript:;" id="trigger_upload">Upload New Image</a>
                        <a class="dropdown-item text-danger" href="javascript:;" id="trigger_remove">Remove Image</a>
                    </div>
                </div>
                <input type="file" name="user_profile_file" id="user_profile_file" class="d-none">
                <?php $userPicture = get_user() ? get_user()->picture : null; ?>
                <img src="<?= $userPicture == null ? '/images/users/default_avatar1.gif' : '/images/users/' . $userPicture ?>" alt="Profile Picture" class="avatar-photo ci-avatar-photo">
            </div>
            <h5 class="text-center h5 mb-0 ci-user-name"><?= esc($user['name']) ?></h5>
            <p class="text-center text-muted font-14 ci-user-email"><?= esc($user['email']) ?></p>
        </div>
    </div>

    <!-- Profile Details Section -->
    <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
        <div class="card-box height-100-p overflow-hidden">
            <div class="profile-tab height-100-p">
                <div class="tab height-100-p">
                    <ul class="nav nav-tabs customtab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#personal_details" role="tab">Personal Details</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#change_password" role="tab">Change Password</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <!-- Personal Details Tab -->
                        <div class="tab-pane fade show active" id="personal_details" role="tabpanel">
                            <div class="pd-20">
                                <form action="<?= route_to('user.update-personal-details') ?>" method="POST" id="personal_details_form">
                                    <?= csrf_field(); ?>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" name="name" class="form-control" placeholder="Enter full name" value="<?= esc($user['name']) ?>" />
                                                <span class="text-danger error-text name_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="bio">Bio</label>
                                                <textarea name="bio" cols="30" rows="3" class="form-control" placeholder="Enter bio"><?= esc($user['bio'] ?? '') ?></textarea>
                                                <span class="text-danger error-text bio_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Change Password Tab -->
                        <div class="tab-pane fade" id="change_password" role="tabpanel">
                            <div class="pd-20">
                                <form action="<?= route_to('user.change-password') ?>" method="POST" id="change_password_form">
                                    <?= csrf_field(); ?>
                                    <div class="form-group">
                                        <label for="current_password">Current Password</label>
                                        <div class="input-group">
                                            <input type="password" name="current_password" id="current_password" class="form-control" placeholder="Enter current password">
                                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="current_password">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                        <span class="text-danger error-text current_password_error"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="new_password">New Password</label>
                                        <div class="input-group">
                                            <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Enter new password">
                                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="new_password">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                        <span class="text-danger error-text new_password_error"></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="confirm_new_password">Confirm New Password</label>
                                        <div class="input-group">
                                            <input type="password" name="confirm_new_password" id="confirm_new_password" class="form-control" placeholder="Confirm new password">
                                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="confirm_new_password">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                        <span class="text-danger error-text confirm_new_password_error"></span>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Change Password</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>

<script>
    $(document).ready(function() {
        // Personal Details Form Submission
        $('#personal_details_form').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);

            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    toastr.remove();
                    $(form).find('span.error-text').text('');
                },
                success: function(response) {
                    if ($.isEmptyObject(response.error)) {
                        if (response.status === 1) {
                            $('.ci-user-name').html(response.user_info.name);
                            toastr.success(response.msg);
                        } else {
                            toastr.error(response.msg);
                        }
                    } else {
                        $.each(response.error, function(prefix, val) {
                            $(form).find(`span.${prefix}_error`).text(val);
                        });
                    }
                },
                error: function(xhr) {
                    toastr.error('An error occurred. Please try again.');
                    console.error(xhr.responseText);
                },
            });
        });

        // Change Password Form Submission
        $('#change_password_form').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);

            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    toastr.remove();
                    $(form).find('span.error-text').text('');
                },
                success: function(response) {
                    if ($.isEmptyObject(response.error)) {
                        if (response.status === 1) {
                            $(form)[0].reset();
                            toastr.success(response.msg);
                            Swal.fire({
                                title: 'Success!',
                                text: response.msg,
                                icon: 'success',
                                confirmButtonText: 'OK',
                            });
                        } else {
                            toastr.error(response.msg);
                        }
                    } else {
                        $.each(response.error, function(prefix, val) {
                            $(form).find(`span.${prefix}_error`).text(val);
                        });
                    }
                },
                error: function(xhr) {
                    toastr.error('An error occurred. Please try again.');
                    console.error(xhr.responseText);
                },
            });
        });

        // Profile Picture Upload and Remove
        $(document).ready(function() {
            // Trigger file input when "Upload New Image" is clicked
            $(document).on('click', '#trigger_upload', function() {
                $('#user_profile_file').click();
            });

            // Initialize ijaboCropTool for uploading a new profile picture
            $('#user_profile_file').ijaboCropTool({
                preview: '.ci-avatar-photo', // The selector for the image preview element
                setRatio: 1, // Aspect ratio for cropping (1:1 for square image)
                allowedExtensions: ['jpg', 'jpeg', 'png'], // Allowed file types
                processUrl: '/user/update-profile-picture', // The URL to process the image
                withCSRF: {
                    csrfName: $('meta[name="csrf-token"]').attr('content'),
                    csrfHash: $('meta[name="csrf-hash"]').attr('content'),
                },
                onSuccess: function(message) {
                    toastr.success(message); // Show success message
                },
                onError: function(message) {
                    toastr.error(message); // Show error message
                },
                onSelect: function() {
                    // This callback is triggered when the user selects an image for cropping
                    console.log("Image selected for cropping");
                },
                onLoad: function() {
                    // This callback is triggered once the cropping tool has loaded the image
                    console.log("Crop tool loaded");
                },
            });

            // Handle Remove Image action
            $(document).on('click', '#trigger_remove', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This will remove your profile picture.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, remove it!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/user/remove-profile-picture',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                csrf_token: $('meta[name="csrf-token"]').attr('content'),
                            },
                            success: function(response) {
                                if (response.status === 1) {
                                    toastr.success(response.msg);
                                    $('.ci-avatar-photo').attr('src', '/images/users/default_avatar1.gif');
                                } else {
                                    toastr.error(response.msg);
                                }
                            },
                            error: function(xhr) {
                                toastr.error('An error occurred while removing your picture.');
                                console.error(xhr.responseText);
                            },
                        });
                    }
                });
            });
        });

        // Toggle Password Visibility
        $(document).on('click', '.toggle-password', function() {
            const targetInput = $('#' + $(this).data('target'));
            const icon = $(this).find('i');

            if (targetInput.attr('type') === 'password') {
                targetInput.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                targetInput.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });
    });
</script>

<!-- ijaboCropTool CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ijabo-crop-tool/dist/css/ijaboCropTool.min.css" />

<!-- FontAwesome for icon (used in cropping tool) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

<style>
    .input-group .btn {
        border-left: none;
    }

    .input-group input {
        border-right: none;
    }
</style>

<?= $this->endSection(); ?>

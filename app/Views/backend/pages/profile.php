<?= $this->extend('backend/layout/pages-layout.php') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Profile</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.profile'); ?>">Home</a>
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
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
        <div class="pd-20 card-box height-100-p">
            <div class="profile-photo">
                <!-- Pencil Icon with Dropdown for Options -->
                <div class="dropdown">
                    <a href="javascript:;" class="edit-avatar dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="javascript:;" id="trigger_upload">Upload New Image</a>
                        <a class="dropdown-item text-danger" href="javascript:;" id="trigger_remove">Remove Image</a>
                    </div>
                </div>

                <!-- File Input for Upload -->
                <input type="file" name="user_profile_file" id="user_profile_file" class="d-none">

                <!-- Profile Image -->
                <img src="<?= get_user()->picture == null ? '/images/users/default_avatar1.gif' : '/images/users/' . get_user()->picture ?>" alt="" class="avatar-photo ci-avatar-photo">
            </div>

            <h5 class="text-center h5 mb-0 ci-user-name"><?= get_user()->name ?></h5>
            <p class="text-center text-muted font-14 ci-user-email">
                <?= get_user()->email ?>
            </p>
        </div>
    </div>
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
                        <!-- Personal Details Tab Start -->
                        <div class="tab-pane fade show active" id="personal_details" role="tabpanel">
                            <div class="pd-20">
                                <form action="<?= route_to('update-personal-details'); ?>" method="POST" id="personal_details_form">
                                    <?= csrf_field(); ?>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" name="name" class="form-control" placeholder="Enter full name" value="<?= get_user()->name ?>" />
                                                <span class="text-danger error-text name_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="username">Username</label>
                                                <input type="text" name="username" class="form-control" placeholder="Enter Username" value="<?= get_user()->username ?>" />
                                                <span class="text-danger error-text username_error"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="bio">Bio</label>
                                            <textarea name="bio" id="bio" cols="85" rows="10" class="form-control" placeholder="Bio..."><?= get_user()->bio ?></textarea>
                                            <span class="text-danger error-text bio_error"></span>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Personal Details Tab End -->
                        <!-- Change Password Tab Start -->
                        <div class="tab-pane fade" id="change_password" role="tabpanel">
                            <div class="pd-20 profile-task-wrap">
                                <form action="<?= route_to('change-password') ?>" method="POST" id="change_password_form">
                                    <!-- CSRF Token -->
                                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="ci_csrf_data">


                                    <div class="row">
                                        <!-- Current Password -->
                                        <div class="col-md-4 mb-3">
                                            <label for="current_password">Current Password:</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" placeholder="Enter current password" name="current_password" id="current_password">
                                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="current_password">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            </div>
                                            <span class="text-danger error-text current_password_error"></span>
                                        </div>

                                        <!-- New Password -->
                                        <div class="col-md-4 mb-3">
                                            <label for="new_password">New Password:</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" placeholder="Enter new password" name="new_password" id="new_password">
                                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="new_password">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            </div>
                                            <span class="text-danger error-text new_password_error"></span>
                                        </div>

                                        <!-- Confirm New Password -->
                                        <div class="col-md-4 mb-3">
                                            <label for="confirm_new_password">Confirm New Password:</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" placeholder="Confirm new password" name="confirm_new_password" id="confirm_new_password">
                                                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="confirm_new_password">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            </div>
                                            <span class="text-danger error-text confirm_new_password_error"></span>
                                        </div>
                                    </div>



                                    <!-- Submit Button -->
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Change Password</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                        <!-- Change Password Tab End -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script>
    // Handle Personal Details Form Submission
    $('#personal_details_form').on('submit', function(e) {
        e.preventDefault();
        var form = this;
        var formdata = new FormData(form);

        $.ajax({
            url: $(form).attr('action'),
            method: $(form).attr('method'),
            data: formdata,
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function() {
                console.log("Submitting form...");
                toastr.remove();
                $(form).find('span.error-text').text('');
            },
            success: function(response) {
                console.log("Response received: ", response);
                if ($.isEmptyObject(response.error)) {
                    if (response.status == 1) {
                        $('.ci-user-name').each(function() {
                            $(this).html(response.user_info.name);
                        });
                        toastr.success(response.msg);
                        Swal.fire({
                            title: 'Success!',
                            text: response.msg,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        toastr.error(response.msg);
                    }
                } else {
                    $.each(response.error, function(prefix, val) {
                        $(form).find('span.' + prefix + '_error').text(val);
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("An error occurred: ", error);
                toastr.error('An error occurred while processing your request. Please try again.');
            }
        });
    });

    $(document).ready(function() {
        // Trigger file input when "Upload New Image" is clicked
        $(document).on('click', '#trigger_upload', function() {
            $('#user_profile_file').click(); // Open file upload dialog
        });

        // Initialize ijaboCropTool for uploading a new profile picture
        $('#user_profile_file').ijaboCropTool({
            preview: '.ci-avatar-photo', // Area to display the preview
            setRatio: 1, // Maintain 1:1 aspect ratio
            allowedExtensions: ['jpg', 'jpeg', 'png'], // Valid file types
            processUrl: '<?= route_to('update-profile-picture') ?>', // Backend endpoint for image upload
            withCSRF: ['<?= csrf_token() ?>', '<?= csrf_hash() ?>'], // Include CSRF tokens for security
            onSuccess: function(message, element, status) {
                if (status === 1) {
                    toastr.success(message); // Show success notification
                } else {
                    toastr.error(message); // Show error notification
                }
            },
            onError: function(message, element, status) {
                toastr.error(message); // Show error notification for upload failure
            }
        });

        // Handle Remove Image action
        $(document).on('click', '#trigger_remove', function(e) {
            e.preventDefault(); // Prevent default behavior

            // Confirm removal with the user before proceeding
            Swal.fire({
                title: 'Are you sure?',
                text: "This will remove your profile picture.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= route_to('remove-profile-picture'); ?>', // Backend endpoint for removing the profile picture
                        method: 'POST', // HTTP POST method
                        dataType: 'json', // Expected response format
                        data: {
                            <?= csrf_token() ?>: '<?= csrf_hash() ?>' // Include CSRF tokens
                        },
                        success: function(response) {
                            if (response.status === 1) {
                                toastr.success(response.msg); // Show success notification
                                $('.ci-avatar-photo').attr('src', '/images/users/default_avatar1.gif'); // Reset to default profile image
                            } else {
                                toastr.error(response.msg); // Show error notification
                            }
                        },
                        error: function() {
                            toastr.error('An error occurred while processing your request. Please try again.'); // Show generic error notification
                        }
                    });
                }
            });
        });
    });

    $('#change_password_form').on('submit', function(e) {
        e.preventDefault();

        // CSRF Token
        const csrfName = $('.ci_csrf_data').attr('name'); // CSRF Token name
        const csrfHash = $('.ci_csrf_data').val(); // CSRF Token value

        // Form data
        const form = this;
        const formData = new FormData(form);
        formData.append(csrfName, csrfHash); // Append CSRF token to form data

        // AJAX Request
        $.ajax({
            url: $(form).attr('action'), // Form action URL
            method: $(form).attr('method'), // Form method (POST)
            data: formData,
            processData: false, // Don't process the data
            contentType: false, // Don't set content-type header
            dataType: 'json', // Expect JSON response
            cache: false, // Disable caching
            beforeSend: function() {
                toastr.remove(); // Clear toastr notifications
                $(form).find('span.error-text').text(''); // Clear error messages
            },
            success: function(response) {
                // Update CSRF token
                $('.ci_csrf_data').val(response.token);

                if ($.isEmptyObject(response.error)) {
                    if (response.status == 1) {
                        $(form)[0].reset(); // Reset the form
                        toastr.success(response.msg); // Success notification
                    } else {
                        toastr.error(response.msg); // General error notification
                    }
                } else {
                    // Display field-specific validation errors
                    $.each(response.error, function(prefix, val) {
                        $(form).find(`span.${prefix}_error`).text(val);
                    });
                }
            },
            error: function() {
                toastr.error('An error occurred. Please try again.'); // Generic error notification
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        const togglePasswordButtons = document.querySelectorAll(".toggle-password");

        togglePasswordButtons.forEach(button => {
            button.addEventListener("click", function() {
                const targetInput = document.getElementById(this.getAttribute("data-target"));
                const icon = this.querySelector("i");

                if (targetInput.type === "password") {
                    targetInput.type = "text";
                    icon.classList.remove("fa-eye");
                    icon.classList.add("fa-eye-slash");
                } else {
                    targetInput.type = "password";
                    icon.classList.remove("fa-eye-slash");
                    icon.classList.add("fa-eye");
                }
            });
        });
    });
</script>
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
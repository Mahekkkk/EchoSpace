<?= $this->extend('backend/layout/pages-layout.php') ?>
<?= $this->section('content') ?>
<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Settings</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home'); ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Settings
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="pd-20 card-box mb-4">
    <div class="tab">
        <ul class="nav nav-tabs customtab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#general_settings" role="tab" aria-selected="true">General Settings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#logo_favicon" role="tab" aria-selected="false">Logo & Favicon</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#social_media" role="tab" aria-selected="false">Social Media</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="general_settings" role="tabpanel">
                <div class="pd-20">
                    <form action="<?= route_to('update-general-settings') ?>" method="POST" id="general_settings_form">
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="ci_csrf_data">
                        <?php $settings = get_settings(); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="blog_title">Blog Title</label>
                                    <input type="text" class="form-control" name="blog_title" placeholder="Enter blog title" value="<?= esc($settings->blog_title ?? '') ?>">
                                    <span class="text-danger error-text blog_title_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="blog_email">Blog Email</label>
                                    <input type="text" class="form-control" name="blog_email" placeholder="Enter blog email" value="<?= esc($settings->blog_email ?? '') ?>">
                                    <span class="text-danger error-text blog_email_error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="blog_phone">Blog Phone No</label>
                                    <input type="text" class="form-control" id="blog_phone" name="blog_phone"
                                        placeholder="Enter blog phone number" value="<?= esc($settings->blog_phone ?? '') ?>" maxlength="10"
                                        oninput="this.value=this.value.replace(/[^0-9]/g,''); 
                                           if(this.value.length > 10) this.value = this.value.slice(0, 10);"
                                        required>
                                    <span class="text-danger error-text blog_phone_error"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="blog_meta_keywords">Blog Meta Keywords</label>
                                    <input type="text" class="form-control" name="blog_meta_keywords" placeholder="Enter meta keywords" value="<?= esc($settings->blog_meta_keywords ?? '') ?>">
                                    <span class="text-danger error-text blog_meta_keywords_error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="blog_meta_description">Blog Meta Description</label>
                            <textarea name="blog_meta_description" id="blog_meta_description" cols="120" rows="3"
                                class="form-control" placeholder="Write blog meta description"><?= esc($settings->blog_meta_description ?? '') ?></textarea>
                            <span class="text-danger error-text blog_meta_description_error"></span>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="tab-pane fade" id="logo_favicon" role="tabpanel">
                <div class="pd-20">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Set Blog Logo</h5>
                            <div class="mb-2 mt-1" style="max-width: 200px;">
                                <img src="/images/blog/<?= esc(get_settings()->blog_logo ?? 'default.png'); ?>" alt="Blog Logo Preview"
                                    class="img-thumbnail" id="logo-image-preview">
                            </div>
                            <form action="<?= route_to('update-blog-logo') ?>" method="post" enctype="multipart/form-data" id="changeBlogLogoForm">
                                <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" class="ci_csrf_data">
                                <div class="mb-2">
                                    <label for="blog_logo">Select New Logo</label>
                                    <input type="file" name="blog_logo" id="blog_logo" class="form-control" accept=".jpg,.jpeg,.png">
                                    <span class="text-danger error-text blog_logo_error"></span>
                                </div>
                                <button type="submit" class="btn btn-primary">Change Logo</button>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <h5>Set Blog Favicon</h5>
                            <div class="mb-2 mt-1" style="max-width: 100px;">
                                <img src="/images/blog/<?= esc(get_settings()->blog_favicon ?? 'default.png'); ?>" alt="Blog Favicon Preview"
                                    class="img-thumbnail" id="favicon-image-preview">
                            </div>
                            <form action="<?= route_to('update-blog-favicon') ?>" method="post" enctype="multipart/form-data" id="changeBlogFaviconForm">
                                <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" class="ci_csrf_data">
                                <div class="mb-2">
                                    <label for="blog_favicon">Select New Favicon</label>
                                    <input type="file" name="blog_favicon" id="blog_favicon" class="form-control" accept=".jpg,.jpeg,.png,.ico">
                                    <span class="text-danger error-text blog_favicon_error"></span>
                                </div>
                                <button type="submit" class="btn btn-primary">Change Favicon</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="social_media" role="tabpanel">
                <div class="pd-20">
                    <form action="<?= route_to('update-social-media') ?>" method="POST" id="social_media_form">
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= get_social_media()->facebook_url ?>" class="ci_csrf_data">
                        <div class="row">
                            <!-- Facebook URL -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="facebook_url">Facebook URL</label>
                                    <input type="url" class="form-control" name="facebook_url" id="facebook_url"
                                        placeholder="Enter your Facebook page URL" value="<?= get_social_media()->facebook_url ?>">
                                    <span class="text-danger error-text facebook_url_error"></span>
                                </div>
                            </div>

                            <!-- Twitter URL -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="twitter_url">Twitter URL</label>
                                    <input type="url" class="form-control" name="twitter_url" id="twitter_url"
                                        placeholder="Enter your Twitter profile URL" value="<?= get_social_media()->twitter_url ?>">
                                    <span class="text-danger error-text twitter_url_error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Instagram URL -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="instagram_url">Instagram URL</label>
                                    <input type="url" class="form-control" name="instagram_url" id="instagram_url"
                                        placeholder="Enter your Instagram profile URL" value="<?= get_social_media()->instagram_url ?>">
                                    <span class="text-danger error-text instagram_url_error"></span>
                                </div>
                            </div>

                            <!-- LinkedIn URL -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="linkedin_url">LinkedIn URL</label>
                                    <input type="url" class="form-control" name="linkedin_url" id="linkedin_url"
                                        placeholder="Enter your LinkedIn profile URL" value="<?= \get_social_media()->linkedin_url ?>">
                                    <span class="text-danger error-text linkedin_url_error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- GitHub URL -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="github_url">GitHub URL</label>
                                    <input type="url" class="form-control" name="github_url" id="github_url"
                                        placeholder="Enter your GitHub profile URL" value="<?= get_social_media()->github_url ?>">
                                    <span class="text-danger error-text github_url_error"></span>
                                </div>
                            </div>

                            <!-- YouTube URL -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="youtube_url">YouTube URL</label>
                                    <input type="url" class="form-control" name="youtube_url" id="youtube_url"
                                        placeholder="Enter your YouTube channel URL" value="<?= get_social_media()->youtube_url ?>">
                                    <span class="text-danger error-text youtube_url_error"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Save Social Media Links</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>

<script>
    $('#general_settings_form').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        let form = $(this);
        let formData = new FormData(this); // Create a new FormData object

        // CSRF Token Update
        formData.append($('.ci_csrf_data').attr('name'), $('.ci_csrf_data').val());

        $.ajax({
            url: form.attr('action'), // Form action URL
            method: form.attr('method'), // Form method
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function() {
                // Show a loading indicator using SweetAlert2
                toastr.info('Processing your request...', 'Please Wait');
                form.find('span.error-text').text(''); // Clear previous errors
            },
            success: function(response) {
                $('.ci_csrf_data').val(response.token); // Update CSRF token
                if (response.status === 1) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.msg,
                        timer: 3000,
                        showConfirmButton: false
                    });
                } else if (response.error) {
                    Swal.close(); // Close the loading alert
                    $.each(response.error, function(key, value) {
                        form.find(`span.${key}_error`).text(value); // Display validation errors
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please fix the errors and try again.'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.msg || 'An unexpected error occurred.'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred. Please try again later.'
                });
            }
        });
    });

    // Live Image Preview for Logo
    $('input[type="file"][name="blog_logo"]').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    $('#logo-image-preview').attr('src', event.target.result);
                };
                reader.readAsDataURL(file);
            } else {
                alert('Invalid file type! Please select a JPG, JPEG, or PNG image.');
            }
        }
    });

    // Live Image Preview for Favicon
    $('input[type="file"][name="blog_favicon"]').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (['image/jpeg', 'image/png', 'image/jpg', 'image/x-icon'].includes(file.type)) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    $('#favicon-image-preview').attr('src', event.target.result);
                };
                reader.readAsDataURL(file);
            } else {
                alert('Invalid file type! Please select a JPG, JPEG, PNG, or ICO image.');
            }
        }
    });

    // AJAX Form Submission for Logo
    $('#changeBlogLogoForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to update the blog logo?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, update it!',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === 1) {
                            Swal.fire('Updated!', response.msg, 'success').then(() => location.reload());
                        } else {
                            $('.ci_csrf_data').val(response.token); // Update CSRF token
                            $('.blog_logo_error').text(response.error); // Display validation error
                            Swal.fire('Error!', response.msg || 'Failed to update logo.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'An unexpected error occurred. Please try again.', 'error');
                    }
                });
            }
        });
    });

    // AJAX Form Submission for Favicon
    $('#changeBlogFaviconForm').on('submit', function(e) {
        e.preventDefault();

        var form = this;
        var formData = new FormData(form);
        var csrfToken = $('.ci_csrf_data').val();

        formData.append($('.ci_csrf_data').attr('name'), csrfToken);

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to update the blog favicon?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, update it!',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: $(form).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    beforeSend: function() {
                        $(form).find('span.error-text').text('');
                    },
                    success: function(response) {
                        $('.ci_csrf_data').val(response.token); // Update CSRF token
                        if (response.status === 1) {
                            Swal.fire('Success', response.msg, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Error', response.msg, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'An unexpected error occurred.', 'error');
                    },
                });
            }
        });
    });

    // AJAX Form Submission for Social media
    $('#social_media_form').on('submit', function (e) {
    e.preventDefault(); // Prevent the default form submission

    var form = this;
    var formData = new FormData(form); // Create FormData object for the form
    var csrfToken = $('.ci_csrf_data').val(); // Get the CSRF token

    formData.append($('.ci_csrf_data').attr('name'), csrfToken); // Append the CSRF token to the form data

    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to update the social media settings?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, update it!',
        cancelButtonText: 'Cancel',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: $(form).attr('action'), // Form action URL
                method: 'POST', // HTTP method
                data: formData, // Form data
                processData: false, // Prevent jQuery from processing the data
                contentType: false, // Prevent jQuery from setting content type
                dataType: 'json', // Expect JSON response
                beforeSend: function () {
                    $(form).find('span.error-text').text(''); // Clear any previous error messages
                },
                success: function (response) {
                    $('.ci_csrf_data').val(response.token); // Update CSRF token
                    if (response.status === 1) {
                        Swal.fire('Success', response.msg, 'success').then(() => location.reload());
                    } else if (response.errors) {
                        // Display validation errors
                        $.each(response.errors, function (key, value) {
                            $(form).find(`span.${key}_error`).text(value);
                        });
                        Swal.fire('Error', 'Please fix the errors and try again.', 'error');
                    } else {
                        Swal.fire('Error', response.msg || 'An unexpected error occurred.', 'error');
                    }
                },
                error: function () {
                    Swal.fire('Error', 'An unexpected error occurred. Please try again later.', 'error');
                },
            });
        }
    });
});




</script>

<?= $this->endSection(); ?>
<?= $this->extend('backend/layout/pages-layout.php') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Add Post</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home'); ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Add Post
                    </li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
            <a href="<?= route_to('all-posts') ?>" class="btn btn-primary">View All Posts</a>
        </div>
    </div>
</div>
<form action="<?= route_to('create-post') ?>" method="post" autocomplete="off" enctype="multipart/form-data" id="addPostForm">
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="ci_csrf_data">
    <div class="row">
        <div class="col-md-9">
            <div class="card card-box mb-2">
                <div class="card-body">
                    <div class="form-group">
                        <label for="title"><b>Post Title</b></label>
                        <input type="text" class="form-control" placeholder="Enter post title" name="title" id="title">
                        <span class="text-danger error-text title_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="content"><b>Content</b></label>
                        <textarea name="content" id="content" cols="30" rows="10" class="form-control" placeholder="Type..."></textarea>
                        <span class="text-danger error-text content_error"></span>
                    </div>

                </div>
            </div>
            <div class="card card-box mb-2">
                <h5 class="card-header weight-500">SEO</h5>
                <div class="card-body">
                    <div class="form-group">
                        <label for="meta_keywords"><b>Post Meta Keywords</b><small> (Separated by comma)</small></label>
                        <input type="text" class="form-control" placeholder="Enter post meta keywords" name="meta_keywords" id="meta_keywords">
                    </div>
                    <div class="form-group">
                        <label for="meta_description"><b>Post Meta Description</b></label>
                        <textarea name="meta_description" id="meta_description" cols="30" rows="10" class="form-control" placeholder="Type meta description"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-box mb-2">
                <div class="card-body">
                    <div class="form-group">
                        <label for="category"><b>Post Category</b></label>
                        <select name="category" id="category" class="custom-select form-control">
                            <option value="">Choose...</option>
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?= $category->id ?>"><?= $category->name ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="text-danger error-text category_error"></span>
                    </div>

                    <!-- Featured Image Upload -->
                    <div class="form-group">
                        <label for="featured_image"><b>Post Featured Image</b></label>
                        <input type="file" name="featured_image" id="featured_image" class="form-control-file form-control" accept="image/*">
                        <span class="text-danger error-text featured_image_error"></span>
                    </div>

                    <!-- Image Preview Section -->
                    <div class="d-block mb-3" style="max-width: 250px;">
                        <img src="" alt="Image preview" class="img-thumbnail" id="image-previewer" style="display: none;">
                    </div>
                    <div class="form-group">
                        <label for="tags"><b>Tags</b></label>
                        <input type="text" class="form-control" placeholder="Enter tags" name="tags" id="tags" data-role="tagsinput">
                        <span class="text-danger error-text tags_error"></span>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="visibility"><b>Visibility</b></label>
                        <div class="custom-control custom-radio mb-5">
                            <input
                                type="radio"
                                name="visibility"
                                id="customRadio1"
                                class="custom-control-input"
                                value="1"
                                checked>
                            <label for="customRadio1" class="custom-control-label">Public</label>
                        </div>
                        <div class="custom-control custom-radio mb-5">
                            <input
                                type="radio"
                                name="visibility"
                                id="customRadio2"
                                class="custom-control-input"
                                value="2">
                            <label for="customRadio2" class="custom-control-label">Private</label>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <center><b><button type="submit" class="btn btn-primary">Create Post</button></b></center>

</form>


<?= $this->endSection() ?>
<?= $this->section('stylesheets') ?>
<link rel="stylesheet" href="/backend/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css">
<link rel="stylesheet" href="/extra-assets/elFinder/css/elfinder.min.css">
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="/backend/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
<script src="/extra-assets/ckeditor/ckeditor.js"></script>
<link rel="stylesheet" href="/extra-assets/elFinder/css/elfinder.min.css">
<script src="/extra-assets/elFinder/js/jquery.elfinder.js"></script>

<!-- <script>
    // Initialize CKEditor
    document.addEventListener('DOMContentLoaded', function () {
        
        if (typeof CKEDITOR !== 'undefined') {
            CKEDITOR.replace('content', {
                height: 300,
                toolbar: [
                    { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike'] },
                    { name: 'paragraph', items: ['NumberedList', 'BulletedList'] },
                    { name: 'insert', items: ['Image', 'Link', 'Unlink'] },
                    { name: 'tools', items: ['Maximize', 'Source'] }
                ]
            });
        } else {
            console.error('CKEditor script is not loaded.');
        }
    });

    // File validation and preview
    document.getElementById('featured_image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const allowedExtensions = ['image/jpeg', 'image/png', 'image/jpg'];
            const maxSize = 2 * 1024 * 1024; // 2MB
            if (!allowedExtensions.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type',
                    text: 'Only JPG, PNG, and JPEG files are allowed.'
                });
                event.target.value = '';
                document.getElementById('image-previewer').style.display = 'none';
                return;
            }
            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'The file size must be less than 2MB.'
                });
                event.target.value = '';
                document.getElementById('image-previewer').style.display = 'none';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewer = document.getElementById('image-previewer');
                previewer.src = e.target.result;
                previewer.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            document.getElementById('image-previewer').style.display = 'none';
        }
    });

    // Form submission with AJAX
$('#addPostForm').on('submit', function(e) {
    e.preventDefault();

    const crName = $('.ci_csrf_data').attr('name'); // CSRF token name
    const csrfHash = $('.ci_csrf_data').val(); // CSRF token value

    const form = this;
    const content = CKEDITOR.instances.content.getData(); // Get data from CKEditor

    const formData = new FormData(form);
    formData.append(crName, csrfHash);
    formData.append('content', content); // Append CKEditor content to the FormData

    $.ajax({
        url: $(form).attr('action'),
        method: $(form).attr('method'),
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        beforeSend: function() {
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while we submit your post.',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => Swal.showLoading()
            });
            $(form).find('span.error-text').text(''); // Clear previous error messages
        },
        success: function(response) {
            $('.ci_csrf_data').val(response.token); // Update CSRF token
            Swal.close(); // Close the loading SweetAlert

            if ($.isEmptyObject(response.error)) {
                if (response.status == 1) {
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.msg,
                    });

                    // Reset the form
                    $(form)[0].reset();
                    CKEDITOR.instances.content.setData(''); // Clear CKEditor content
                    $('#image-previewer').hide(); // Hide image preview
                    $('input[name="tags"]').tagsinput('removeAll'); // Reset tags input
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.msg,
                    });
                }
            } else {
                // Handle validation errors
                $.each(response.error, function(prefix, val) {
                    $(form).find('span.' + prefix + '_error').text(val);
                });
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Errors',
                    text: 'Please fix the highlighted fields and try again.'
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while submitting your post. Please try again.'
            });
        }
    });
});

</script> -->

<script>
document.addEventListener('DOMContentLoaded', function () {
        // Initialize CKEditor with elFinder Integration
        if (typeof CKEDITOR !== 'undefined') {
            CKEDITOR.replace('content', {
                height: 300,
                filebrowserBrowseUrl: '/extra-assets/elFinder/elfinder.src.php',
                filebrowserImageBrowseUrl: '/extra-assets/elFinder/elfinder.src.php?type=Images',
                filebrowserUploadUrl: '/extra-assets/elFinder/elfinder.src.php?command=QuickUpload&type=Files',
                filebrowserImageUploadUrl: '/extra-assets/elFinder/elfinder.src.php?command=QuickUpload&type=Images',
                removeDialogTabs: 'link:upload;image:upload'
            });
        } else {
            console.error('CKEditor script is not loaded.');
        }

        // File validation and image preview
        document.getElementById('featured_image').addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const allowedExtensions = ['image/jpeg', 'image/png', 'image/jpg'];
                const maxSize = 2 * 1024 * 1024; // 2MB
                if (!allowedExtensions.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid File Type',
                        text: 'Only JPG, PNG, and JPEG files are allowed.'
                    });
                    event.target.value = '';
                    document.getElementById('image-previewer').style.display = 'none';
                    return;
                }
                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Too Large',
                        text: 'The file size must be less than 2MB.'
                    });
                    event.target.value = '';
                    document.getElementById('image-previewer').style.display = 'none';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function (e) {
                    const previewer = document.getElementById('image-previewer');
                    previewer.src = e.target.result;
                    previewer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('image-previewer').style.display = 'none';
            }
        });

        // Handle Form Submission
        $('#addPostForm').on('submit', function (e) {
            e.preventDefault();

            const crName = $('.ci_csrf_data').attr('name');
            const csrfHash = $('.ci_csrf_data').val();

            const form = this;
            const content = CKEDITOR.instances.content.getData();

            const formData = new FormData(form);
            formData.append(crName, csrfHash);
            formData.append('content', content);

            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function () {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait while we submit your post.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => Swal.showLoading()
                    });
                    $(form).find('span.error-text').text('');
                },
                success: function (response) {
                    $('.ci_csrf_data').val(response.token); // Update CSRF token
                    Swal.close();

                    if ($.isEmptyObject(response.error)) {
                        if (response.status == 1) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.msg,
                            });
                            $(form)[0].reset();
                            CKEDITOR.instances.content.setData('');
                            $('#image-previewer').hide();
                            $('input[name="tags"]').tagsinput('removeAll');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.msg,
                            });
                        }
                    } else {
                        $.each(response.error, function (prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val);
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Errors',
                            text: 'Please fix the highlighted fields and try again.'
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while submitting your post. Please try again.'
                    });
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>
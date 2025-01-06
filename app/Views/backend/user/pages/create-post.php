<?= $this->extend('backend/user/layout/pages-layout.php') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Create New Post</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('user.dashboard'); ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('user.blogs'); ?>">My Blogs</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Create New Post
                    </li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
            <a href="<?= route_to('user.blogs'); ?>" class="btn btn-primary">View My Blogs</a>
        </div>
    </div>
</div>

<form action="<?= route_to('user.store-post') ?>" method="POST" enctype="multipart/form-data" id="addPostForm">
    <?= csrf_field(); ?>
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="ci_csrf_data">
    <div class="row">
        <!-- Main Content Area -->
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
                        <label for="meta_keywords"><b>Post Meta Keywords</b> <small>(Separated by comma)</small></label>
                        <input type="text" class="form-control" placeholder="Enter post meta keywords" name="meta_keywords" id="meta_keywords">
                    </div>
                    <div class="form-group">
                        <label for="meta_description"><b>Post Meta Description</b></label>
                        <textarea name="meta_description" id="meta_description" cols="30" rows="3" class="form-control" placeholder="Type meta description"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Area -->
        <div class="col-md-3">
            <div class="card card-box mb-2">
                <div class="card-body">
                    <div class="form-group">
                        <label for="subcategory"><b>Post Subcategory</b></label>
                        <select name="subcategory_id" id="subcategory" class="custom-select form-control">
                            <option value="">Choose...</option>
                            <?php foreach ($subCategories as $subCategory) : ?>
                                <option value="<?= $subCategory->id ?>"><?= $subCategory->name ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="text-danger error-text subcategory_id_error"></span>
                    </div>

                    <div class="form-group">
                        <label for="featured_image"><b>Post Featured Image</b></label>
                        <input type="file" name="featured_image" id="featured_image" class="form-control-file form-control" accept="image/*">
                        <span class="text-danger error-text featured_image_error"></span>

                        <!-- Preview the selected image -->
                        <div class="mt-3">
                            <img id="image-preview" src="#" alt="Image Preview" style="max-width: 240px; display: none;" class="img-thumbnail">
                        </div>
                    </div>

                    <div class="d-block mb-3" style="max-width: 250px;">
                        <img src="" alt="Image preview" class="img-thumbnail" id="image-previewer" style="display: none;">
                    </div>

                    <div class="form-group">
                        <label for="tags"><b>Tags</b></label>
                        <input type="text" class="form-control" placeholder="Enter tags" name="tags" id="tags" data-role="tagsinput">
                        <span class="text-danger error-text tags_error"></span>
                    </div>

                    <div class="form-group">
                        <label for="visibility"><b>Visibility</b></label>
                        <div class="custom-control custom-radio mb-2">
                            <input type="radio" name="visibility" id="publicVisibility" class="custom-control-input" value="1" checked>
                            <label for="publicVisibility" class="custom-control-label">Public</label>
                        </div>
                        <div class="custom-control custom-radio mb-2">
                            <input type="radio" name="visibility" id="privateVisibility" class="custom-control-input" value="2">
                            <label for="privateVisibility" class="custom-control-label">Private</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary"><b>Create Post</b></button>
    </div>
</form>


<?= $this->endSection() ?>

<?= $this->section('stylesheets') ?>
<link rel="stylesheet" href="/backend/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css">
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="/backend/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
<script src="/extra-assets/ckeditor/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // CKEditor Initialization
    CKEDITOR.replace('content', {
        height: 300
    });

    // Featured Image Validation and Preview
    // document.getElementById('featured_image').addEventListener('change', function(event) {
    //     const file = event.target.files[0];
    //     if (file) {
    //         const allowedExtensions = ['image/jpeg', 'image/png', 'image/jpg'];
    //         const maxSize = 2 * 1024 * 1024; // 2MB
    //         if (!allowedExtensions.includes(file.type)) {
    //             Swal.fire('Error', 'Only JPG, PNG, and JPEG files are allowed.', 'error');
    //             event.target.value = '';
    //             document.getElementById('image-previewer').style.display = 'none';
    //             return;
    //         }
    //         if (file.size > maxSize) {
    //             Swal.fire('Error', 'The file size must be less than 2MB.', 'error');
    //             event.target.value = '';
    //             document.getElementById('image-previewer').style.display = 'none';
    //             return;
    //         }
    //         const reader = new FileReader();
    //         reader.onload = function(e) {
    //             document.getElementById('image-previewer').src = e.target.result;
    //             document.getElementById('image-previewer').style.display = 'block';
    //         };
    //         reader.readAsDataURL(file);
    //     } else {
    //         document.getElementById('image-previewer').style.display = 'none';
    //     }
    // });

    document.getElementById('featured_image').addEventListener('change', function(event) {
        const file = event.target.files[0];

        if (file) {
            const allowedExtensions = ['image/jpeg', 'image/png', 'image/jpg'];
            const maxSize = 2 * 1024 * 1024; // 2MB

            // Validate file type
            if (!allowedExtensions.includes(file.type)) {
                alert('Invalid file type. Please upload a JPG, JPEG, or PNG image.');
                event.target.value = '';
                document.getElementById('image-preview').style.display = 'none';
                return;
            }

            // Validate file size
            if (file.size > maxSize) {
                alert('File size exceeds 2MB. Please upload a smaller image.');
                event.target.value = '';
                document.getElementById('image-preview').style.display = 'none';
                return;
            }

            // Preview the image
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('image-preview');
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            // Hide preview if no file selected
            document.getElementById('image-preview').style.display = 'none';
        }
    });

    // Form Submission with SweetAlert2
    document.getElementById('addPostForm').addEventListener('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);
        const content = CKEDITOR.instances.content.getData();
        formData.append('content', content);

        fetch('<?= route_to("user.store-post") ?>', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 1) {
                    Swal.fire('Success', data.msg, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.msg || 'Validation error occurred.', 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'An unexpected error occurred. Please try again.', 'error');
                console.error(error);
            });
    });
</script>
<?= $this->endSection() ?>
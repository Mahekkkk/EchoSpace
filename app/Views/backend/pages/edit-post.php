<?= $this->extend('backend/layout/pages-layout.php') ?>
<?= $this->section('content') ?>
<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Edit Post</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home'); ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('all-posts'); ?>">All Posts</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Edit Post
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<form action="<?= route_to('update-post', $post['id']) ?>" method="post" autocomplete="off" enctype="multipart/form-data" id="editPostForm">
    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="ci_csrf_data">
    <div class="row">
        <div class="col-md-9">
            <div class="card card-box mb-2">
                <div class="card-body">
                    <div class="form-group">
                        <label for="title"><b>Post Title</b></label>
                        <input type="text" class="form-control" placeholder="Enter post title" name="title" id="title" value="<?= esc($post['title']) ?>">
                        <span class="text-danger error-text title_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="content"><b>Content</b></label>
                        <textarea name="content" id="content" cols="30" rows="10" class="form-control" placeholder="Type..."><?= esc($post['content']) ?></textarea>
                        <span class="text-danger error-text content_error"></span>
                    </div>
                </div>
            </div>
            <div class="card card-box mb-2">
                <h5 class="card-header weight-500">SEO</h5>
                <div class="card-body">
                    <div class="form-group">
                        <label for="meta_keywords"><b>Post Meta Keywords</b><small> (Separated by comma)</small></label>
                        <input type="text" class="form-control" placeholder="Enter post meta keywords" name="meta_keywords" id="meta_keywords" value="<?= esc($post['meta_keywords']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="meta_description"><b>Post Meta Description</b></label>
                        <textarea name="meta_description" id="meta_description" cols="30" rows="10" class="form-control" placeholder="Type meta description"><?= esc($post['meta_description']) ?></textarea>
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
                                <option value="<?= $category['id'] ?>" <?= $post['category_id'] == $category['id'] ? 'selected' : '' ?>><?= esc($category['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="text-danger error-text category_error"></span>
                    </div>
                    <!-- Featured Image Upload -->
                    <div class="form-group">
                        <label for="featured_image"><b>Post Featured Image</b></label>
                        <input type="file" name="featured_image" id="featured_image" class="form-control-file form-control" accept="image/*">
                        <img src="<?= base_url('images/posts/thumb_' . $post['featured_image']) ?>" alt="Current Featured Image" class="img-thumbnail mt-3" style="max-width: 250px;">
                        <span class="text-danger error-text featured_image_error"></span>
                    </div>
                    <!-- Tags Input -->
                    <div class="form-group">
                        <label for="tags"><b>Tags</b></label>
                        <input type="text" class="form-control" placeholder="Enter tags" name="tags" id="tags" data-role="tagsinput" value="<?= esc($post['tags']) ?>">
                        <span class="text-danger error-text tags_error"></span>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="visibility"><b>Visibility</b></label>
                        <div class="custom-control custom-radio mb-5">
                            <input type="radio" name="visibility" id="customRadio1" class="custom-control-input" value="1" <?= $post['visibility'] == 1 ? 'checked' : '' ?>>
                            <label for="customRadio1" class="custom-control-label">Public</label>
                        </div>
                        <div class="custom-control custom-radio mb-5">
                            <input type="radio" name="visibility" id="customRadio2" class="custom-control-input" value="0" <?= $post['visibility'] == 0 ? 'checked' : '' ?>>
                            <label for="customRadio2" class="custom-control-label">Private</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <center><b><button type="submit" class="btn btn-primary">Update Post</button></b></center>
</form>
<?= $this->endSection() ?>
<?= $this->section('stylesheets') ?>
<link rel="stylesheet" href="/backend/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css">
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="/backend/src/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
<script src="/extra-assets/ckeditor/ckeditor.js"></script>
<script src="/extra-assets/elFinder/js/jquery.elfinder.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize CKEditor with elFinder integration
        if (typeof CKEDITOR !== 'undefined') {
            CKEDITOR.replace('content', {
                height: 300,
                //filebrowserBrowseUrl: '/extra-assets/elFinder/elfinder.html',
               // filebrowserImageBrowseUrl: '/extra-assets/elFinder/elfinder.html?type=Images',
               // filebrowserUploadUrl: '/extra-assets/elFinder/connector.php?command=QuickUpload&type=Files',
               // filebrowserImageUploadUrl: '/extra-assets/elFinder/connector.php?command=QuickUpload&type=Images'
                filebrowserBrowseUrl: '/extra-assets/elFinder/elfinder.src.php',
                filebrowserImageBrowseUrl: '/extra-assets/elFinder/elfinder.src.php?type=Images',
                filebrowserUploadUrl: '/extra-assets/elFinder/elfinder.src.php?command=QuickUpload&type=Files',
                filebrowserImageUploadUrl: '/extra-assets/elFinder/elfinder.src.php?command=QuickUpload&type=Images',
                removeDialogTabs: 'link:upload;image:upload'
            });
        } else {
            console.error('CKEditor is not loaded.');
        }
        // Handle form submission with AJAX
        $('#editPostForm').on('submit', function(e) {
            e.preventDefault();
            const crName = $('.ci_csrf_data').attr('name');
            const csrfHash = $('.ci_csrf_data').val();
            const content = CKEDITOR.instances.content.getData();
            const formData = new FormData(this);
            formData.append(crName, csrfHash);
            formData.append('content', content);
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Updating your post.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => Swal.showLoading()
                    });
                    $('span.error-text').text('');
                },
                success: function(response) {
                    $('.ci_csrf_data').val(response.token);
                    Swal.close();
                    if (response.status === 1) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated',
                            text: response.msg
                        }).then(() => {
                            window.location.href = "<?= route_to('all-posts') ?>";
                        });
                    } else if (response.error) {
                        $.each(response.error, function(key, val) {
                            $('span.' + key + '_error').text(val);
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Check the highlighted fields.'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.msg
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed',
                        text: 'Unable to update the post.'
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>
<?= $this->extend('backend/user/layout/pages-layout.php') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>All Posts</h4>
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
                        All Posts
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card-box">
    <div class="card-header">
        <h5>Your Posts</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($posts) && is_array($posts)) : ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Subcategory</th>
                        <th>Created At</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $key => $post) : ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= esc($post->title) ?></td>
                            <td><?= esc($post->subcategory_name ?? 'N/A') ?></td>
                            <td><?= date('M d, Y', strtotime($post->created_at)) ?></td>
                            <td>
                                <?= $post->status == 1
                                    ? '<span class="badge badge-success">Approved</span>'
                                    : ($post->status == 2
                                        ? '<span class="badge badge-danger">Rejected</span>'
                                        : '<span class="badge badge-warning">Pending</span>') ?>
                            </td>
                            <td>

                                <button class="btn btn-sm btn-danger delete-post" data-id="<?= $post->id ?>">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        <?php else : ?>
            <p>No posts found.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Delete Post
        document.querySelectorAll('.delete-post').forEach(button => {
            button.addEventListener('click', function() {
                const postId = this.dataset.id;

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to undo this action!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then(result => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '<?= route_to('user.delete-post') ?>',
                            method: 'POST',
                            data: {
                                post_id: postId,
                                <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.status === 1) {
                                    Swal.fire('Deleted!', response.msg, 'success').then(() => location.reload());
                                } else {
                                    Swal.fire('Error!', response.msg, 'error');
                                }
                            },
                            error: function() {
                                Swal.fire('Error!', 'An unexpected error occurred.', 'error');
                            }
                        });
                    }
                });
            });
        });
    });
</script>


<?= $this->endSection() ?>
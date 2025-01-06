<?= $this->extend('backend/layout/pages-layout.php') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Review User Posts</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Review User Posts
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card-box">
    <div class="card-header">
        <h5>Posts Awaiting Approval</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($userPosts) && is_array($userPosts)) : ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Subcategory</th>
                        <th>Author</th>
                        <th>Created At</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($userPosts as $key => $post) : ?>
                        <tr>
                            <td><?= $key + 1 + (($pager->getCurrentPage() - 1) * $pager->getPerPage()) ?></td>
                            <td><?= esc($post->title) ?></td>
                            <td><?= esc($post->subcategory_name ?? 'N/A') ?></td>
                            <td><?= esc($post->author_name ?? 'Unknown') ?></td>
                            <td><?= date('M d, Y', strtotime($post->created_at)) ?></td>
                            <td>
                                <?= $post->status == 0 ? '<span class="badge badge-warning">Pending</span>' : '<span class="badge badge-success">Approved</span>' ?>
                            </td>
                            <td>
                                <a href="<?= route_to('admin.user-posts.view', $post->id) ?>" class="btn btn-sm btn-info">View</a>
                                <button class="btn btn-sm btn-success approve-post" data-id="<?= $post->id ?>">Approve</button>
                               

                                <button class="btn btn-sm btn-danger delete-post" data-id="<?= $post->id ?>">Reject</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Pagination Links -->
            <div class="mt-3">
                <?= $pager->links() ?>
            </div>
        <?php else : ?>
            <p>No posts awaiting approval.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfName = '<?= csrf_token() ?>';
        const csrfHash = '<?= csrf_hash() ?>';

        // Approve Post
        document.querySelectorAll('.approve-post').forEach(button => {
            button.addEventListener('click', function() {
                const postId = this.dataset.id;
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Approve this post?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, approve it!'
                }).then(result => {
                    if (result.isConfirmed) {
                        $.post('<?= route_to('admin.approve-user-post') ?>/' + postId, {
                            [csrfName]: csrfHash
                        }, function(response) {
                            if (response.status === 1) {
                                Swal.fire('Approved!', response.msg, 'success').then(() => location.reload());
                            } else {
                                Swal.fire('Error!', response.msg, 'error');
                            }
                        }, 'json');
                    }
                });
            });
        });

        // Reject Post

        // Reject Post
// Reject Post
document.querySelectorAll('.reject-post').forEach(button => {
    button.addEventListener('click', function () {
        const postId = this.dataset.id;

        Swal.fire({
            title: 'Are you sure?',
            text: "Reject this post?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, reject it!'
        }).then(result => {
            if (result.isConfirmed) {
                $.post('<?= route_to('admin.user-posts.reject') ?>/' + postId, {
                    <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                }, function (response) {
                    if (response.status === 1) {
                        Swal.fire('Rejected!', response.msg, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error!', response.msg, 'error');
                    }
                }, 'json');
            }
        });
    });
});



        // Delete Post
        document.querySelectorAll('.delete-post').forEach(button => {
            button.addEventListener('click', function() {
                const postId = this.dataset.id;
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!'
                }).then(result => {
                    if (result.isConfirmed) {
                        $.post('<?= route_to('admin.delete-user-post') ?>', {
                            post_id: postId,
                            [csrfName]: csrfHash
                        }, function(response) {
                            if (response.status === 1) {
                                Swal.fire('Deleted!', response.msg, 'success').then(() => location.reload());
                            } else {
                                Swal.fire('Error!', response.msg, 'error');
                            }
                        }, 'json');
                    }
                });
            });
        });
    });
</script>

<?= $this->endSection() ?>
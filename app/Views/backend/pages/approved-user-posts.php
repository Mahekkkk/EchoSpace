<?= $this->extend('backend/layout/pages-layout.php') ?>
<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-12">
            <div class="title">
                <h4>Approved User Posts</h4>
            </div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home') ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Approved Posts</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="card-box">
    <div class="card-header">
        <h5>Approved Posts</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($posts) && is_array($posts)) : ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Subcategory</th>
                        <th>Author</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $key => $post) : ?>
                        <tr>
                            <td><?= $key + 1 ?></td>
                            <td><?= esc($post->title) ?></td>
                            <td><?= esc($post->subcategory_name) ?></td>
                            <td><?= esc($post->author_name) ?></td>
                            <td><?= date('M d, Y', strtotime($post->created_at)) ?></td>
                            <td>
                                <button class="btn btn-sm btn-danger delete-post" data-id="<?= $post->id ?>">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No approved posts found.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Delete Post
        document.querySelectorAll('.delete-post').forEach(button => {
            button.addEventListener('click', function () {
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
                        $.post('<?= route_to('admin.delete-approved-user-post') ?>', {
                            post_id: postId,
                            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
                        }, function (response) {
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

<!-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        const csrfName = '<?= csrf_token() ?>';
        const csrfHash = '<?= csrf_hash() ?>';

        const showProcessing = () => {
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while your action is being processed.',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        };

        const reloadPosts = () => {
            $.ajax({
                url: '<?= route_to('admin.approved-user-posts') ?>',
                method: 'GET',
                success: function (response) {
                    if (response.status === 1) {
                        // Update the table dynamically
                        let tableBody = document.querySelector('tbody');
                        tableBody.innerHTML = '';
                        response.posts.forEach((post, index) => {
                            tableBody.innerHTML += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${post.title}</td>
                                    <td>${post.author_name}</td>
                                    <td>${post.subcategory_name}</td>
                                    <td>${post.created_at}</td>
                                    <td>
                                        <button class="btn btn-sm btn-danger delete-post" data-id="${post.id}">Delete</button>
                                    </td>
                                </tr>`;
                        });

                        // Reattach event listeners for the new buttons
                        attachDeleteEventListeners();
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'Failed to fetch updated posts. Please reload the page.', 'error');
                }
            });
        };

        const attachDeleteEventListeners = () => {
            // Delete Post
            document.querySelectorAll('.delete-post').forEach(button => {
                button.addEventListener('click', function () {
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
                            showProcessing();
                            $.post('<?= route_to('admin.delete-approved-user-post') ?>', {
                                post_id: postId,
                                [csrfName]: csrfHash
                            }, function (response) {
                                Swal.close();
                                if (response.status === 1) {
                                    Swal.fire('Deleted!', response.msg, 'success');
                                    reloadPosts();
                                } else {
                                    Swal.fire('Error!', response.msg, 'error');
                                }
                            }, 'json');
                        }
                    });
                });
            });
        };

        // Attach event listeners for the first load
        attachDeleteEventListeners();
    });
</script> -->


<?= $this->endSection() ?>

<?= $this->extend('backend/layout/pages-layout.php') ?>

<?= $this->section('content') ?>

<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>All Posts</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= route_to('admin.home'); ?>">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">All Posts</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
            <a href="<?= route_to('new-post') ?>" class="btn btn-primary">Add New Post</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card card-box">
            <div class="card-header">
                <div class="clearfix">
                    <div class="pull-left">
                        <h5>All Posts</h5>
                    </div>
                    <!-- <div class="pull-right">
                        <a href="<?= route_to('create-post'); ?>" class="btn btn-primary btn-sm">Add New Post</a>
                    </div> -->
                </div>
            </div>
            <div class="card-body">
                <table class="data-table table table-striped table-hover nowrap dataTable no-footer dtr-inline collapsed" id="posts-table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col"> Featured Image</th>
                            <th scope="col">Title</th>
                            <th scope="col">Category</th>
                            <th scope="col">Visibility</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<?= $this->endSection() ?>
<?= $this->section('stylesheets') ?>
<link rel="stylesheet" href="/backend/src/plugins/datatables/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/backend/src/plugins/datatables/css/responsive.bootstrap4.min.css">
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="/backend/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="/backend/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="/backend/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
<script src="/backend/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        //Initialize DataTable
        var posts_DT= $('table#posts-table').DataTable({
            scrollCollapse: true,
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: false, // Set to true if you want to use server-side processing
            ajax:"<?= route_to('get-posts'); ?>",
            "dom":"IBfrtip",
            info: true,
            fnCreatedRow: function(row,data,index){
                $('td',row).eq(0).html(index+1);
            },
            columnDefs: [
                { orderable: false, targets:[0,1,2,3,4,5]  } // Disable sorting on the last column (Actions)
            ]
        });

    });

    $(document).on('click', '.editPostBtn', function(e) {
    e.preventDefault();

    var editUrl = $(this).data('url'); // Get the edit URL from the button

    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to edit this post?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, edit it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirect to the edit page
            window.location.href = editUrl;
        }
    });
});

    $(document).on('click', '.deletePostBtn', function(e) {
    e.preventDefault();

    var id = $(this).data('id'); // Get post ID from button
    var csrfName = $('.ci_csrf_data').attr('name'); // CSRF Token name
    var csrfHash = $('.ci_csrf_data').val(); // CSRF Token hash

    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?= route_to('delete-post'); ?>", // Delete route
                method: "POST",
                data: {
                    [csrfName]: csrfHash,
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    // Update CSRF Token
                    $('.ci_csrf_data').val(response.token);

                    if (response.status == 1) {
                        Swal.fire(
                            'Deleted!',
                            response.msg,
                            'success'
                        );

                        // Reload DataTable
                        $('table#posts-table').DataTable().ajax.reload();
                    } else {
                        Swal.fire(
                            'Error!',
                            response.msg,
                            'error'
                        );
                    }
                },
                error: function() {
                    Swal.fire(
                        'Error!',
                        'An error occurred. Please try again.',
                        'error'
                    );
                }
            });
        }
    });
});


    
    
</script>
<?= $this->endSection() ?>
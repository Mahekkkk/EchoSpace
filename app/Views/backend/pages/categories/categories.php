<?= $this->extend('backend/layout/pages-layout.php') ?>
<?= $this->section('content') ?>
<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Categories</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= route_to('admin.home'); ?>">Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Categories
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card card-box">
            <div class="card-header">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4>Categories</h4>
                    </div>
                    <div class="pull-right">
                        <a href="#" class="btn btn-add-category" id="add_category_btn">
                            <i class="fa fa-plus-circle"></i> Add New Category
                        </a>
                        <style>
                            /* Button Styling */
                            .btn-add-category {
                                display: inline-flex;
                                align-items: center;
                                justify-content: center;
                                gap: 8px;
                                padding: 10px 15px;
                                font-size: 16px;
                                font-weight: 600;
                                color: #fff;
                                background: linear-gradient(45deg, #6a11cb, #2575fc);
                                border: none;
                                border-radius: 30px;
                                text-decoration: none;
                                box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
                                transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out, background 0.3s;
                            }

                            /* Button Hover Effects */
                            .btn-add-category:hover {
                                background: linear-gradient(45deg, #2575fc, #6a11cb);
                                box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2);
                                transform: translateY(-3px);
                            }

                            .btn-add-category:active {
                                transform: translateY(1px);
                                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                            }

                            /* Icon Styling */
                            .btn-add-category i {
                                font-size: 18px;
                                color: #fff;
                                animation: spin 1.5s infinite;
                            }

                            /* Icon Animation */
                            @keyframes spin {
                                0% {
                                    transform: rotate(0deg);
                                }

                                100% {
                                    transform: rotate(360deg);
                                }
                            }
                        </style>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless table-hover table-striped" id="categories-table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Category Name</th>
                            <th scope="col">No of Sub categories</th>
                            <th scope="col">Action</th>
                            <th scope="col">Ordering</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-12 mb-4">
        <div class="card card-box">
            <div class="card-header">
                <div class="clearfix">
                    <div class="pull-left">
                        <h4>Sub Categories</h4>
                    </div>
                    <div class="pull-right">
                        <a href="#" class="btn btn-add-subcategory" id="add_subcategory_btn">
                            <i class="fa fa-plus-circle"></i> Add Sub Category
                        </a>
                        <style>
                            /* Button Styling */
                            .btn-add-subcategory {
                                display: inline-flex;
                                align-items: center;
                                justify-content: center;
                                gap: 8px;
                                padding: 10px 15px;
                                font-size: 16px;
                                font-weight: 600;
                                color: #fff;
                                background: linear-gradient(45deg, #ff7e5f, #feb47b);
                                border: none;
                                border-radius: 30px;
                                text-decoration: none;
                                box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
                                transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out, background 0.3s;
                            }

                            /* Button Hover Effects */
                            .btn-add-subcategory:hover {
                                background: linear-gradient(45deg, #feb47b, #ff7e5f);
                                box-shadow: 0 12px 20px rgba(0, 0, 0, 0.2);
                                transform: translateY(-3px);
                            }

                            .btn-add-subcategory:active {
                                transform: translateY(1px);
                                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                            }

                            /* Icon Styling */
                            .btn-add-subcategory i {
                                font-size: 18px;
                                color: #fff;
                                animation: spin 1.5s infinite;
                            }

                            /* Icon Animation */
                            @keyframes spin {
                                0% {
                                    transform: rotate(0deg);
                                }

                                100% {
                                    transform: rotate(360deg);
                                }
                            }
                        </style>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless table-hover table-striped" id="subcategories-table">
                    <thead>
                        <tr>
                            <th scope="col">*</th>
                            <th scope="col">Sub-category Name</th>
                            <th scope="col">Parent Category</th>
                            <th scope="col">No. of Post(s)</th>
                            <th scope="col">Action</th>
                            <th scope="col">ID</th>
                            <th scope="col">Ordering</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= view('backend/pages/modals/category-modal-form.php'); ?>
<?= view('backend/pages/modals/edit-category-modal-form.php'); ?>
<?= view('backend/pages/modals/subcategory-modal-form.php'); ?>
<?= view('backend/pages/modals/edit-subcategory-modal-form.php')?>



<?= $this->endSection() ?>

<?= $this->section('stylesheets') ?>
<link rel="stylesheet" href="/backend/src/plugins/datatables/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="/backend/src/plugins/datatables/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="/extra-assets/jquery-ui-1.14.1/jquery-ui.min.css">
<link rel="stylesheet" href="/extra-assets/jquery-ui-1.14.1/jquery-ui.structure.min.css">
<link rel="stylesheet" href="/extra-assets/jquery-ui-1.14.1/jquery-ui.theme.min.css">
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script src="/backend/src/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="/backend/src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="/backend/src/plugins/datatables/js/dataTables.responsive.min.js"></script>
<script src="/backend/src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
<script src="/extra-assets/jquery-ui-1.14.1/jquery-ui.min.js"></script>



<script>
    $(document).on('click', '#add_category_btn', function(e) {
        e.preventDefault();

        // Locate the modal
        var modal = $('body').find('div#category-modal');

        // Set modal title and button text
        var modal_title = 'Add Category';
        var modal_btn_text = 'ADD';

        // Update modal content
        modal.find('.modal-title').html(modal_title);
        modal.find('.modal-footer button.action').html(modal_btn_text);
        modal.find('input.error-text').html('');
        modal.find('input[type="text"]').val('');

        // Show the modal
        modal.modal('show');
    });

    $(document).on('submit', '#add-category-form', function(e) {
        e.preventDefault(); // Prevent default form submission

        let form = this;
        let formData = new FormData(form);

        $.ajax({
            url: $(form).attr('action'),
            method: $(form).attr('method'),
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function() {
                // Disable the submit button and show a loading state
                $(form).find('button[type="submit"]').attr('disabled', true).text('Submitting...');
                $(form).find('span.error-text').text(''); // Clear previous error messages
            },
            success: function(response) {
                // Update CSRF token
                $('input[name="<?= csrf_token() ?>"]').val(response.token);

                if (response.status === 1) {
                    // Show success SweetAlert
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.msg,
                        confirmButtonText: 'OK',
                    }).then(() => {
                        // Reset the form and hide the modal after confirmation
                        $(form)[0].reset();
                        $('#category-modal').modal('hide');
                        categories_DT.ajax.reload(null, false);
                        subcategoriesDT.ajax.reload(null. false);
                    });
                } else {
                    // Show error SweetAlert
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.msg,
                        confirmButtonText: 'OK',
                    });

                    // Show validation errors
                    if (response.error) {
                        $.each(response.error, function(prefix, val) {
                            $(form)
                                .find('span.' + prefix + '_error')
                                .text(val); // Display error messages
                        });
                    }
                }
            },
            error: function(xhr) {
                // Show unexpected error SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'An unexpected error occurred. Please try again.',
                    confirmButtonText: 'OK',
                });
                console.error(xhr.responseText);
            },
            complete: function() {
                // Re-enable the submit button in all cases
                $(form).find('button[type="submit"]').attr('disabled', false).text('Save Changes');
            },
        });
    });

    var categories_DT = $('#categories-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "<?= route_to('get-categories') ?>",
        dom: "Brtip",
        info: true,
        fnCreatedRow: function(row, data, index) {
            $('td', row).eq(0).html(index + 1); // Add row index in the first column
            // console.log(data);
            $('td', row).parent().attr('data-index', data[0]).attr('data-ordering', data[4]);
        },
        columnDefs: [{
                orderable: false, // Disable ordering on specified columns
                targets: [0, 1, 2, 3],
            },
            {
                visible: false, // Hide specific columns
                targets: 4,
            },
        ],
        order: [
            [4, 'asc']
        ], // Default ordering based on column 4 (ascending)
    });

    $(document).ready(function() {
        $(document).on('click', '.editCategoryBtn', function(e) {
            e.preventDefault();
            var category_id = $(this).data('id');
            var url = "<?= route_to('get-category') ?>"; // URL for the AJAX request

            $.get(url, {
                category_id: category_id
            }, function(response) {
                if (response.status === 1) {
                    // Populate modal with fetched data
                    var modal_title = 'Edit Category';
                    var modal_btn_text = 'Save Changes';
                    var modal = $('body').find('div#edit-category-modal');

                    modal.find('form input[type="hidden"][name="category_id"]').val(category_id);
                    modal.find('.modal-title').html(modal_title);
                    modal.find('.modal-footer > button.action').html(modal_btn_text);
                    modal.find('input[type="text"]').val(response.data.name);
                    modal.find('span.error-text').html(''); // Clear error messages
                    modal.modal('show');
                } else {
                    alert(response.msg); // Show error message
                }
            }, 'json');
        });
    });

    $('#update_category_form').on('submit', function(e) {
        e.preventDefault();

        var csrfName = $('.ci_csrf_data').attr('name'); // CSRF Token name
        var csrfHash = $('.ci_csrf_data').val(); // CSRF Token hash
        var form = this;
        var modal = $('body').find('div#edit-category-modal');
        var formData = new FormData(form);

        // Append CSRF token to the FormData object
        formData.append(csrfName, csrfHash);

        // AJAX request
        $.ajax({
            url: $(form).attr('action'), // Get form action URL
            method: $(form).attr('method'), // Get form method (POST/PUT)
            data: formData,
            processData: false, // Prevent jQuery from processing the data
            contentType: false, // Prevent jQuery from setting the Content-Type header
            dataType: 'json',
            cache: false, // Disable caching
            beforeSend: function() {
                $(form).find('span.error-text').text(''); // Clear previous error messages
                $(form).find('button[type="submit"]').attr('disabled', true).text('Saving...'); // Disable submit button
            },
            success: function(response) {
                // Update CSRF token in the form
                $('.ci_csrf_data').val(response.token);

                if ($.isEmptyObject(response.error)) {
                    if (response.status === 1) {
                        // Success: Hide modal and show SweetAlert success
                        modal.modal("hide"); // Close modal
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.msg,
                        }).then(() => {
                            categories_DT.ajax.reload(null, false); 
                            subcategoriesDT.ajax.reload(null. false);// Reload DataTable
                        });
                    } else {
                        // General error with message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.msg,
                        });
                    }
                } else {
                    // Validation errors
                    let errorMessages = '';
                    $.each(response.error, function(prefix, val) {
                        $(form).find('span.' + prefix + '_error').text(val); // Display specific field errors
                        errorMessages += val + '\n'; // Accumulate errors for alert
                    });

                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Errors',
                        text: errorMessages,
                    });
                }
            },
            error: function(xhr, status, error) {
                // Show generic error message
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred. Please try again.',
                });
                console.error(xhr.responseText); // Log error for debugging
            },
            complete: function() {
                // Re-enable submit button
                $(form).find('button[type="submit"]').attr('disabled', false).text('Save Changes');
            },
        });
    });

    $(document).on('click', '.deleteCategoryBtn', function() {
        const categoryId = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= route_to('delete-category') ?>',
                    method: 'POST',
                    data: {
                        category_id: categoryId,
                        [$('.ci_csrf_data').attr('name')]: $('.ci_csrf_data').val(),
                    },
                    success: function(response) {
                        // Update CSRF token
                        $('.ci_csrf_data').val(response.token);

                        if (response.status === 1) {
                            Swal.fire('Deleted!', response.msg, 'success');
                            $('#categories-table').DataTable().ajax.reload(); // Reload DataTable
                        } else {
                            Swal.fire('Error!', response.msg, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'An unexpected error occurred. Please try again.', 'error');
                        console.error(xhr.responseText); // Log error for debugging
                    },
                });
            }
        });
    });

    $('table#categories-table').find('tbody').sortable({
    update: function (event, ui) {
        // Collect updated positions
        var positions = [];
        $(this).children('tr').each(function (index) {
            var currentIndex = $(this).attr('data-index'); // Unique ID
            var newPosition = index + 1; // New position

            // Add the updated position to the array
            positions.push({
                id: currentIndex,
                ordering: newPosition
            });

            // Update the data-ordering attribute
            $(this).attr('data-ordering', newPosition);
        });

        // Use SweetAlert to confirm the reordering
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to save the new order?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, save it!',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                // Send the updated positions to the server
                var url = "<?= route_to('reorder-categories') ?>";

                $.ajax({
                    url: url,
                    method: 'POST', // Use POST for data modification
                    data: {
                        positions: positions, // Send positions array
                        [$('.ci_csrf_data').attr('name')]: $('.ci_csrf_data').val() // Include CSRF token
                    },
                    dataType: 'json',
                    success: function (response) {
                        // Update CSRF token
                        $('.ci_csrf_data').val(response.token);

                        if (response.status === 1) {
                            categories_DT.ajax.reload(null, false); // Reload DataTable
                            subcategoriesDT.ajax.reload(null. false);
                            Swal.fire('Saved!', response.msg, 'success');
                        } else {
                            Swal.fire('Error!', response.msg, 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error!', 'An unexpected error occurred. Please try again.', 'error');
                    }
                });
            } else {
                // If the user cancels, reset the rows to their original state
                categories_DT.ajax.reload(null, false); // Reload DataTable to original order
                subcategoriesDT.ajax.reload(null. false);
            }
        });
    }
});

$(document).on('click', '#add_subcategory_btn', function (e) {
    e.preventDefault();

    var modal = $('body').find('div#sub-category-modal');
    var select = modal.find('select[name="parent_cat"]');
    var url = "<?= route_to('get-parent-categories') ?>";

    // Fetch parent categories
    $.getJSON(url, function (response) {
        if (response.status === 1) {
            // Populate the parent category dropdown
            select.empty().html(response.data);
        } else {
            toastr.error(response.msg || 'Failed to load parent categories.');
        }
    });

    // Set modal content and show
    modal.find('.modal-title').html('Add Subcategory');
    modal.find('.modal-footer button.action').html('Add');
    modal.find('input[type="text"]').val(''); // Clear text inputs
    modal.find('textarea').val(''); // Clear textareas
    modal.find('span.error-text').html(''); // Clear error messages
    modal.modal('show');
});

$(document).on('submit', '#add-subcategory-form', function (e) {
    e.preventDefault();

    var form = this;
    var formData = new FormData(form);

    $.ajax({
        url: $(form).attr('action'),
        method: $(form).attr('method'),
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        beforeSend: function () {
            $(form).find('span.error-text').text(''); // Clear previous errors
            $(form).find('button[type="submit"]').attr('disabled', true).text('Submitting...');
        },
        success: function (response) {
            $('.ci_csrf_data').val(response.token); // Update CSRF token

            if (response.status === 1) {
                // Clear the form, hide the modal, and reload the DataTable
                $(form)[0].reset();
                $('#sub-category-modal').modal('hide');

                // Show success notification
                toastr.success(response.msg);

                // Reload the DataTable
                $('#subcategories-table').DataTable().ajax.reload(null, false); // Reload without resetting pagination
            } else if (response.error) {
                // Show validation errors
                $.each(response.error, function (key, val) {
                    $(form).find('span.' + key + '_error').text(val);
                });
            } else {
                toastr.error(response.msg || 'Something went wrong.');
            }
        },
        error: function (xhr) {
            toastr.error('An unexpected error occurred.');
            console.error(xhr.responseText);
        },
        complete: function () {
            $(form).find('button[type="submit"]').attr('disabled', false).text('Save Changes');
        },
    });
});




$(document).ready(function () {
    var subcategories_DT = $('#subcategories-table').DataTable({
        processing: true, // Show processing indicator
        serverSide: false, // Client-side processing
        ajax: {
            url: "<?= route_to('get-subcategories') ?>", // Endpoint to fetch data
            type: "GET",
            dataType: "json",
            error: function (xhr) {
                console.error("Error fetching subcategories: ", xhr.responseText);
            },
        },
        columns: [
            { data: 'ordering', title: '#' }, // Subcategory ID
            { data: 'name', title: 'Subcategory Name' }, // Subcategory name
            { data: 'parent_category', title: 'Parent Category' }, // Parent category
            { data: 'posts_count', title: 'No. of Post(s)' }, // Placeholder for posts
            { data: 'action', title: 'Action', orderable: false }, // Action buttons
            { data: 'id', title: 'ID' }, // Subcategory ID
            { data: 'ordering', title: 'Ordering' }, // Ordering
        ],
        columnDefs: [
            {
                targets: 6, // Index of the "Ordering" column
                visible: false, // Hide the column from view
            },
        ],
        order: [[0, 'asc']], // Default ordering by the first column
    });
});



$(document).on('click', '.editSubCategoryBtn', function (e) {
    e.preventDefault();

    var subcategoryId = $(this).data('id'); // Get the subcategory ID
    var url = "<?= route_to('get-subcategory') ?>"; // API endpoint

    $.get(url, { id: subcategoryId }, function (response) {
        if (response.status === 1) {
            // Populate the modal with the subcategory data
            $('#edit-subcategory-id').val(response.data.id);
            $('#edit-subcategory-name').val(response.data.name);
            $('#edit-parent-category').html(response.data.parent_categories); // Populate dropdown
            $('#edit-description').val(response.data.description);

            // Show the modal
            $('#edit-subcategory-modal').modal('show');
        } else {
            toastr.error(response.msg || 'Failed to fetch subcategory details.');
        }
    }).fail(function (xhr) {
        console.error('Error:', xhr.responseText);
        toastr.error('An error occurred while fetching subcategory details.');
    });
});


$(document).on('click', '.deleteSubCategoryBtn', function (e) {
    e.preventDefault();

    const subcategoryId = $(this).data('id');
    const url = "<?= route_to('delete-subcategory') ?>";

    Swal.fire({
        title: 'Are you sure?',
        text: 'This action cannot be undone!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
    }).then((result) => {
        if (result.isConfirmed) {
            $.post(url, { id: subcategoryId }, function (response) {
                if (response.status === 1) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: response.msg,
                    }).then(() => {
                        $('#subcategories-table').DataTable().ajax.reload(null, false); // Reload DataTable
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.msg || 'Failed to delete subcategory.',
                    });
                }
            }).fail(function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An unexpected error occurred. Please try again.',
                });
            });
        }
    });
});


// Handle Edit Subcategory Button Click
$(document).on('click', '.editSubCategoryBtn', function () {
    const subcategoryId = $(this).data('id'); // Get the subcategory ID
    if (!subcategoryId) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Subcategory ID is missing.',
        });
        return;
    }

    const url = "<?= route_to('get-subcategory') ?>"; // API endpoint

    // Show loading alert
    Swal.fire({
        title: 'Loading...',
        text: 'Fetching subcategory details, please wait.',
        icon: 'info',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });

    // Fetch subcategory details
    $.get(url, { subcategory_id: subcategoryId }, function (response) {
        if (response.status === 1) {
            Swal.close(); // Close the loading alert

            // Populate the modal with the subcategory data
            $('#edit-subcategory-id').val(response.data.id);
            $('#editSubcategoryName').val(response.data.name);
            $('#editDescription').val(response.data.description);

            // Populate parent category dropdown
            if (response.parent_categories) {
                $('#editParentCategory').html(response.parent_categories);
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: 'Parent categories could not be loaded.',
                });
            }

            // Show the modal
            $('#edit-sub-category-modal').modal('show');
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.msg || 'Failed to fetch subcategory details.',
            });
        }
    }).fail(function (xhr) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while fetching subcategory details.',
        });
        console.error('AJAX Error:', xhr.status, xhr.statusText);
        console.error('Response:', xhr.responseText);
    });
});




// Handle Edit Subcategory Form Submission
$('#edit-subcategory-form').on('submit', function (e) {
    e.preventDefault();

    const form = $(this);
    const url = form.attr('action'); // Update endpoint
    const formData = form.serialize();

    // Show loading alert
    Swal.fire({
        title: 'Saving...',
        text: 'Updating subcategory, please wait.',
        icon: 'info',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        },
    });

    // Submit the form via AJAX
    $.post(url, formData, function (response) {
        if (response.status === 1) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.msg,
            }).then(() => {
                $('#edit-sub-category-modal').modal('hide'); // Hide the modal
                $('#subcategories-table').DataTable().ajax.reload(null, false); // Reload DataTable
            });
        } else {
            Swal.close(); // Close the loading alert
            if (response.error) {
                $.each(response.error, function (key, val) {
                    form.find(`span.${key}_error`).text(val); // Show validation errors
                });
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Errors',
                    text: 'Please fix the highlighted errors and try again.',
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.msg || 'Failed to update subcategory.',
                });
            }
        }
    }).fail(function (xhr) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while updating the subcategory.',
        });
        console.error(xhr.responseText);
    });
});



</script>


<?= $this->endSection() ?>
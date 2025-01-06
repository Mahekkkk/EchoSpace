
<div class="modal fade" id="edit-category-modal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" data-backdrop="static" role="document">
        <form class="modal-content" action=" <?= route_to('update-category') ?>" method="post" id="update_category_form">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" id="categoryModalLabel">Add Category</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <input type ="hidden" name = "<?= csrf_token() ?>" value = "<?= csrf_hash() ?>" class="ci_csrf_data">
                <input type="hidden" name="category_id">
                <div class="form-group">
                    <label for="categoryName"><b>Category Name</b></label>
                    <input type="text"  class="form-control"  id="category_name"  name="category_name"  placeholder="Enter category name" required >
                    <span class="text-danger error-text category_name_error"></span>
                </div>
                <div class="error-text text-danger"></div>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary action">Save Changes</button>
            </div>
        </form>
    </div>
</div>

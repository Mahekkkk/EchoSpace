<div class="modal fade" id="sub-category-modal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content" action="<?= route_to('add-subcategory') ?>" method="post" id="add-subcategory-form">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" id="categoryModalLabel">Add Subcategory</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="ci_csrf_data">
                
                <!-- Parent Category Dropdown -->
                <div class="form-group">
                    <label for="parentCategory"><b>Parent Category</b></label>
                    <select name="parent_cat" id="parentCategory" class="form-control" required>
                        <option value="">Uncategorized</option>
                    </select>
                    <span class="text-danger error-text parent_cat_error"></span>
                </div>
                
                <!-- Subcategory Name -->
                <div class="form-group">
                    <label for="subcategoryName"><b>Subcategory Name</b></label>
                    <input type="text" id="subcategoryName" class="form-control" name="subcategory_name" placeholder="Enter subcategory name" required>
                    <span class="text-danger error-text subcategory_name_error"></span>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description"><b>Description</b></label>
                    <textarea id="description" name="description" class="form-control" cols="30" rows="3" placeholder="Enter description"></textarea>
                    <span class="text-danger error-text description_error"></span>
                </div>
            </div>
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary action">Save Changes</button>
            </div>
        </form>
    </div>
</div>

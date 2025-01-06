<div class="modal fade" id="edit-sub-category-modal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="modal-content" action="<?= route_to('update-subcategory') ?>" method="post" id="edit-subcategory-form">
            <div class="modal-header">
                <h4 class="modal-title">Edit Subcategory</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="subcategory_id" id="edit-subcategory-id">
                <div class="form-group">
                    <label for="editSubcategoryName">Subcategory Name</label>
                    <input type="text" class="form-control" id="editSubcategoryName" name="subcategory_name" required>
                    <span class="text-danger error-text subcategory_name_error"></span>
                </div>
                <div class="form-group">
                    <label for="editParentCategory">Parent Category</label>
                    <select class="form-control" id="editParentCategory" name="parent_cat" required>
                        <option value="">Select Parent Category</option>
                        <!-- Options will be dynamically populated -->
                    </select>
                    <span class="text-danger error-text parent_cat_error"></span>
                </div>

                <div class="form-group">
                    <label for="editDescription">Description</label>
                    <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
                    <span class="text-danger error-text description_error"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Subcategory</button>
            </div>
        </form>
    </div>
</div>
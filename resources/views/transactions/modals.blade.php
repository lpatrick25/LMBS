<div id="itemReleased" class="modal fade">
    <div class="modal-dialog">
        <form class="modal-content" id="releasedForm">
            <div class="modal-header">
                <h3 class="modal-title">Item Release</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="item_name">Item Name</label>
                            <input type="text" class="form-control" name="item_name" id="item_name" readonly>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class envie="form-group">
                            <label for="reserve_quantity">Reserved Quantity</label>
                            <input type="number" class="form-control" name="reserve_quantity" id="reserve_quantity"
                                readonly>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" class="form-control" name="quantity" id="quantity" min="1"
                                required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-md"><i class="fa fa-save"></i> Save</button>
                <button type="button" class="btn btn-danger btn-md" data-dismiss="modal"><i class="fa fa-times"></i>
                    Close</button>
            </div>
        </form>
    </div>
</div>
<div id="updateModal" class="modal fade">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <form class="modal-content" id="updateForm">
            <div class="modal-header">
                <h3 class="modal-title">Update Quantity</h3>
            </div>
            <div class="modal-body">
                <p class="form-control text-center" id="item_name"></p>
                <div class="form-group">
                    <label for="quantity">New Quantity: <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-md"><i class="fa fa-save"></i> Save</button>
                <button type="button" class="btn btn-danger btn-md" data-dismiss="modal"><i class="fa fa-times"></i>
                    Close</button>
            </div>
        </form>
    </div>
</div>
<div id="itemReturn" class="modal fade">
    <div class="modal-dialog">
        <form class="div modal-content" id="returnForm">
            <div class="modal-header">
                <h3 class="modal-title">Item Return</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
            </div>
            <div class="modal-body">
                <div id="status-msg"></div>
                <div class="row" id="item-status">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-md"><i class="fa fa-save"></i> Save</button>
                <button type="button" class="btn btn-danger btn-md" data-dismiss="modal"><i class="fa fa-times"></i>
                    Close</button>
            </div>
        </form>
    </div>
</div>
<div class="modal" id="itemModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Transaction Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Borrower Name:</strong> <span id="borrower_name" class="text-lime"></span></p>
                <table class="table table-bordered table-hover table-stripped" id="item_status_table">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Item Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows will be dynamically populated -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-md" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

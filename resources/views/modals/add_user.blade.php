
<div id="addModal" class="modal fade">
    <div class="modal-dialog">
        <form class="modal-content" id="addForm">
            <div class="modal-header">
                <h3 class="modal-title">Add User</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row" id="addModalContent">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="first_name">First Name: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="first_name" name="first_name">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="middle_name">Middle Name: <span class="text-danger"></span></label>
                            <input type="text" class="form-control" id="middle_name" name="middle_name">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="last_name">Last Name: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="last_name" name="last_name">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="extension_name">Extension Name: <span class="text-danger"></span></label>
                            <input type="text" class="form-control" id="extension_name" name="extension_name">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="contact_no">Contact Number: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="contact_no" name="contact_no" data-mask="(+63) 999-999-9999" placeholder="(+63)">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="email">Email Address: <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="username">Username: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="password">Password: <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password: <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-right">
                <button type="submit" class="btn btn-primary btn-md"><i class="fa fa-save"></i> Save</button>
                <button type="button" class="btn btn-danger btn-md" data-dismiss="modal"><i class="fa fa-times"></i>
                    Close</button>
            </div>
        </form>
    </div>
</div>

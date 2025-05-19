var transactionQuantity = 0;
var currentFieldsCount = 1;

function returnedItem(transaction_no) {
    $.ajax({
        method: "GET",
        url: `/transactions/getTransaction/${transaction_no}`,
        dataType: "JSON",
        cache: false,
        success: function (response) {
            if (response) {
                item_name = response.item_name;
                transactionQuantity = response.quantity;
                transactionNo = transaction_no;
                $("#status-msg").html("");
                $("#item-status").html("");
                const newIndex = currentFieldsCount;
                $("#item-status").append(`
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="item_name_${newIndex}">Item Name</label>
                            <input type="text" class="form-control" name="item_name_${newIndex}" id="item_name_${newIndex}" value="${item_name}" readonly>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="quantity${newIndex}">Borrowed Quantity</label>
                            <input type="number" class="form-control" name="quantity${newIndex}" id="quantity${newIndex}" value="${transactionQuantity}" readonly>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="qty_${newIndex}">Quantity</label>
                            <input type="number" class="form-control" name="qty_${newIndex}" id="qty_${newIndex}" min="1" max="${transactionQuantity}" value="${transactionQuantity}" required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="status_${newIndex}">Status</label>
                            <select name="status_${newIndex}" id="status_${newIndex}" class="form-control status-dropdown" data-index="${newIndex}">
                                <option value="Okay">Okay</option>
                                <option value="Lost">Lost</option>
                                <option value="Damaged">Damaged</option>
                                <option value="For Repair">For Repair</option>
                                <option value="For Disposal">For Disposal</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 replacement-option-field" id="replacement-option-container_${newIndex}" style="display: none;">
                        <div class="form-group">
                            <label for="replacement-option_${newIndex}">Replacement Option</label>
                            <select name="replacement-option_${newIndex}" id="replacement-option_${newIndex}" class="form-control replacement-option-dropdown" data-index="${newIndex}">
                                <option value="" selected disabled>Select Option</option>
                                <option value="Replace">Replace Item</option>
                                <option value="Pay">Pay</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 amount-field" id="amount-container_${newIndex}" style="display: none;">
                        <div class="form-group">
                            <label for="amount_${newIndex}">Amount</label>
                            <input type="number" class="form-control" name="amount_${newIndex}" id="amount_${newIndex}" min="0.0" value="0.0">
                        </div>
                    </div>
                `);
                currentFieldsCount++;
                $("select").chosen({ width: "100%" });
                $("#itemReturn")
                    .modal({ backdrop: "static", keyboard: false })
                    .modal("show");
            } else {
                showErrorMessage(response.msg);
            }
        },
        error: function (xhr, textStatus, error) {
            let errorMessage = "An error occurred. Please try again.";
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            showErrorMessage(errorMessage);
        },
    });
}

$(document).on("change", ".status-dropdown", function () {
    const selectedStatus = $(this).val();
    const index = $(this).data("index");
    const penaltyField = $(`#penalty-container_${index}`);
    const optionField = $(`#replacement-option-container_${index}`);
    const amountField = $(`#amount-container_${index}`);
    const amountInput = $(`#amount_${index}`);
    if (selectedStatus === "Lost" || selectedStatus === "Damaged") {
        penaltyField.show();
        optionField.show();
        amountField.hide();
        amountInput.val("");
        $(`#replacement-option_${index}`).val("").trigger("change");
    } else {
        penaltyField.hide();
        optionField.hide();
        amountField.hide();
        amountInput.val("");
    }
});

$(document).on("change", ".replacement-option-dropdown", function () {
    const selectedOption = $(this).val();
    const index = $(this).data("index");
    const amountField = $(`#amount-container_${index}`);
    const amountInput = $(`#amount_${index}`);
    if (selectedOption === "Pay") {
        amountField.show();
    } else {
        amountField.hide();
        amountInput.val("");
    }
});

function addNewFields() {
    const totalQty = calculateTotalQty();
    if (totalQty >= transactionQuantity) {
        alert("Maximum quantity reached.");
        return;
    }
    const currentQty = transactionQuantity - totalQty;
    const newIndex = currentFieldsCount;
    const newFieldHtml = `
        <div class="col-lg-6">
            <div class="form-group">
                <label for="qty_${newIndex}">Quantity</label>
                <input type="number" class="form-control" name="qty_${newIndex}" id="qty_${newIndex}"
                min="1" max="${currentQty}" value="${currentQty}" required>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for="status_${newIndex}">Status</label>
                <select name="status_${newIndex}" id="status_${newIndex}" class="form-control status-dropdown" data-index="${newIndex}">
                    <option value="Okay">Okay</option>
                    <option value="Lost">Lost</option>
                    <option value="Damaged">Damaged</option>
                    <option value="For Repair">For Repair</option>
                    <option value="For Disposal">For Disposal</option>
                </select>
            </div>
        </div>
        <div class="col-lg-6 replacement-option-field" id="replacement-option-container_${newIndex}" style="display: none;">
            <div class="form-group">
                <label for="replacement-option_${newIndex}">Replacement Option</label>
                <select name="replacement-option_${newIndex}" id="replacement-option_${newIndex}" class="form-control replacement-option-dropdown" data-index="${newIndex}">
                    <option value="" selected disabled>Select Option</option>
                    <option value="Replace">Replace Item</option>
                    <option value="Pay">Pay</option>
                </select>
            </div>
        </div>
        <div class="col-lg-6 amount-field" id="amount-container_${newIndex}" style="display: none;">
            <div class="form-group">
                <label for="amount_${newIndex}">Amount</label>
                <input type="number" class="form-control" name="amount_${newIndex}" id="amount_${newIndex}" min="0.0" value="0.0">
            </div>
        </div>
    `;
    $("#item-status").append(newFieldHtml);
    currentFieldsCount++;
    $("select").chosen({ width: "100%" });
}

function calculateTotalQty() {
    let totalQty = 0;
    for (let i = 0; i < currentFieldsCount; i++) {
        const qty = parseInt($(`#qty_${i}`).val()) || 0;
        totalQty += qty;
    }
    return totalQty;
}

$(document).on("input", "input[name^='qty_']", function () {
    const currentInput = $(this).val();
    const totalQty = calculateTotalQty();
    if (currentInput === "0") {
        $("#status-msg").html(
            '<div class="alert alert-warning">Quantity cannot be zero.</div>'
        );
        setTimeout(() => {
            $("#status-msg").html("");
        }, 5000);
        $(this).val(totalQty);
        return;
    }
    if (totalQty > transactionQuantity) {
        $("#status-msg").html(
            '<div class="alert alert-danger">The total quantity cannot exceed the borrowed quantity.</div>'
        );
        setTimeout(() => {
            $("#status-msg").html("");
        }, 5000);
        $(this).val("");
        return;
    }
    if (
        totalQty < transactionQuantity &&
        $(`#qty_${currentFieldsCount - 1}`).val() !== ""
    ) {
        addNewFields();
    }
});

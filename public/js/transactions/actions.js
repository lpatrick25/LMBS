var transactionNo;

function editTransaction(transaction_no) {
    $.ajax({
        method: "GET",
        url: `/transactions/${transaction_no}`,
        dataType: "JSON",
        cache: false,
        success: function (response) {
            if (response.valid) {
                transactionNo = transaction_no;
                showSuccessMessage(response.msg);
                $("#updateForm")
                    .find("p[id=item_name]")
                    .text(response.data.item_name);
                $("#updateForm")
                    .find("input[id=quantity]")
                    .val(response.quantity);
                $("#updateForm")
                    .find("input[id=quantity]")
                    .attr("max", response.data.current_qty);
                $("#updateModal").modal({
                    backdrop: "static",
                    keyboard: false,
                    show: true,
                });
            } else {
                showErrorMessage(response.msg);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            if (jqXHR.responseJSON && jqXHR.responseJSON.error) {
                var errors = jqXHR.responseJSON.error;
                var errorMsg = "Error submitting data: " + errors + ". ";
                showErrorMessage(errorMsg);
            } else {
                showErrorMessage(
                    "Something went wrong! Please try again later."
                );
            }
        },
    });
}

function cancelReserve(reserved_id) {
    Swal.fire({
        title: "Are you sure?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                method: "DELETE",
                url: `/transactions/${transaction_no}`,
                data: { status: "Cancelled" },
                dataType: "JSON",
                cache: false,
                success: function (response) {
                    if (response.valid) {
                        showSuccessMessage(response.msg);
                        table1.ajax.reload(null, false);
                    } else {
                        showErrorMessage(response.msg);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (jqXHR.responseJSON && jqXHR.responseJSON.error) {
                        var errors = jqXHR.responseJSON.error;
                        var errorMsg =
                            "Error submitting data: " + errors + ". ";
                        showErrorMessage(errorMsg);
                    } else {
                        showErrorMessage(
                            "Something went wrong! Please try again later."
                        );
                    }
                },
            });
        }
    });
}

function rejectedItem(reserved_id) {
    Swal.fire({
        title: "Are you sure?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                method: "DELETE",
                url: `/transactions/${transaction_no}`,
                data: { status: "Rejected" },
                dataType: "JSON",
                cache: false,
                success: function (response) {
                    if (response.valid) {
                        showSuccessMessage(response.msg);
                        table1.ajax.reload(null, false);
                    } else {
                        showErrorMessage(response.msg);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (jqXHR.responseJSON && jqXHR.responseJSON.error) {
                        var errors = jqXHR.responseJSON.error;
                        var errorMsg =
                            "Error submitting data: " + errors + ". ";
                        showErrorMessage(errorMsg);
                    } else {
                        showErrorMessage(
                            "Something went wrong! Please try again later."
                        );
                    }
                },
            });
        }
    });
}

function confirmedItem(transaction_no) {
    Swal.fire({
        title: "Are you sure?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                method: "DELETE",
                url: `/transactions/${transaction_no}`,
                data: { status: "Confirmed" },
                dataType: "JSON",
                cache: false,
                success: function (response) {
                    if (response.valid) {
                        showSuccessMessage(response.msg);
                        table1.ajax.reload(null, false);
                    } else {
                        showErrorMessage(response.msg);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (jqXHR.responseJSON && jqXHR.responseJSON.error) {
                        var errors = jqXHR.responseJSON.error;
                        var errorMsg =
                            "Error submitting data: " + errors + ". ";
                        showErrorMessage(errorMsg);
                    } else {
                        showErrorMessage(
                            "Something went wrong! Please try again later."
                        );
                    }
                },
            });
        }
    });
}

function releasedItem(transaction_no) {
    $.ajax({
        method: "GET",
        url: `/transactions/${transaction_no}`,
        dataType: "JSON",
        cache: false,
        success: function (response) {
            if (response) {
                transactionNo = transaction_no;
                $("#releasedForm")
                    .find("input[id=item_name]")
                    .val(response.data.item_name);
                $("#releasedForm")
                    .find("input[id=reserve_quantity]")
                    .val(response.quantity);
                $("#releasedForm")
                    .find("input[id=quantity]")
                    .val(response.quantity);
                $("#releasedForm")
                    .find("input[id=quantity]")
                    .attr("max", response.data.current_qty);
                $("#itemReleased")
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

function dontRelease(date_of_usage) {
    const today = new Date();
    const usageDate = new Date(date_of_usage);
    const todayFormatted = today.toISOString().split("T")[0];
    const differenceInTime = usageDate - today;
    const daysRemaining = Math.ceil(differenceInTime / (1000 * 60 * 60 * 24));
    Swal.fire({
        icon: "warning",
        title: "Cannot Release Item",
        html: `
            <p><strong>Date Now:</strong> ${todayFormatted}</p>
            <p><strong>Date of Usage:</strong> ${date_of_usage}</p>
            ${
                daysRemaining > 0
                    ? `<p>Item cannot be released until the date of usage. Please wait for <strong>${daysRemaining}</strong> day(s).</p>`
                    : `<p>The date of usage has already passed or is today!</p>`
            }
        `,
        confirmButtonText: "Okay",
    });
}

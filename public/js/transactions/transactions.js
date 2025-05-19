var table1;

$(document).ready(function () {
    table1 = $("#table1").DataTable({
        paging: true,
        lengthChange: false,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: true,
        responsive: true,
        ajax: { url: "/transactions", dataSrc: "data" },
        columns: [
            { data: "count" },
            { data: "transaction_no" },
            { data: "borrower_name" },
            { data: "item_name" },
            { data: "quantity" },
            { data: "date_of_usage" },
            { data: "date_of_return" },
            { data: "status" },
            { data: "action" },
        ],
        dom: '<"d-flex justify-content-between align-items-center"<"search-box"f><"custom-button"B>>rtip',
        buttons: [
            {
                text: '<i class="fa fa-plus-circle"></i> Add New',
                className: "btn btn-primary btn-md",
                action: function (e, dt, node, config) {
                    $.ajax({
                        method: "GET",
                        url: "/getTransactionByUser",
                        dataType: "JSON",
                        cache: false,
                        success: function (response) {
                            if (response.valid) {
                                showSuccessMessage(response.msg);
                                $("#title").text("Transaction");
                                $("#table-reserve").hide();
                                $("#addForm").show();
                                $("#addReserve").click();
                            } else {
                                showErrorMessage(response.msg);
                            }
                        },
                        error: function (jqXHR) {
                            if (jqXHR.status === 403) {
                                showErrorMessage(
                                    "Unauthorized access. Please check your permissions."
                                );
                            } else if (jqXHR.status === 404) {
                                showErrorMessage("No transactions available.");
                            } else {
                                showErrorMessage(
                                    "Something went wrong! Please try again later."
                                );
                            }
                        },
                    });
                },
            },
        ],
    });

    $("#table1 tbody").on("click", "tr", function (e) {
        var clickedCell = $(e.target);
        var columnIndex = clickedCell.index();
        var data = table1.row(this).data();
        if (columnIndex === 0 || data.status.includes("Pending")) {
            return;
        }
        $.ajax({
            method: "GET",
            url: `/transactions/getItemRemarks/${data.transaction_no}`,
            dataType: "JSON",
            cache: false,
            success: function (response) {
                if (response.valid) {
                    $("#borrower_name").text(response.fullName);
                    let statusTableRows = "";
                    response.transactionStatus.forEach((element) => {
                        let remarks = "";
                        if (element.status === "Damaged") {
                            remarks = "Return with issue";
                        } else if (element.status === "Lost") {
                            remarks = "Return with penalty";
                        }
                        statusTableRows += `
                            <tr>
                                <td>${element.item_name}</td>
                                <td>${element.quantity}</td>
                                <td>${element.status}</td>
                                <td>${remarks}</td>
                            </tr>
                        `;
                    });
                    $("#item_status_table tbody").html(statusTableRows);
                    $("#itemModal").modal({
                        backdrop: "static",
                        keyboard: false,
                        show: true,
                    });
                } else {
                    showErrorMessage(
                        response.msg || "Failed to fetch item details."
                    );
                }
            },
            error: function (jqXHR) {
                if (jqXHR.responseJSON && jqXHR.responseJSON.error) {
                    var errorMsg = `Error: ${jqXHR.responseJSON.error}`;
                    showErrorMessage(errorMsg);
                } else {
                    showErrorMessage(
                        "Something went wrong! Please try again later."
                    );
                }
            },
        });
    });

    $("#date_of_usage").on("change", function () {
        let usageDate = $(this).val();
        if (usageDate) {
            let date = new Date(usageDate);
            date.setDate(date.getDate() + 1);
            let returnDate = date.toISOString().split("T")[0];
            $("#date_of_return").val(returnDate);
            $("#date_of_return").attr("min", returnDate);
        } else {
            $("#date_of_return").val("");
            $("#date_of_return").attr("min", "");
        }
    });
});

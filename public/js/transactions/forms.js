$("#addForm").validate({
    rules: {
        user_id: { required: true },
    },
    messages: {
        user_id: { required: "Reserver's name is required." },
    },
    errorElement: "span",
    errorPlacement: function (error, element) {
        error.addClass("invalid-feedback");
        element.closest(".form-group").append(error);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass("is-invalid");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass("is-invalid");
    },
});

$("#updateForm").validate({
    rules: {
        category_name: { required: true },
        category_type: { required: true },
    },
    messages: {
        category_name: { required: "Reserve Name is required." },
        category_type: { required: "Reserve Type is required." },
    },
    errorElement: "span",
    errorPlacement: function (error, element) {
        error.addClass("invalid-feedback");
        element.closest(".form-group").append(error);
    },
    highlight: function (element, errorClass, validClass) {
        $(element).addClass("is-invalid");
    },
    unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass("is-invalid");
    },
});

// $("#addForm").submit(function (event) {
//     event.preventDefault();
//     $("#addForm").find("button[type=submit]").attr("disabled", true);
//     $("html, body").animate({ scrollTop: 0 }, 800);
//     if ($("#addForm").valid()) {
//         $("#addModal").modal("hide");
//         Swal.fire({
//             title: "Are you sure?",
//             icon: "question",
//             showCancelButton: true,
//             confirmButtonColor: "#3085d6",
//             cancelButtonColor: "#d33",
//             confirmButtonText: "Yes",
//             cancelButtonText: "No",
//         }).then((result) => {
//             if (result.isConfirmed) {
//                 let items = [];
//                 $("#reserve-content tr").each(function () {
//                     let itemId = $(this).find('select[name^="item_id"]').val();
//                     let quantity = $(this)
//                         .find('input[name^="quantity"]')
//                         .val();
//                     let dateOfUsage = $(this)
//                         .find('input[name^="date_of_usage"]')
//                         .val();
//                     let dateOfReturn = $(this)
//                         .find('input[name^="date_of_return"]')
//                         .val();
//                     let timeOfReturn = $(this)
//                         .find('input[name^="time_of_return"]')
//                         .val();
//                     if (
//                         itemId &&
//                         quantity &&
//                         dateOfUsage &&
//                         dateOfReturn &&
//                         timeOfReturn
//                     ) {
//                         items.push({
//                             item_id: itemId,
//                             quantity: quantity,
//                             date_of_usage: dateOfUsage,
//                             date_of_return: dateOfReturn,
//                             time_of_return: timeOfReturn,
//                         });
//                     }
//                 });
//                 let data = {
//                     user_id: $("#user_id").val(),
//                     items: items,
//                 };
//                 $.ajax({
//                     method: "POST",
//                     url: `/transactions`,
//                     data: JSON.stringify(data),
//                     contentType: "application/json",
//                     dataType: "JSON",
//                     cache: false,
//                     success: function (response) {
//                         if (response.valid) {
//                             $("#addForm")[0].reset();
//                             $("#title").text("Reserve List");
//                             $("#table-reserve").show();
//                             $("#addForm").hide();
//                             showSuccessMessage(response.msg);
//                             table1.ajax.reload(null, false);
//                             $("#addForm")[0].reset();
//                             $("#reserve-content").empty();
//                             counter = 1;
//                         } else {
//                             showErrorMessage(response.msg);
//                         }
//                     },
//                     error: function (jqXHR, textStatus, errorThrown) {
//                         if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
//                             let errors = jqXHR.responseJSON.errors;
//                             let errorMsg = `${jqXHR.responseJSON.msg}\n`;
//                             for (const [field, messages] of Object.entries(
//                                 errors
//                             )) {
//                                 errorMsg += `- ${messages.join(", ")}\n`;
//                             }
//                             showErrorMessage(errorMsg);
//                         } else if (
//                             jqXHR.responseJSON &&
//                             jqXHR.responseJSON.msg
//                         ) {
//                             showErrorMessage(jqXHR.responseJSON.msg);
//                         } else {
//                             showErrorMessage(
//                                 "An unexpected error occurred. Please try again."
//                             );
//                         }
//                     },
//                 });
//             }
//         });
//     }
//     $("#addForm").find("button[type=submit]").removeAttr("disabled");
// });

$("#addForm").submit(function (event) {
    event.preventDefault();
    $("#addForm").find("button[type=submit]").attr("disabled", true);
    $("html, body").animate({ scrollTop: 0 }, 800);

    let isFormValid = true;
    let items = [];

    $("#reserve-content tr").each(function () {
        let itemId = $(this).find('select[name^="item_id"]').val();
        let quantity = $(this).find('input[name^="quantity"]').val();
        let dateOfUsage = $(this).find('input[name^="date_of_usage"]').val();
        let dateOfReturn = $(this).find('input[name^="date_of_return"]').val();
        let timeOfReturn = $(this).find('input[name^="time_of_return"]').val();

        if (
            !itemId ||
            !quantity ||
            !dateOfUsage ||
            !dateOfReturn ||
            !timeOfReturn
        ) {
            isFormValid = false;
            $(this).addClass("table-danger"); // Highlight invalid row
        } else {
            $(this).removeClass("table-danger");
            items.push({
                item_id: itemId,
                quantity: quantity,
                date_of_usage: dateOfUsage,
                date_of_return: dateOfReturn,
                time_of_return: timeOfReturn,
            });
        }
    });

    // Also check if the user_id is set
    if (!$("#user_id").val()) {
        showErrorMessage("Borrower's name is required.");
        $("#addForm").find("button[type=submit]").removeAttr("disabled");
        return;
    }

    if (!isFormValid || items.length === 0) {
        showErrorMessage(
            "Please complete all fields in each row before submitting."
        );
        $("#addForm").find("button[type=submit]").removeAttr("disabled");
        return;
    }

    $("#addModal").modal("hide");
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
            let data = {
                user_id: $("#user_id").val(),
                items: items,
            };
            $.ajax({
                method: "POST",
                url: `/transactions`,
                data: JSON.stringify(data),
                contentType: "application/json",
                dataType: "JSON",
                cache: false,
                success: function (response) {
                    if (response.valid) {
                        $("#addForm")[0].reset();
                        $("#title").text("Reserve List");
                        $("#table-reserve").show();
                        $("#addForm").hide();
                        showSuccessMessage(response.msg);
                        table1.ajax.reload(null, false);
                        $("#reserve-content").empty();
                        counter = 1;
                    } else {
                        showErrorMessage(response.msg);
                    }
                },
                error: function (jqXHR) {
                    if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                        let errorMsg = `${jqXHR.responseJSON.msg}\n`;
                        for (const [field, messages] of Object.entries(
                            jqXHR.responseJSON.errors
                        )) {
                            errorMsg += `- ${messages.join(", ")}\n`;
                        }
                        showErrorMessage(errorMsg);
                    } else if (jqXHR.responseJSON?.msg) {
                        showErrorMessage(jqXHR.responseJSON.msg);
                    } else {
                        showErrorMessage(
                            "An unexpected error occurred. Please try again."
                        );
                    }
                },
            });
        }
    });

    $("#addForm").find("button[type=submit]").removeAttr("disabled");
});

$("#updateForm").submit(function (event) {
    event.preventDefault();
    $("#updateForm").find("button[type=submit]").attr("disabled", true);
    if ($("#updateForm").valid()) {
        $("#updateModal").modal("hide");
        Swal.fire({
            title: "Are you sure you?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method: "PUT",
                    url: `/transactions/${transactionNo}`,
                    data: $("#updateForm").serialize(),
                    dataType: "JSON",
                    cache: false,
                    success: function (response) {
                        if (response.valid) {
                            $("#updateForm")[0].reset();
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
    $("#updateForm").find("button[type=submit]").removeAttr("disabled");
});

$("#releasedForm").submit(function (event) {
    event.preventDefault();
    $("#releasedForm").find("button[type=submit]").attr("disabled", true);
    if ($("#releasedForm").valid()) {
        $("#itemReleased").modal("hide");
        Swal.fire({
            title: "Are you sure you?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method: "PUT",
                    url: `/transactions/releaseTransaction/${transactionNo}`,
                    data: $("#releasedForm").serialize(),
                    dataType: "JSON",
                    cache: false,
                    success: function (response) {
                        if (response.valid) {
                            $("#releasedForm")[0].reset();
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
    $("#releasedForm").find("button[type=submit]").removeAttr("disabled");
});

$("#returnForm").submit(function (event) {
    event.preventDefault();
    $("#returnForm").find("button[type=submit]").attr("disabled", true);
    if ($("#returnForm").valid()) {
        $("#itemReturn").modal("hide");
        Swal.fire({
            title: "Are you sure you?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method: "PUT",
                    url: `/transactions/returnTransaction/${transactionNo}`,
                    data: $("#returnForm").serialize(),
                    dataType: "JSON",
                    cache: false,
                    success: function (response) {
                        if (response.valid) {
                            $("#returnForm")[0].reset();
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
    $("#returnForm").find("button[type=submit]").removeAttr("disabled");
});

let counter = 1;
let selectedItems = [];
let remainingItems = 0;

$("#cancel-btn").click(function (event) {
    event.preventDefault();
    $("#title").text("Reserve List");
    $("#table-reserve").show();
    $("#addForm").hide();
    $("#reserve-content").empty();
    $("#remaining-count").text("0");
    remainingItems = 0;
});

$("#addReserve").on("click", function () {
    $.ajax({
        method: "GET",
        url: "/getTransactionByUser",
        dataType: "JSON",
        cache: false,
        success: function (response) {
            if (!response.valid) {
                showErrorMessage(response.msg);
                return;
            }

            let remainingItems = response.remaining || 5;
            let currentCount = counter - 1;
            let allowedRemaining = remainingItems - currentCount;

            if (
                counter >= 6 ||
                remainingItems === 0 ||
                currentCount >= remainingItems
            ) {
                $("html, body").animate({ scrollTop: 0 }, "slow");
                showErrorMessage(
                    "You have reached the limit of items to reserve."
                );
                return;
            }

            showSuccessMessage(response.msg);

            let itemNo = String(counter).padStart(2, "0");
            let today = new Date().toISOString().split("T")[0];

            // This part should be rendered server-side and inserted into the page as a template string or loaded via AJAX
            // Simulating with a placeholder, replace with actual item options in production
            let itemOptions = $("#item-options-template").html(); // assumes you have this in a hidden <script> or <template> tag

            let newRow = `
                <tr id="reserve-row-${counter}">
                    <td>${itemNo}</td>
                    <td>
                        <select class="form-control item-select" id="item_id-${counter}" name="item_id-${counter}">
                            <option value="" disabled selected>Select Item</option>
                            ${itemOptions}
                        </select>
                    </td>
                    <td><input type="number" name="quantity-${counter}" id="quantity-${counter}" class="form-control" min="1"></td>
                    <td><input type="date" name="date_of_usage-${counter}" id="date_of_usage-${counter}" class="form-control" min="${today}"></td>
                    <td><input type="date" name="date_of_return-${counter}" id="date_of_return-${counter}" class="form-control" min="${today}"></td>
                    <td><input type="time" name="time_of_return-${counter}" id="time_of_return-${counter}" class="form-control"></td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm btn-remove-reserve btn-elegant" data-id="${counter}">
                            <i class="fa fa-times"></i>
                        </button>
                    </td>
                </tr>`;

            $("#reserve-content").append(newRow);

            $(`#date_of_usage-${counter}`).on("change", function () {
                let usageDate = $(this).val();
                if (usageDate) {
                    let date = new Date(usageDate);
                    date.setDate(date.getDate() + 1);
                    let returnDate = date.toISOString().split("T")[0];
                    $(`#date_of_return-${counter}`)
                        .val(returnDate)
                        .attr("min", returnDate);
                } else {
                    $(`#date_of_return-${counter}`).val("").attr("min", "");
                }
            });

            counter++;
            $("#remaining-count").text(remainingItems - (counter - 1));
            $(`select#item_id-${counter - 1}`).chosen({ width: "100%" });
            addDynamicValidation(counter);
            updateDropdownOptions();
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
});

$(document).on("click", ".btn-remove-reserve", function () {
    let rowId = $(this).data("id");
    let removedItem = $(`#item_id-${rowId}`).val();
    $(`#reserve-row-${rowId}`).remove();
    $(`#item_id-${rowId}`).rules("remove");
    $(`#quantity-${rowId}`).rules("remove");
    $(`#date_of_usage-${rowId}`).rules("remove");
    $(`#date_of_return-${rowId}`).rules("remove");
    $(`#time_of_return-${rowId}`).rules("remove");
    if (removedItem) {
        selectedItems = selectedItems.filter((item) => item !== removedItem);
    }
    counter--;
    $("#remaining-count").text(remainingItems - (counter - 1));
    updateDropdownOptions();
});

$(document).on("change", 'select[name^="item_id-"]', function () {
    let selectedValue = $(this).val();
    let oldValue = $(this).data("old-value");
    if (oldValue) {
        selectedItems = selectedItems.filter((item) => item !== oldValue);
    }
    if (selectedValue) {
        selectedItems.push(selectedValue);
    }
    $(this).data("old-value", selectedValue);
    updateDropdownOptions();
});

function updateDropdownOptions() {
    $('#reserve-content select[name^="item_id-"]').each(function () {
        let currentValue = $(this).val();
        $(this)
            .find("option")
            .each(function () {
                let optionValue = $(this).val();
                if (
                    selectedItems.includes(optionValue) &&
                    optionValue !== currentValue
                ) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
        $(this).trigger("chosen:updated");
    });
}

function addDynamicValidation(counter) {
    $(`#item_id-${counter}`).rules("add", {
        required: true,
        messages: { required: "Item selection is required." },
    });
    $(`#quantity-${counter}`).rules("add", {
        required: true,
        digits: true,
        min: 1,
        messages: {
            required: "Quantity is required.",
            digits: "Please enter a valid number.",
            min: "Quantity must be at least 1.",
        },
    });
    $(`#date_of_usage-${counter}`).rules("add", {
        required: true,
        date: true,
        messages: {
            required: "Date of usage is required.",
            date: "Please enter a valid date.",
        },
    });
    $(`#date_of_return-${counter}`).rules("add", {
        required: true,
        date: true,
        messages: {
            required: "Date of return is required.",
            date: "Please enter a valid date.",
        },
    });
    $(`#time_of_return-${counter}`).rules("add", {
        required: true,
        messages: {
            required: "Time of return is required.",
        },
    });
}

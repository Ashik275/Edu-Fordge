$("#calssCreateForm").submit(function (e) {
    e.preventDefault();
    var form = $(this);
    var element = form.serializeArray();
    var storeUrl = form.data("store-url"); // Retrieve the store URL
    var indexUrl = form.data("index-url"); // Retrieve the index URL
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    form.find(".is-invalid")
        .removeClass("is-invalid")
        .siblings(".invalid-feedback")
        .remove();

    $.ajax({
        url: storeUrl,
        type: "post",
        data: element,
        dataType: "json",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        success: function (res) {
            if (res["status"] == false) {
                // Iterate through errors object and display error message for each field
                $.each(res.errors, function (field, messages) {
                    var inputField = form.find('[name="' + field + '"]');
                    var errorMessage =
                        '<span class="invalid-feedback" role="alert">' +
                        messages.join("<br>") +
                        "</span>";
                    inputField.addClass("is-invalid").after(errorMessage);
                });

                // Hide error messages after 3 seconds
                setTimeout(function () {
                    form.find(".is-invalid")
                        .removeClass("is-invalid")
                        .siblings(".invalid-feedback")
                        .fadeOut(300, function () {
                            $(this).remove();
                        });
                }, 3000);
            }
            if (res["status"] == true) {
                window.location.href = indexUrl;
            }
        },
        error: function (jqXHR, exception) {
            console.log("wrong");
        },
    });
});
function classUpdate(id) {
    // $('#exampleModal').modal('show');\
    var route = document.getElementById("edit-class-route").dataset.route;
    var url = route.replace("ID", id);
    $.ajax({
        url: url,
        type: "get",
        success: function (res) {
            console.log(res);
            $("#exampleModal").remove();
            $("body").append(res);
            $("#exampleModal").modal("show");
        },
    });
}
function error(res,form){
    $.each(res.errors, function(field, messages) {
        var inputField = form.find('[name="' + field + '"]');
        var errorMessage =
            '<span class="invalid-feedback" role="alert">' +
            messages.join("<br>") +
            "</span>";
        inputField.addClass("is-invalid").after(errorMessage);
    });

    // Hide error messages after 3 seconds
    setTimeout(function() {
        form.find(".is-invalid")
            .removeClass("is-invalid")
            .siblings(".invalid-feedback")
            .fadeOut(300, function() {
                $(this).remove();
            });
    }, 3000);
}

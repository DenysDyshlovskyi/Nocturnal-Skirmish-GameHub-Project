// JavaScript file for error report page

// updates characher limit viewer (0-500/500)
$("#what-happened-textarea").keyup(function(){
    $("#textarea-lenght-counter").text($(this).val().length + "/500");
});

// Shows details of a category when a category is selected
function showSelectDetails(description) {
    var descriptionContainer = document.getElementById("category-details");
    descriptionContainer.style.display = "block";
    descriptionContainer.innerHTML = description;
}

// Shows preview of screenshot that is uploaded
function preview() {
    document.getElementById('media-preview').src=URL.createObjectURL(event.target.files[0]);
    document.getElementById('media-preview').style.display = "block";
    document.getElementById('media-preview-container').style.display = "block";
    document.getElementById('screenshot-input').style.display = "none";
}

// Cancels screenshot upload
function cancelScreenshot() {
    document.getElementById('media-preview').src = "";
    document.getElementById('screenshot-input').value = "";
    document.getElementById('screenshot-input').style.display = "block";
    document.getElementById('media-preview').style.display = "none";
    document.getElementById('media-preview-container').style.display = "none";
    resizeMessageBar()
}

$("form#error-report-form").submit(function(e) {
    e.preventDefault();    
    var formData = new FormData(this);
    waitClick();

    $.ajax({
        url: './php_scripts/send_error_report.php',
        type: 'POST',
        data: formData,
        success: function (response) {
            stopWaitClick();
            if (response == "empty") {
                showConfirm("One or more inputs are empty");
            } else if (response == "unsupported") {
                showConfirm("Unsupported file format! Only JPG or PNG allowed!");
            } else if (response == "error") {
                showConfirm("Something went wrong.");
            } else if (response == "toolarge") {
                showConfirm("Attachment exceeds 3MB! Please upload smaller file.")
                cancelScreenshot();
            } else if (response == "toolong") {
                showConfirm("Input is too long! Character limit is 500.");
            } else if (response == "toomanysubmits") {
                showConfirm("You have sent a report very recently. Please wait until later to send another.")
            } else {
                document.getElementById("error-report-body").innerHTML = "";
                ajaxGet("./spa/error_report/error_submitted.php", "error-report-body", "no_sfx");
            }
        },
        cache: false,
        contentType: false,
        processData: false,
        error: function() {
            showConfirm("Something went wrong.");
            stopWaitClick();
        }
    });
});
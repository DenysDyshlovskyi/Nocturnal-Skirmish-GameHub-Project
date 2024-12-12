<?php
session_start();

// Redirect user if theyre not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ./index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub & Nocturnal Skirmish - Report errors</title>
    <link rel="icon" type=".image/x-icon" href="./img/favicon.png">
    <style> <?php include "./css/universal.css" ?> </style>
    <style> <?php include "./css/error-report.css" ?> </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body id="error-report-body">
    <div id="wait-container" class="wait-container">
        <div class="wait-container-inner">Please wait...</div>
    </div>
    <div class="confirmation-popup" id="confirmContainer"></div>
    <div class="error-report-container">
        <button class="backtohub-button" title="Back to hub" onclick="window.location.href='hub.php'">Back to hub</button>
        <h1>Report errors found on GameHub & Nocturnal Skirmish</h1>
        <div class="headline-divider"></div>
        <form action="" method="POST" id="error-report-form"></form>
            <p>Category*</p>
            <select form="error-report-form" required name="category">
                <option value="" disabled selected>Select a category</option>
                <option value="error_message" onclick="showSelectDetails(this.title)" title="Error message: Choose this option if you tried an action and got an error message like 'Something went wrong.' or a code like 404, 500 ect." value="error_message">Error message</option>
                <option value="got_stuck" onclick="showSelectDetails(this.title)" title="Got Stuck: Choose this option if you tried an action and got stuck on a loading screen or you pressed a button and nothing happened." value="got_stuck">Got Stuck</option>
                <option value="design_flaw" onclick="showSelectDetails(this.title)" title="Design Flaw: Choose this option if you came across a design flaw, like something clipping out of a box, a button that is too big or small, if you cant scroll etc." value="design_flaw">Design Flaw</option>
                <option value="other" onclick="showSelectDetails(this.title)" title="Other: Choose this option if none of the above apply" value="other">Other</option>
            </select>
            <div class="category-details" id="category-details"></div>
            <p id="what-happened">What happened and what page were you on?*</p>
            <div class="textarea-container" >
                <textarea id="what-happened-textarea" form="error-report-form" required placeholder="Write here" name="what-happened-textarea" maxlength="500"></textarea>
                <div id="textarea-lenght-counter">0/500</div>
            </div>
            <p id="provide_screenshot">Please provide a screenshot of the incident if possible (max file size: 3MB, jpg or png):</p>
            <div class="screenshot-preview-container" id="media-preview-container">
                <button class="cancel-screenshot-button" title="Cancel screenshot upload" onclick="cancelScreenshot()"></button>
                <img id="media-preview"/>
            </div>
            <input id="screenshot-input" type="file" name="media-upload" form="error-report-form" onchange="preview()" accept="image/png, image/jpeg">
            <br>
            <button class="submit-error-report-button" type="submit" title="Submit error report" form="error-report-form">Submit error report</button>
    </div>
</body>
<script type="text/javascript"><?php include "./js/script.js" ?></script>
<script><?php include "./js/error_report.js" ?></script>
</html>
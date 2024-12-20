/* JavaScript file for server settings page */

// Config for chart js for cpu usage
const cpu_canvas = document.getElementById('cpu_chart');

const cpu_labels = [
    '%',
];

const cpu_data = {
    labels: cpu_labels,
    datasets: [{
        label: '%',
        data: [0],
        borderWidth: 1
    }]
};

const cpu_config = {
  type: 'line',
  data: cpu_data,
  options: {responsive: false,
    plugins: {
        legend: {
            display: false,
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            min: 0,
            max: 100,
        },
        x: {
            display: false,
        }
    }}
};

const cpuChart = new Chart(
    cpu_canvas,
    cpu_config
);

// Config for chart js for ram usage
const ram_canvas = document.getElementById('ram_chart');

const ram_labels = [
    'GB',
];

const ram_data = {
    labels: ram_labels,
    datasets: [{
        label: 'GB',
        data: [0],
        borderWidth: 1,
        borderColor: 'rgb(210, 4, 45)',
    }]
};

const ram_config = {
  type: 'line',
  data: ram_data,
  options: {responsive: false,
    plugins: {
        legend: {
            display: false,
        }
    },
    scales: {
        y: {
            beginAtZero: true
        },
        x: {
            display: false,
        }
    }}
};

const ramChart = new Chart(
    ram_canvas,
    ram_config
);

/*Function for pushing data to a chart*/
function addData(chart, label, data) {
    chart.data.labels.push(label);
    chart.data.datasets.forEach((dataset) => {
        dataset.data.push(data);
    });
    chart.update();
}

/*Updates charts on load*/
function loadCharts() {
    $.get("./scripts/get_cpu_usage.php", function(usage){
        addData(cpuChart, '%', usage);
        document.getElementById("cpu-current-p").innerHTML = "CPU | Current usage: " + usage + "%"
    });
    $.get("./scripts/get_ram_usage.php", function(usage){
        newusage = usage.replace(",", ".")
        addData(ramChart, 'GB', newusage);
        document.getElementById("ram-current-p").innerHTML = "RAM | Current usage: " + newusage + "GB"
    });
}

// Does a git pull
function gitPull() {
    $.get("./scripts/git_pull.php", function(){
        showConfirm("Attempting git pull");
    });
}

// Interval to update charts in real time
setInterval(function() {
    loadCharts()
}, 3000);

//Shows a popup when saving information in user settings
function showConfirm(text) {
    $("#confirmContainer").stop( true, true ).fadeOut();
    confirmContainer = document.getElementById("confirmContainer");
    confirmContainer.innerHTML = text;
    confirmContainer.style.display = "block";
    $("#confirmContainer").fadeOut(3000);
}

// GET request with ajax
function ajaxGet(phpFile, changeID){
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function(){
        document.getElementById(changeID).innerHTML = this.responseText;
        if (changeID == "dark-container") {
            $("#dark-container").fadeIn(100);
        }
    }
    xhttp.open("GET", phpFile);
    xhttp.send();
}

// Function for downloading files or accessing directories
function adminDownloadFile(file, type) {
    switch(type) {
        case "folder":
            $.ajax({
                type: "POST",
                url: './scripts/set_folder.php',
                data:{ folder : file }, 
                success: function(response){
                    if (response == "error") {
                        showConfirm("Something went wrong");
                    } else {
                        ajaxGet('./scripts/admin_loadfiles.php', 'file-explorer-file-container');
                    }
                },
                error: function() {
                    showConfirm("Something went wrong.");
                }
            })
            break
        case "file":
            $.ajax({
                type: "POST",
                url: './scripts/download_file.php',
                data:{ file : file }, 
                success: function(response){
                    if (response == "error") {
                        showConfirm("Something went wrong.")
                    } else {
                        document.getElementById("sourceCodeContainer").innerHTML = response;
                        document.getElementById("sourceCodeContainer").style.display = "block";
                    }
                },
                error: function(xhr, status, error) {
                    // Alert detailed error information
                    alert("Error details:\n" +
                        "Status: " + status + "\n" +
                        "Error: " + error + "\n" +
                        "Response Text: " + xhr.responseText);
                    
                    // Optionally, log the error for debugging
                    console.error("Error Details:", xhr, status, error);
                }
            })
            break
    }
}

// Goes back to previous folder
function adminPreviousDir() {
    $.get("./scripts/set_previousfolder.php", function(){
        ajaxGet('./scripts/admin_loadfiles.php', 'file-explorer-file-container');
    });
}

// Removes sourcecode container
function removeSourceCode() {
    document.getElementById("sourceCodeContainer").innerHTML = "";
    document.getElementById("sourceCodeContainer").style.display = "none";
}

// Resets current path
function resetPath() {
    $.get("./scripts/reset_path.php", function(){
        ajaxGet('./scripts/admin_loadfiles.php', 'file-explorer-file-container');
    });
}
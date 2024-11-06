<?
// Checks if user trying to log in is banned.
$stmt = $conn->prepare("SELECT * FROM banned WHERE user_id = ? OR ip = ? LIMIT 1");
$stmt->bind_param("ss", $_SESSION['user_id'], $ip);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows > 0){
    $row = $result->fetch_assoc();
    $showError = true;
    $errorMessage = "You have been banned! Expires: " . $row['duration'] . "<button>Show reason</button>";

    $_SESSION['user_id'] = "banned";
} else {
    header("Location: ./hub.php");
}
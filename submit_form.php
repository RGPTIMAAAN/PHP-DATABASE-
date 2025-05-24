<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1. Connect to MySQL
$servername = "localhost";
$username = "root";
$password = ""; // default WAMP password is empty
$database = "lending";

$conn = new mysqli($servername, $username, $password, $database);

// 2. Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 3. Get Co-Maker data from POST
$comakerName = $_POST['comaker-name'];
$comakerOccupation = $_POST['comaker-occupation'];
$comakerAddress = $_POST['comaker-address'];
$comakerMobile = $_POST['comaker-mobile'];

// 4. Insert into comakers table
$comakerSQL = "INSERT INTO comakers (name, occupation, address, mobile) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($comakerSQL);
$stmt->bind_param("ssss", $comakerName, $comakerOccupation, $comakerAddress, $comakerMobile);
$stmt->execute();

if ($stmt->error) {
    die("Error inserting co-maker: " . $stmt->error);
}

$comaker_id = $stmt->insert_id;
$stmt->close();

// 5. Get Borrower data
$borrowerName = $_POST['borrower-name'];
$borrowerOccupation = $_POST['borrower-occupation'];
$borrowerAddress = $_POST['borrower-address'];
$borrowerMobile = $_POST['borrower-mobile'];
$borrowerMessenger = isset($_POST['borrower-messenger']) ? $_POST['borrower-messenger'] : null;
$borrowerGmail = isset($_POST['borrower-email']) ? $_POST['borrower-email'] : null;

// 6. Insert into borrowers table
$borrowerSQL = "INSERT INTO borrowers (name, occupation, address, mobile, messenger, gmail, comaker_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($borrowerSQL);
$stmt->bind_param("ssssssi", $borrowerName, $borrowerOccupation, $borrowerAddress, $borrowerMobile, $borrowerMessenger, $borrowerGmail, $comaker_id);
$stmt->execute();

if ($stmt->error) {
    die("Error inserting borrower: " . $stmt->error);
}

$stmt->close();
$conn->close();

echo "
    <div style='font-family: Arial, sans-serif; text-align: center; margin-top: 50px;'>
        <h2 style='color: green;'>Application for <span style='color: black;'>".htmlspecialchars($borrowerName)."</span> submitted successfully!</h2>
        <p><a href='Loan.php?borrowerName=".urlencode($borrowerName)."&borrowerOccupation=".urlencode($borrowerOccupation)."' style='text-decoration: none; color: #007BFF;'>
            Click here to go back to the Loan Application page
        </a></p>
    </div>
";
?> 

<?php
session_start();
$name = $_POST['name'];
$date = $_POST['date'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password'];

$conn = new mysqli('localhost', 'root', '', 'test');
$successMessage="Details submitted...Successfull redirecting to login page....";
$loginMessage="Email is already registered . Please go to sigin page , automatically redirecting...";
if ($conn->connect_error) {
    echo "$conn->connect_error";
    die("Connection Failed: " . $conn->connect_error);
} else {
    // Check if the email or phone number is already registered
    $stmt = $conn->prepare("SELECT * FROM signuptab WHERE email = ? OR phone = ?");
    $stmt->bind_param("ss", $email, $phone);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows > 0) {
        // If user is already registered, redirect to Signin page
        
        if(isset($loginMessage) && !empty($loginMessage)) {
            echo '<div>' . $loginMessage . '</div>';
            echo '<script>';
            echo 'setTimeout(function() {';
            echo 'window.location.href = "signin.html";';
            echo '}, 2000);'; // Redirect after 2 seconds
            echo '</script>';
          }
        #header("Location: signin.html");
        exit();
    } else {
        // If user is not registered, proceed with registration
        $stmt = $conn->prepare("INSERT INTO signuptab (name, date, email, phone, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $date, $email, $phone, $password);
        $stmt->execute();
        echo 'Registered Successfully...';


        if(isset($successMessage) && !empty($successMessage)) {
            echo '<div>' . $successMessage . '</div>';
            echo '<script>';
            echo 'setTimeout(function() {';
            echo 'window.location.href = "signin.html";';
            echo '}, 2000);'; // Redirect after 2 seconds
            echo '</script>';
          }}
        $stmt->close();
        $conn->close();
    }

?>
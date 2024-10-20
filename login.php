<?php
// Connect to the database
$host = 'localhost';  // Database host
$db = 'your_database';  // Database name
$user = 'your_username';  // Database user
$pass = 'your_password';  // Database password

// Create a connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the query to check user credentials
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the user exists, verify the password
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Assuming passwords are hashed using password_hash() function
        if (password_verify($password, $row['password'])) {
            // Password matches, redirect to another page
            header("Location: blood.html");
            exit();
        } else {
            // Incorrect password
            echo "<script>alert('Incorrect password. Please try again.');</script>";
        }
    } else {
        // Username not found
        echo "<script>alert('Username not found.');</script>";
    }

    // Close statement and connection
    $stmt->close();
}

$conn->close();
?>

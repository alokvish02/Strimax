<?php
include 'config.php';
// Create inquiries table if not exists
$createTableSQL = "CREATE TABLE IF NOT EXISTS inquiries (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($createTableSQL)) {
    die("Error creating table: " . htmlspecialchars($conn->error));
}

// Process form data
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate and sanitize input
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    // Basic validation
    $errors = [];
    if (empty($name)) $errors[] = "Name is required";
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    if (empty($message)) $errors[] = "Message is required";

    if (!empty($errors)) {
        http_response_code(400);
        die(implode("<br>", $errors));
    }

    // Prepare and execute SQL statement
    $stmt = $conn->prepare("INSERT INTO inquiries (name, email, message) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("sss", $name, $email, $message);
    
    if ($stmt->execute()) {
        // Success - redirect back with success message
        header("Location: ../success.html");
        exit();
    } else {
        http_response_code(500);
        die("Error submitting inquiry: " . htmlspecialchars($stmt->error));
    }

    $stmt->close();
}

$conn->close();
?>
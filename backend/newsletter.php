<?php
// Include database configuration
require_once __DIR__ . '/config.php';

// Create newsletter table if not exists
$createTableSQL = "CREATE TABLE IF NOT EXISTS newsletter (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    agreed TINYINT(1) NOT NULL DEFAULT 0,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($createTableSQL)) {
    die("Error creating table: " . htmlspecialchars($conn->error));
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate inputs
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $agreed = isset($_POST['save-data']) ? 1 : 0;

    // Error messages array
    $errors = [];

    // Email validation
    if (empty($email)) {
        $errors[] = "Email address is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }


    // Handle errors
    if (!empty($errors)) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=" . urlencode(implode(", ", $errors)));
        exit();
    }

    // Insert into database
    try {
        $stmt = $conn->prepare("INSERT INTO newsletter (email, agreed) VALUES (?, ?)");
        $stmt->bind_param("si", $email, $agreed);
        
        if ($stmt->execute()) {
            header("Location: ../success.html");
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() === 1062) {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=" . urlencode("This email is already subscribed"));
        } else {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=" . urlencode("Database error: " . $e->getMessage()));
        }
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
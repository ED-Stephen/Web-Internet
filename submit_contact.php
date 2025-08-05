<?php
// submit_contact.php
require_once 'config.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    $errors = [];

    // Server-side validation
    if (empty($name)) {
        $errors[] = "Name is required.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($message)) {
        $errors[] = "Message is required.";
    }

    // If there are no validation errors, proceed with database insertion
    if (empty($errors)) {
        // Prepare an insert statement
        $sql = "INSERT INTO contact_messages (sender_name, sender_email, subject, message) VALUES (?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssss", $param_name, $param_email, $param_subject, $param_message);

            // Set parameters
            $param_name = $name;
            $param_email = $email;
            $param_subject = $subject; // Can be an empty string if not provided
            $param_message = $message;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Success message page (instead of alert())
                echo "<!DOCTYPE html><html><head><title>Message Sent</title><style>body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; text-align: center; } .container { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); max-width: 500px; margin: 50px auto; } .success { color: green; font-weight: bold; font-size: 1.2em; } .link { display: inline-block; margin-top: 20px; padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; }</style></head><body><div class='container'><p class='success'>Thank you! Your message has been sent successfully.</p><a href='contact.html' class='link'>Send Another Message</a></div></body></html>";
            } else {
                // Error message page
                echo "<!DOCTYPE html><html><head><title>Error</title><style>body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; text-align: center; } .container { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); max-width: 500px; margin: 50px auto; } .error { color: red; font-weight: bold; } .link { display: inline-block; margin-top: 20px; padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; }</style></head><body><div class='container'><p class='error'>Oops! Something went wrong. Please try again later.</p><p class='error-details'>" . $stmt->error . "</p><a href='contact.html' class='link'>Go Back to Contact Form</a></div></body></html>";
            }

            // Close statement
            $stmt->close();
        } else {
            echo "<!DOCTYPE html><html><head><title>Error</title><style>body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; text-align: center; } .container { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); max-width: 500px; margin: 50px auto; } .error { color: red; font-weight: bold; } .link { display: inline-block; margin-top: 20px; padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; }</style></head><body><div class='container'><p class='error'>Error preparing statement: " . $conn->error . "</p><a href='contact.html' class='link'>Go Back to Contact Form</a></div></body></html>";
        }
    } else {
        // Display validation errors
        echo "<!DOCTYPE html><html><head><title>Validation Errors</title><style>body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; text-align: center; } .container { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); max-width: 500px; margin: 50px auto; } .error-list { text-align: left; color: red; list-style-type: disc; padding-left: 20px; } .link { display: inline-block; margin-top: 20px; padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; }</style></head><body><div class='container'><p style='color: red; font-weight: bold;'>Message Not Sent Due to Errors:</p><ul class='error-list'>";
        foreach ($errors as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul><a href='contact.html' class='link'>Go Back to Contact Form</a></div></body></html>";
    }

    // Close connection
    $conn->close();
} else {
    // If not a POST request, redirect or show an error
    header("Location: contact.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Explore custom online visualizers for live venues and creators, along with insightful articles and resources">
    <title>Max | Creative Developer</title>
    <link rel="stylesheet" href="/css/contact.css">
</head>
<body>
    <?php include '../nav.php'; ?>
    <div class="form-container">
        <h2>Contact Me</h2>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $message = trim($_POST['message'] ?? '');
            
            if (empty($name) || empty($email) || empty($message)) {
                echo '<div class="message error">Please fill in all fields.</div>';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo '<div class="message error">Please enter a valid email address.</div>';
            } else {
                $submission = [
                    'date' => date('Y-m-d H:i:s'),
                    'name' => $name,
                    'email' => $email,
                    'message' => $message
                ];
                
                $contactsFile = 'contacts.json';
                $contacts = file_exists($contactsFile) ? json_decode(file_get_contents($contactsFile), true) : [];
                $contacts[] = $submission;
                
                if (file_put_contents($contactsFile, json_encode($contacts, JSON_PRETTY_PRINT))) {
                    echo '<div class="message success">Thank you for your message! We will get back to you soon.</div>';
                    // Clear the form
                    $name = $email = $message = '';
                } else {
                    echo '<div class="message error">Sorry, there was an error saving your message. Please try again.</div>';
                }
            }
        }
        ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
            </div>
            
            <button type="submit" class="submit-btn">Send Message</button>
        </form>
    </div>
    <?php include '../foot.php'; ?>
</body>
</html>
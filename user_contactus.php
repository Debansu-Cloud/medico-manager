<?php
session_start();
include "user_navbar.php"; // Your navbar file

// Database configuration
include "db_connect.php";

// Make sure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['username']; // Logged-in user's name

// Handle form submission
$success_msg = "";
$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $website = $conn->real_escape_string($_POST['website']);
    $message = $conn->real_escape_string($_POST['message']);

    // Insert with the logged-in username
    $sql = "INSERT INTO contact_form_submissions (user, name, email, phone, website, message) 
            VALUES ('$user', '$name', '$email', '$phone', '$website', '$message')";

    if ($conn->query($sql) === TRUE) {
        $success_msg = "Your message has been sent successfully!";
    } else {
        $error_msg = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Contact Developers - Medico Manager</title>

<style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap");
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Poppins", sans-serif; }
    body { background: #f0f4ff; min-height: 100vh; display: flex; flex-direction: column; }
    .wrapper { width: 800px; max-width: 95%; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 15px 35px rgba(0,0,80,0.2); padding: 40px 50px; display: flex; flex-direction: column; gap: 20px; }
    .wrapper header { font-size: 28px; font-weight: 700; text-align: center; margin-bottom: 20px; color: #004aad; }
    form table { width: 100%; border-collapse: separate; border-spacing: 15px 20px; }
    form table td { vertical-align: top; }
    input, textarea { width: 100%; padding: 12px 15px; border-radius: 8px; border: 1px solid #004aad; font-size: 1rem; outline: none; resize: none; }
    textarea { min-height: 120px; }
    label { display: block; margin-bottom: 5px; font-weight: 500; color: #004aad; }
    .button-area { text-align: center; margin-top: 20px; }
    .button-area button { background: linear-gradient(135deg, #004aad 0%, #50c9ce 100%); color: white; border: none; padding: 14px 40px; font-size: 1rem; font-weight: 600; border-radius: 50px; cursor: pointer; transition: 0.3s; }
    .button-area button:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,80,0.4); }
    .success { color: green; text-align: center; font-weight: bold; margin-bottom: 10px; }
    .error { color: red; text-align: center; font-weight: bold; margin-bottom: 10px; }
    @media(max-width:768px){ form table td{display:block;width:100%;} form table{border-spacing:0 15px;} }
</style>
</head>
<body>

<div class="wrapper">
    <header>Contact Developers</header>

    <?php if($success_msg) { echo "<div class='success'>$success_msg</div>"; } ?>
    <?php if($error_msg) { echo "<div class='error'>$error_msg</div>"; } ?>

    <form method="POST" novalidate>
        <table>
            <tr>
                <td>
                    <label for="name">Name</label>
                    <input type="text" name="name" placeholder="Enter your name" required />
                </td>
                <td>
                    <label for="email">Email</label>
                    <input type="email" name="email" placeholder="Enter your email" required />
                </td>
            </tr>
            <tr>
                <td>
                    <label for="phone">Phone</label>
                    <input type="tel" name="phone" placeholder="Enter your phone number" />
                </td>
                <td>
                    <label for="website">Website</label>
                    <input type="url" name="website" placeholder="Enter your website" />
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <label for="message">Message</label>
                    <textarea name="message" placeholder="Write your message/query related to order" required></textarea>
                </td>
            </tr>
        </table>

        <div class="button-area">
            <button type="submit">Send Message</button>
        </div>
    </form>
</div>

<?php include "footer.php"; ?>
</body>
</html>

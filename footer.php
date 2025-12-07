<?php
// Database Connection
include "db_connect.php";
// Message variables
$success = "";
$error = "";

// Handle form submission
if (isset($_POST['submit'])) {
  $email = $_POST['email'];
  $message = $_POST['message'];

  if (!empty($email) && !empty($message)) {
    $sql = "INSERT INTO contact_messages (email, message) VALUES ('$email', '$message')";
    if ($conn->query($sql) === TRUE) {
      $success = "✅ Thank you! Your message has been sent.";
    } else {
      $error = "❌ Something went wrong. Please try again.";
    }
  } else {
    $error = "⚠ Please fill in all fields.";
  }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>Responsive Footer Section</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background: #fff; }

    .footer { background: #f8fbff; color: #0d3a8c; border-top: 4px solid #1e6aff; }
    .footer-container { max-width: 1200px; margin: auto; padding: 40px 20px; display: flex; flex-wrap: wrap; justify-content: space-between; gap: 20px; }
    .footer-section { flex: 1 1 250px; }
    .footer-section h2 { font-size: 1.4rem; margin-bottom: 15px; color: #1e6aff; border-bottom: 2px solid #1e6aff; display: inline-block; padding-bottom: 5px; }
    .footer-section p { margin-bottom: 15px; }

    .footer-section .social a { display: inline-block; margin: 5px 10px 5px 0; font-size: 1.2rem; color: #0d3a8c; transition: color 0.3s; }
    .footer-section .social a:hover { color: #1e6aff; }

    .footer-section.address p { margin-bottom: 10px; }
    .footer-section.address p i { margin-right: 8px; color: #1e6aff; }

    .footer-section.contact form input,
    .footer-section.contact form textarea {
      width: 100%; padding: 10px; border: 1px solid #1e6aff;
      border-radius: 4px; margin-bottom: 10px; font-size: 0.95rem;
    }

    .footer-section.contact form button {
      padding: 10px 20px; border: none; background: #1e6aff;
      color: #fff; font-size: 1rem; cursor: pointer; border-radius: 4px;
      transition: background 0.3s;
    }

    .footer-section.contact form button:hover { background: #0047d1; }
    .footer-section.contact form input:focus,
    .footer-section.contact form textarea:focus,
    .footer-section.contact form button:focus {
      outline: 2px solid #1e6aff; outline-offset: 2px;
    }

    .footer-bottom { text-align: center; padding: 15px 20px; background: #f2f8ff; font-size: 0.9rem; color: #555; }

    .message { text-align:center; padding:10px; margin-bottom:10px; border-radius:6px; font-weight:500; }
    .success { background:#e3f2fd; color:#1e6aff; border:1px solid #bbdefb; }
    .error { background:#ffebee; color:#c62828; border:1px solid #ef5350; }

    @media (max-width: 900px) {
      .footer-container { flex-direction: column; align-items: center; text-align: center; }
      .footer-section { flex: 1 1 100%; max-width: 500px; }
    }

    @media (max-width: 480px) {
      .footer-section h2 { font-size: 1.2rem; }
      .footer-section .social a { font-size: 1.1rem; margin-right: 8px; }
    }
  </style>
</head>

<body>
  <footer class="footer">
    <div class="footer-container">
      <div class="footer-section about">
        <h2>About Us</h2>
        <p>Medicomanager is your trusted pharmacy partner — we deliver genuine medicines and health essentials directly to you, swiftly and securely.</p>
        <div class="social">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-whatsapp"></i></a>
        </div>
      </div>

      <div class="footer-section address">
        <h2>Address</h2>
        <p><i class="fas fa-map-marker-alt"></i> Newtown, West Bengal</p>
        <p><i class="fa-solid fa-phone"></i> +089-765432100</p>
        <p><i class="fas fa-envelope"></i> info@medicomanager.com</p>
      </div>

      <div class="footer-section contact">
        <h2>Contact Us</h2>
        <?php if($success) echo "<div class='message success'>$success</div>"; ?>
        <?php if($error) echo "<div class='message error'>$error</div>"; ?>
        <form method="POST">
          <input type="email" name="email" placeholder="Your email" required>
          <textarea name="message" placeholder="Your message" required></textarea>
          <button type="submit" name="submit">Send</button>
        </form>
      </div>
    </div>

    <div class="footer-bottom">
      <p>© 2025 Medicomanager. All rights reserved.</p>
    </div>
  </footer>
</body>
</html>

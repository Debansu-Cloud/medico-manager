<?php
session_start();

// ---------- DATABASE CONNECTION ----------
include "db_connect.php";

$message = "";

// ---------- SIGNUP BACKEND ----------
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Check if passwords match
    if ($password !== $confirm_password) {
        $message = "<div class='error'>❌ Passwords do not match!</div>";
    } else {
        // Check if username already exists
        $check_sql = "SELECT * FROM medicine_app_users WHERE username='$username'";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            $message = "<div class='error'>❌ Username already taken!</div>";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $insert_sql = "INSERT INTO medicine_app_users (username, password) VALUES ('$username', '$hashed_password')";
            if ($conn->query($insert_sql)) {
                $message = "<div class='success'>✅ Signup successful! You can now <a href='index.php'>login</a>.</div>";
            } else {
                $message = "<div class='error'>❌ Something went wrong: ".$conn->error."</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Medicine Manager - User Signup</title>
<style>
/* Same CSS as login page */
* {margin:0; padding:0; box-sizing:border-box; font-family:Arial, Helvetica, sans-serif;}
body {background: linear-gradient(to bottom right,#e0e7ff,#c7d2fe); min-height:100vh; display:flex; justify-content:center; align-items:center; padding:20px;}
.container {max-width:400px; width:100%;}
.header {text-align:center; margin-bottom:25px;}
.icon-circle {width:70px; height:70px; background:#4f46e5; color:white; font-size:35px; display:flex; justify-content:center; align-items:center; border-radius:50%; margin:0 auto 10px;}
.header h1 {color:#111827; font-size:24px; margin-bottom:5px;}
.header p {color:#6b7280; font-size:14px;}
.card {background:white; padding:20px; border-radius:12px; box-shadow:0px 6px 18px rgba(0,0,0,0.1);}
form label {display:block; margin-bottom:5px; color:#374151; font-size:14px;}
form input {width:100%; padding:10px; border:1px solid #d1d5db; border-radius:8px; margin-bottom:15px; font-size:16px;}
form input:focus {outline:none; border-color:#4f46e5; box-shadow:0px 0px 4px #818cf8;}
.login-button {width:100%; padding:12px; background:#4f46e5; color:white; border:none; border-radius:8px; font-size:18px; cursor:pointer; transition:0.3s;}
.login-button:hover {background:#4338ca;}
.success {background:#c8e6c9; color:#2e7d32; padding:8px; margin-bottom:10px; border-radius:8px; text-align:center; font-size:14px;}
.error {background:#ffe5e5; color:#c70000; padding:8px; margin-bottom:10px; border-radius:8px; text-align:center; font-size:14px;}
</style>
</head>
<body>
<div class="container">
  <div class="header">
    <div class="icon-circle">💊</div>
    <h1>Medicine Manager</h1>
    <p>User Signup</p>
  </div>

  <div class="card">
    <?php echo $message; ?>

    <form method="POST">
      <label>Username</label>
      <input type="text" name="username" placeholder="Enter username" required />

      <label>Password</label>
      <input type="password" name="password" placeholder="Enter password" required />

      <label>Confirm Password</label>
      <input type="password" name="confirm_password" placeholder="Confirm password" required />

      <button type="submit" class="login-button">✅ Signup</button>
    </form>

    <div class="demo-box" style="margin-top:15px;">
      <p>Already have an account? <a href="index.php">Login here</a>.</p>
    </div>
  </div>
</div>
</body>
</html>

<?php
session_start();
include "db_connect.php";

$message = "";

// ---------- LOGIN BACKEND ----------
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role']; // "user" or "admin"
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($role == "user") {
        $sql = "SELECT * FROM medicine_app_users WHERE username='$username'";
    } else { // admin
        $sql = "SELECT * FROM users WHERE username='$username'";
    }

    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        if ($role == "user") {
            // Verify password for users
            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;
                $_SESSION['user_id'] = $row['id']; // important for cart page
                header("Location: user_dashboard.php");
                exit();
            } else {
                $message = "<div class='error'>❌ Incorrect Password!</div>";
            }
        } else {
            // Admin password check (plain text for now)
            if ($password === $row['password']) {
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;
                $_SESSION['admin_id'] = $row['id']; // optional
                header("Location: dashboard.php");
                exit();
            } else {
                $message = "<div class='error'>❌ Incorrect Password!</div>";
            }
        }

    } else {
        $message = "<div class='error'>❌ Username not found!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Medicine Management Login</title>
<style>
* {margin:0; padding:0; box-sizing:border-box; font-family:Arial, Helvetica, sans-serif;}
body {background: linear-gradient(to bottom right,#e0e7ff,#c7d2fe); min-height:100vh; display:flex; justify-content:center; align-items:center; padding:20px;}
.container {max-width:400px; width:100%;}
.header {text-align:center; margin-bottom:25px;}
.icon-circle {width:70px; height:70px; background:#4f46e5; color:white; font-size:35px; display:flex; justify-content:center; align-items:center; border-radius:50%; margin:0 auto 10px;}
.header h1 {color:#111827; font-size:24px; margin-bottom:5px;}
.header p {color:#6b7280; font-size:14px;}
.card {background:white; padding:20px; border-radius:12px; box-shadow:0px 6px 18px rgba(0,0,0,0.1);}
.toggle {display:flex; margin-bottom:20px; background:#e5e7eb; padding:4px; border-radius:10px;}
.toggle button {flex:1; padding:10px; background:transparent; border:none; font-size:16px; cursor:pointer; border-radius:8px; transition:0.3s;}
.toggle button.active {background:white; color:#4f46e5; font-weight:bold; box-shadow:0px 2px 6px rgba(0,0,0,0.15);}
form label {display:block; margin-bottom:5px; color:#374151; font-size:14px;}
form input {width:100%; padding:10px; border:1px solid #d1d5db; border-radius:8px; margin-bottom:15px; font-size:16px;}
form input:focus {outline:none; border-color:#4f46e5; box-shadow:0px 0px 4px #818cf8;}
.login-button {width:100%; padding:12px; background:#4f46e5; color:white; border:none; border-radius:8px; font-size:18px; cursor:pointer; transition:0.3s;}
.login-button:hover {background:#4338ca;}
.demo-box {background:#f3f4f6; padding:12px; border-radius:8px; margin-top:20px; font-size:14px;}
.hidden {display:none;}
.error {background:#ffe5e5; color:#c70000; padding:8px; margin-bottom:10px; border-radius:8px; text-align:center; font-size:14px;}
.signup-link {text-align:center; margin-top:10px; font-size:14px;}
.signup-link a {color:#4f46e5; text-decoration:none; font-weight:bold;}
.signup-link a:hover {text-decoration:underline;}
</style>
</head>
<body>
<div class="container">

  <div class="header">
    <div class="icon-circle">💊</div>
    <h1>Medicine Manager</h1>
    <p>Login Portal</p>
  </div>

  <div class="card">

    <!-- Toggle Buttons -->
    <div class="toggle">
      <button id="userBtn" class="active">👤 User</button>
      <button id="adminBtn">🛡️ Admin</button>
    </div>

    <?php echo $message; ?>

    <!-- Login Form -->
    <form id="loginForm" method="POST">
      <input type="hidden" id="roleInput" name="role" value="user" />

      <label id="userLabel">Username</label>
      <input type="text" name="username" id="usernameInput" placeholder="Enter username" required />

      <label id="passLabel">Password</label>
      <input type="password" name="password" id="passwordInput" placeholder="Enter password" required />

      <button type="submit" class="login-button">🔐 Login</button>
    </form>

    <!-- Signup link for users only -->
    <div id="signupLink" class="signup-link">
      <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>

    <div id="demoUser" class="demo-box">
      <p><strong>User Login</strong></p>
    </div>

    <div id="demoAdmin" class="demo-box hidden">
      <p><strong>Admin Login</strong></p>
    </div>

  </div>
</div>

<script>
const userBtn = document.getElementById("userBtn");
const adminBtn = document.getElementById("adminBtn");

const roleInput = document.getElementById("roleInput");
const userLabel = document.getElementById("userLabel");
const passLabel = document.getElementById("passLabel");
const demoUser = document.getElementById("demoUser");
const demoAdmin = document.getElementById("demoAdmin");
const signupLink = document.getElementById("signupLink");

// Default = User
userBtn.onclick = () => {
  userBtn.classList.add("active");
  adminBtn.classList.remove("active");

  roleInput.value = "user";
  userLabel.innerText = "Username";
  passLabel.innerText = "Password";

  demoUser.classList.remove("hidden");
  demoAdmin.classList.add("hidden");
  signupLink.style.display = "block"; // show signup
};

// Admin
adminBtn.onclick = () => {
  adminBtn.classList.add("active");
  userBtn.classList.remove("active");

  roleInput.value = "admin";
  userLabel.innerText = "Username";
  passLabel.innerText = "Password";

  demoUser.classList.add("hidden");
  demoAdmin.classList.remove("hidden");
  signupLink.style.display = "none"; // hide signup
};
</script>
</body>
</html>

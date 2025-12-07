<?php
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: index.php");
    exit();
}

include "db_connect.php";
include "navbar.php";  // Database connection

$message = "";

// ---------- Handle Form Submission ----------
if(isset($_POST['add_doctor'])){
    $name = trim($_POST['name']);
    $qualification = trim($_POST['qualification']);
    $specialization = trim($_POST['specialization']);
    $times = trim($_POST['available_times']);
    $fee = trim($_POST['booking_fee']);

    // Generate a unique doctor_code
    $doctor_code = strtoupper(substr($name,0,3) . uniqid());

    // Insert into database safely
    $sql = "INSERT INTO doctors (name, qualification, specialization, available_times, booking_fee, doctor_code)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $name, $qualification, $specialization, $times, $fee, $doctor_code);

    if($stmt->execute()){
        $message = "✅ Doctor added successfully! Doctor Code: $doctor_code";
    } else {
        $message = "❌ Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Doctor</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body { font-family: Arial, sans-serif; background:#f0f4ff; margin:0; padding:0; }
.container { max-width:600px; margin:40px auto; background:#fff; padding:30px; border-radius:15px; box-shadow:0 5px 15px rgba(0,0,0,0.1); }
h2 { text-align:center; color:#004aad; margin-bottom:20px; }
input, button { width:100%; padding:12px; margin:10px 0; border-radius:6px; border:1px solid #ccc; font-size:1rem; }
button { background:#004aad; color:#fff; border:none; cursor:pointer; transition:0.3s; }
button:hover { background:#003580; }
.message { text-align:center; padding:10px; border-radius:6px; margin-bottom:15px; background:#e0f7fa; }
a.back { display:inline-block; margin-top:10px; color:#004aad; text-decoration:none; }
a.back:hover { text-decoration:underline; }
</style>
</head>
<body>

<div class="container">
<h2>Add Doctor</h2>

<?php if($message) echo "<div class='message'>$message</div>"; ?>

<form method="POST">
<label>Name:</label>
<input type="text" name="name" required>

<label>Qualification:</label>
<input type="text" name="qualification">

<label>Specialization:</label>
<input type="text" name="specialization">

<label>Available Times (comma separated, e.g., 09:00-11:00,14:00-16:00):</label>
<input type="text" name="available_times" required>

<label>Booking Fee:</label>
<input type="number" name="booking_fee" required step="0.01">

<button type="submit" name="add_doctor">Add Doctor</button>
</form>

<a href="dashboard.php" class="back">⬅ Back to Dashboard</a>
</div>
<?php
include "footer.php"; 
?>
</body>
</html>

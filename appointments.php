<?php
session_start();
include "db_connect.php";
include "user_navbar.php";

// ---------- LOGIN CHECK ----------
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user'){
    header("Location: index.php");
    exit();
}

$message = "";

// ---------- BOOKING ----------
if(isset($_POST['book'])){
    $doctor_code = $_POST['doctor_code'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $patient_name = trim($_POST['patient_name']); 

    if(empty($patient_name)){
        $message = "❌ Please enter the patient name!";
    } else {
        $stmt = $conn->prepare("SELECT id FROM doctors WHERE doctor_code=? LIMIT 1");
        $stmt->bind_param("s", $doctor_code);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows == 0){
            $message = "❌ Doctor not found!";
        } else {
            $doctor = $result->fetch_assoc();
            $doctor_id = $doctor['id'];
            $user_id = $_SESSION['user_id'];

            // Check duplicate appointment
            $check_stmt = $conn->prepare("SELECT * FROM doctor_appointments WHERE user_id=? AND appointment_date=? AND appointment_time=?");
            $check_stmt->bind_param("iss", $user_id, $date, $time);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if($check_result->num_rows > 0){
                $message = "❌ You already have an appointment at this time.";
            } else {
                $status = "Pending";
                $insert_stmt = $conn->prepare("INSERT INTO doctor_appointments (user_id, doctor_id, patient_name, appointment_date, appointment_time, status) VALUES (?,?,?,?,?,?)");
                $insert_stmt->bind_param("iissss", $user_id, $doctor_id, $patient_name, $date, $time, $status);
                $insert_stmt->execute();
                $message = "✅ Appointment booked successfully!";
            }
        }
    }
}

// Fetch all doctors
$doctors_res = $conn->query("SELECT * FROM doctors ORDER BY name ASC");

// Fetch user's appointments
$user_id = $_SESSION['user_id'];
$appointments = $conn->prepare("
    SELECT da.*, d.name AS doctor_name 
    FROM doctor_appointments da 
    JOIN doctors d ON da.doctor_id=d.id
    WHERE da.user_id=?
    ORDER BY da.appointment_date DESC, da.appointment_time DESC
");
$appointments->bind_param("i", $user_id);
$appointments->execute();
$appointments_res = $appointments->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Book Appointment</title>
<style>
body{font-family:sans-serif; background:#f0f4ff; margin:0; padding:0;}
.container{max-width:1200px;margin:40px auto;padding:20px;}
.message{padding:10px;background:#e0f7fa;border-radius:6px;margin-bottom:15px;}
.flex-doctors {display:flex; flex-wrap:wrap; gap:20px;}
.doctor-card{background:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.05); flex:1 1 calc(33% - 20px); min-width:250px;}
.doctor-card h3{margin:0 0 10px;}
form input, form select, form button{width:100%; padding:10px; margin:5px 0; border-radius:6px;}
form button{background:#004aad;color:#fff;border:none; cursor:pointer;}
form button:hover{background:#003580;}
.appointment-card{background:#fff;padding:15px;border-radius:12px;margin-bottom:15px;box-shadow:0 4px 12px rgba(0,0,0,0.05);}
.status-Pending {color:#ff9800;font-weight:bold;}
.status-Confirmed {color:#43a047;font-weight:bold;}
.status-Cancelled {color:#c62828;font-weight:bold;}
@media(max-width:900px){.doctor-card{flex:1 1 calc(50% - 20px);}}
@media(max-width:600px){.doctor-card{flex:1 1 100%;}}
</style>
</head>
<body>

<div class="container">
<h2>Available Doctors</h2>
<?php if($message) echo "<div class='message'>$message</div>"; ?>

<div class="flex-doctors">
<?php while($doc = $doctors_res->fetch_assoc()): ?>
<div class="doctor-card">
<h3><?php echo htmlspecialchars($doc['name']); ?></h3>
<p><strong>Qualification:</strong> <?php echo htmlspecialchars($doc['qualification']); ?></p>
<p><strong>Specialization:</strong> <?php echo htmlspecialchars($doc['specialization']); ?></p>
<p><strong>Available Times:</strong> <?php echo htmlspecialchars($doc['available_times']); ?></p>
<p><strong>Fee:</strong> ₹<?php echo number_format($doc['booking_fee'],2); ?></p>

<form method="POST">
<input type="hidden" name="doctor_code" value="<?php echo htmlspecialchars($doc['doctor_code']); ?>">
<label>Patient Name:</label>
<input type="text" name="patient_name" required placeholder="Enter patient name">
<label>Date:</label>
<input type="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
<label>Time:</label>
<input type="time" name="time" required>
<button type="submit" name="book">Book Appointment</button>
</form>
</div>
<?php endwhile; ?>
</div>

<h2>My Appointments</h2>
<?php if($appointments_res->num_rows>0): ?>
<?php while($app = $appointments_res->fetch_assoc()): ?>
<div class="appointment-card">
<h4><?php echo htmlspecialchars($app['patient_name']); ?> with Dr. <?php echo htmlspecialchars($app['doctor_name']); ?></h4>
<p><strong>Date:</strong> <?php echo $app['appointment_date']; ?> <strong>Time:</strong> <?php echo $app['appointment_time']; ?></p>
<p><strong>Status:</strong> <span class="status-<?php echo $app['status']; ?>"><?php echo $app['status']; ?></span></p>
</div>
<?php endwhile; ?>
<?php else: ?>
<p>You have no appointments yet.</p>
<?php endif; ?>

</div>

<?php include "footer.php"; ?>
</body>
</html>

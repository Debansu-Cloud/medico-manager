<?php
session_start();

// ---------- Access Control ----------
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: index.php");
    exit();
}


include "db_connect.php"; // Database connection

// ---------- Handle Status Update ----------
if(isset($_POST['update_status'])){
    $doctor_code = $_POST['doctor_code'];
    $date = $_POST['appointment_date'];
    $time = $_POST['appointment_time'];
    $new_status = $_POST['status'];

    $stmt = $conn->prepare("
        UPDATE doctor_appointments da
        JOIN doctors d ON da.doctor_id=d.id
        SET da.status=?
        WHERE d.doctor_code=? AND da.appointment_date=? AND da.appointment_time=?
    ");
    $stmt->bind_param("ssss", $new_status, $doctor_code, $date, $time);
    $stmt->execute();
    $stmt->close();
}

// ---------- Handle Delete ----------
if(isset($_POST['delete_booking'])){
    $booking_id = intval($_POST['booking_id']);
    $del_stmt = $conn->prepare("DELETE FROM doctor_appointments WHERE id=?");
    $del_stmt->bind_param("i", $booking_id);
    $del_stmt->execute();
    $del_stmt->close();
}

// ---------- Fetch All Bookings ----------
$bookings = $conn->query("
    SELECT da.*, d.name AS doctor_name, d.doctor_code
    FROM doctor_appointments da
    JOIN doctors d ON da.doctor_id = d.id
    ORDER BY da.appointment_date DESC, da.appointment_time DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Doctor Appointments</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {font-family:Arial,sans-serif;background:#f0f4ff;margin:0;padding:0;}
h2 {color:#314ce6;margin-top:20px;text-align:center;}
table {width:100%;border-collapse:collapse;margin:20px 0;}
th,td {border:1px solid #ccc;padding:8px;text-align:left;}
th {background:#314ce6;color:white;}
select,button {padding:5px;margin:2px;border-radius:4px;}
button {background:#4f46e5;color:white;border:none;cursor:pointer;}
button:hover {background:#4338ca;}
.status-Pending {color:#ff9800;font-weight:bold;}
.status-Confirmed {color:#43a047;font-weight:bold;}
.status-Cancelled {color:#c62828;font-weight:bold;}
.delete-btn {background:#c62828;}
.delete-btn:hover {background:#b71c1c;}
@media(max-width:768px){
    table,thead,tbody,th,td,tr{display:block;}
    tr{margin-bottom:15px;border-bottom:2px solid #ddd;}
    th{display:none;}
    td{display:flex;justify-content:space-between;padding:8px;border:none;border-bottom:1px solid #eee;}
    td:before{content: attr(data-label); font-weight:bold;}
}
</style>
</head>
<body>

<?php include "navbar.php"; // Ensure navbar appears at top ?>

<h2>All Doctor Appointments</h2>

<table>
<tr>
<th>Patient Name</th>
<th>Doctor</th>
<th>Date</th>
<th>Time</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php if($bookings->num_rows>0): ?>
    <?php while($b = $bookings->fetch_assoc()): ?>
<tr>
<td data-label="Patient Name"><?php echo htmlspecialchars($b['patient_name']); ?></td>
<td data-label="Doctor"><?php echo htmlspecialchars($b['doctor_name']); ?></td>
<td data-label="Date"><?php echo $b['appointment_date']; ?></td>
<td data-label="Time"><?php echo $b['appointment_time']; ?></td>
<td data-label="Status">
<span class="status-<?php echo $b['status']; ?>"><?php echo $b['status']; ?></span>
</td>
<td data-label="Action">
<!-- Update Status -->
<form method="POST" style="display:inline-block;">
<input type="hidden" name="doctor_code" value="<?php echo htmlspecialchars($b['doctor_code']); ?>">
<input type="hidden" name="appointment_date" value="<?php echo $b['appointment_date']; ?>">
<input type="hidden" name="appointment_time" value="<?php echo $b['appointment_time']; ?>">
<select name="status">
    <option value="Pending" <?php if($b['status']=='Pending') echo 'selected'; ?>>Pending</option>
    <option value="Confirmed" <?php if($b['status']=='Confirmed') echo 'selected'; ?>>Confirmed</option>
    <option value="Cancelled" <?php if($b['status']=='Cancelled') echo 'selected'; ?>>Cancelled</option>
</select>
<button type="submit" name="update_status">Update</button>
</form>

<!-- Delete Booking -->
<form method="POST" style="display:inline-block;">
<input type="hidden" name="booking_id" value="<?php echo $b['id']; ?>">
<button type="submit" name="delete_booking" class="delete-btn" onclick="return confirm('Are you sure you want to delete this booking?');">Delete</button>
</form>
</td>
</tr>
    <?php endwhile; ?>
<?php else: ?>
<tr><td colspan="6" style="text-align:center;">No appointments found.</td></tr>
<?php endif; ?>
</table>

<?php include "footer.php"; // Footer at bottom ?>

</body>
</html>

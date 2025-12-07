<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: index.php");
    exit();
}

include "db_connect.php";

// Handle status update
if(isset($_POST['update_status'])){
    $booking_id = intval($_POST['booking_id']);
    $new_status = $_POST['status'];

    // Get current booking to check medicine quantity
    $res = $conn->query("SELECT medicine_id, qty, status FROM bookings WHERE id=$booking_id");
    if($res->num_rows > 0){
        $booking = $res->fetch_assoc();

        // Only reduce stock if changing to Success from Pending
        if(strtolower($new_status) == 'success' && strtolower($booking['status']) != 'success'){
            $conn->query("UPDATE medicines SET quantity = quantity - {$booking['qty']} WHERE id={$booking['medicine_id']}");
        }

        // Update booking status
        $stmt = $conn->prepare("UPDATE bookings SET status=? WHERE id=?");
        $stmt->bind_param("si", $new_status, $booking_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch all bookings
$bookings = $conn->query("SELECT b.id, u.username AS user_name, m.name AS medicine_name, b.qty, b.price, b.name AS customer_name, b.phone, b.utr_no, b.status, b.booking_date 
                          FROM bookings b
                          JOIN medicines m ON b.medicine_id = m.id
                          JOIN medicine_app_users u ON b.user_id = u.id
                          ORDER BY b.booking_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Orders</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {font-family:Arial,sans-serif;background:#f0f4ff;padding:20px;}
h2 {color:#314ce6;}
table {width:100%;border-collapse:collapse;margin-top:20px;}
th,td {border:1px solid #ccc;padding:8px;text-align:left;}
th {background:#314ce6;color:white;}
select,button {padding:5px;margin:2px;border-radius:4px;}
button {background:#4f46e5;color:white;border:none;cursor:pointer;}
button:hover {background:#4338ca;}
.status-pending {color:#ff9800;font-weight:bold;}
.status-success {color:#43a047;font-weight:bold;}
.status-cancelled {color:#c62828;font-weight:bold;}
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
<h2>All User Bookings</h2>
<table>
<tr>
<th>User</th>
<th>Customer Name</th>
<th>Phone</th>
<th>Medicine</th>
<th>Qty</th>
<th>Price</th>
<th>UTR No</th>
<th>Status</th>
<th>Booking Date</th>
<th>Action</th>
</tr>
<?php if($bookings->num_rows>0): ?>
<?php while($b=$bookings->fetch_assoc()): ?>
<tr>
<td data-label="User"><?php echo htmlspecialchars($b['user_name']); ?></td>
<td data-label="Customer Name"><?php echo htmlspecialchars($b['customer_name']); ?></td>
<td data-label="Phone"><?php echo htmlspecialchars($b['phone']); ?></td>
<td data-label="Medicine"><?php echo htmlspecialchars($b['medicine_name']); ?></td>
<td data-label="Qty"><?php echo $b['qty']; ?></td>
<td data-label="Price"><?php echo number_format($b['price'],2); ?></td>
<td data-label="UTR No"><?php echo htmlspecialchars($b['utr_no']); ?></td>
<td data-label="Status">
<span class="<?php echo strtolower($b['status'])=='pending'?'status-pending':(strtolower($b['status'])=='success'?'status-success':'status-cancelled'); ?>">
<?php echo $b['status']; ?>
</span>
</td>
<td data-label="Booking Date"><?php echo $b['booking_date']; ?></td>
<td data-label="Action">
<form method="POST" style="display:inline-block;">
<input type="hidden" name="booking_id" value="<?php echo $b['id']; ?>">
<select name="status">
<option value="Pending" <?php if($b['status']=='Pending') echo 'selected'; ?>>Pending</option>
<option value="Success" <?php if($b['status']=='Success') echo 'selected'; ?>>Success</option>
<option value="Cancelled" <?php if($b['status']=='Cancelled') echo 'selected'; ?>>Cancelled</option>
</select>
<button type="submit" name="update_status">Update</button>
</form>
</td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr><td colspan="10" style="text-align:center;">No bookings found.</td></tr>
<?php endif; ?>
</table>
</body>
</html>

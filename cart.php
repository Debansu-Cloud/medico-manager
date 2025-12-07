<?php
session_start();
include "db_connect.php";
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user'){
    header("Location: index.php");
    exit();
}
include "user_navbar.php";



$message = "";

if(isset($_GET['remove'])){
    $id = $_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit();
}

if(isset($_POST['checkout'])){
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $utr = trim($_POST['utr']);
    $status = "Pending";

    if(!empty($name) && !empty($phone) && !empty($utr) && !empty($_SESSION['cart'])){
        foreach($_SESSION['cart'] as $med_id=>$item){
            $price = $item['price'];
            $qty = $item['qty'];
            $sql = "INSERT INTO bookings (user_id, name, phone, medicine_id, qty, price, utr_no, status)
                    VALUES ({$_SESSION['user_id']},
                            '{$conn->real_escape_string($name)}',
                            '{$conn->real_escape_string($phone)}',
                            $med_id,
                            $qty,
                            $price,
                            '{$conn->real_escape_string($utr)}',
                            '{$status}')";
            $conn->query($sql);
        }
        $_SESSION['cart'] = [];
        $message = "✅ Booking confirmed! Collect from shop with payment proof.";
    } else {
        $message = "❌ Fill all fields or add items to cart.";
    }
}

$bookings = [];
$res = $conn->query("SELECT b.id, m.name AS medicine_name, b.qty, b.price, b.status
                     FROM bookings b
                     JOIN medicines m ON b.medicine_id = m.id
                     WHERE b.user_id = {$_SESSION['user_id']}
                     ORDER BY b.id DESC");
while($row = $res->fetch_assoc()){
    $bookings[] = $row;
}

$total = 0;
if(!empty($_SESSION['cart'])){
    foreach($_SESSION['cart'] as $item){
        $total += $item['price'] * $item['qty'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Cart - Medico Manager</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
    font-family: Arial, sans-serif;
    background: #f0f4ff;
    margin: 0;
    padding: 0;
    color: #222;
}
.container { max-width: 1200px; margin: auto; padding: 20px; }

/* ----- Table Styling ----- */
table {width:100%;border-collapse:collapse;margin-bottom:20px; box-shadow: 0 6px 18px rgba(0,0,0,0.05);}
th,td {padding:12px;border:1px solid #ddd; text-align:left;}
th {background: #314ce6; color:white;}
tr:nth-child(even){background:#f9f9f9;}
td a {color:#c62828; text-decoration:none;}
td a:hover {text-decoration:underline;}

/* ----- Payment Card ----- */
.payment-card {
    background: white;
    border-radius: 16px;
    padding: 30px;
    margin-top: 30px;
    box-shadow: 0 8px 25px rgba(0,0,150,0.08);
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
}
.payment-info {
    flex: 1;
    min-width: 250px;
}
.payment-info h2 { color:#314ce6; margin-bottom:12px; }
.payment-info p { font-size:1rem; margin:8px 0; }

/* ----- Checkout Form ----- */
.checkout-form {
    flex: 1;
    min-width: 300px;
    background: #f3f7ff;
    border-radius: 16px;
    padding: 25px;
    box-shadow: 0 4px 18px rgba(0,60,220,0.05);
}
.checkout-form h3 { color:#314ce6; margin-bottom:15px; }
.checkout-form label { display:block; margin-top:10px; font-weight:600; }
.checkout-form input { width:100%; padding:10px; margin-top:6px; border-radius:8px; border:1px solid #ccc; }
.checkout-form button {
    background: linear-gradient(90deg,#004aad,#50c9ce);
    color:white;
    font-size:1rem;
    font-weight:600;
    border:none;
    padding:12px 20px;
    border-radius:32px;
    cursor:pointer;
    margin-top:15px;
    transition:0.3s;
}
.checkout-form button:hover { transform: translateY(-3px); box-shadow:0 8px 20px rgba(0,0,0,0.2); }

/* ----- Booking Policy ----- */
.booking-policy {
    background: #fff8e1;
    border-left: 6px solid #ff9800;
    padding: 20px;
    margin-top: 25px;
    border-radius: 12px;
    font-size: 0.95rem;
    line-height: 1.6;
}
.booking-policy h4 { margin-bottom:10px; color:#ff9800; }
.booking-policy ul { margin-left:20px; }

/* Status Colors */
.status-pending {color:#ff9800;font-weight:bold;}
.status-success {color:#43a047;font-weight:bold;}
.status-cancelled {color:#c62828;font-weight:bold;}

/* Responsive */
@media(max-width:768px){
    .payment-card {flex-direction:column;}
}
</style>
</head>
<body>
<div class="container">
<h2>Your Cart</h2>
<a href="available_Medicine.php">⬅ Back to Medicines</a>

<?php if($message) echo "<p class='message'>{$message}</p>"; ?>

<?php if(!empty($_SESSION['cart'])): ?>
<table>
<tr>
<th>Name</th>
<th>Price</th>
<th>Qty</th>
<th>Subtotal</th>
<th>Action</th>
</tr>
<?php foreach($_SESSION['cart'] as $id=>$item):
$subtotal = $item['price']*$item['qty'];
?>
<tr>
<td data-label="Name"><?php echo htmlspecialchars($item['name']); ?></td>
<td data-label="Price"><?php echo number_format($item['price'],2); ?></td>
<td data-label="Qty"><?php echo $item['qty']; ?></td>
<td data-label="Subtotal"><?php echo number_format($subtotal,2); ?></td>
<td data-label="Action"><a href="?remove=<?php echo $id; ?>">Remove</a></td>
</tr>
<?php endforeach; ?>
<tr>
<td colspan="3"><strong>Total</strong></td>
<td colspan="2"><strong><?php echo number_format($total,2); ?></strong></td>
</tr>
</table>

<div class="payment-card">
    <!-- Payment Details -->
    <div class="payment-info">
        <h2>Payment Details</h2>
        <p>💳 Pay via PhonePe / Paytm / Google Pay</p>
        <p>📱 <strong>4769755556</strong> - Medico Store</p>
        <p>✉️ UPI ID: <strong>medico@ybl</strong></p>
        <p>📌 Collect medicines from store. No return/refund allowed without payment proof.</p>
    </div>

    <!-- Checkout Form -->
    <div class="checkout-form">
        <h3>Enter Your Details</h3>
        <form method="POST">
            <label>Name:</label>
            <input type="text" name="name" required>
            <label>Phone:</label>
            <input type="text" name="phone" required>
            <label>UTR / Transaction No:</label>
            <input type="text" name="utr" required>
            <button type="submit" name="checkout">Book & Pay</button>
        </form>
    </div>
</div>

<!-- Booking Policy -->
<div class="booking-policy">
<h4>Booking & Refund Policy</h4>
<ul>
<li>All medicines must be collected from the store with valid payment proof.</li>
<li>No returns or refunds are allowed without UTR / Transaction confirmation.</li>
<li>For queries regarding your booking, contact us via <a href="contactus.php">Contact Form</a> or call the number in the footer.</li>
<li>Visit the store personally to collect your order and verify your payment.</li>
<li>Double-check payment details before submitting.</li>
<li>Copy your UTR/Transaction ID accurately for verification.</li>
</ul>
</div>

<?php else: ?>
<p>Your cart is empty.</p>
<?php endif; ?>

<h3>Your Bookings</h3>
<?php if(!empty($bookings)): ?>
<table>
<tr>
<th>Medicine</th>
<th>Qty</th>
<th>Price</th>
<th>Status</th>
</tr>
<?php foreach($bookings as $b): ?>
<tr>
<td data-label="Medicine"><?php echo htmlspecialchars($b['medicine_name']); ?></td>
<td data-label="Qty"><?php echo $b['qty']; ?></td>
<td data-label="Price"><?php echo number_format($b['price'],2); ?></td>
<td data-label="Status">
<?php 
$statusClass = strtolower($b['status']) == 'pending' ? 'status-pending' : (strtolower($b['status'])=='success'?'status-success':'status-cancelled');
echo "<span class='$statusClass'>".$b['status']."</span>";
?>
</td>
</tr>
<?php endforeach; ?>
</table>
<?php else: ?>
<p>No bookings yet.</p>
<?php endif; ?>

</div>

<?php 
include "footer.php";
?>
</body>
</html>

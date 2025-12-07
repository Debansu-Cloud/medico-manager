<?php include "navbar.php"?>
<?php
include "db_connect.php";

$success = $error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $contact_person = $_POST['contact_person'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $gst_number = $_POST['gst_number'];

    $stmt = $conn->prepare("INSERT INTO suppliers (name, contact_person, phone, email, address, gst_number) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $contact_person, $phone, $email, $address, $gst_number);
    if ($stmt->execute()) {
        $success = "✅ Supplier added successfully!";
    } else {
        $error = "❌ Failed to add supplier. Error: ".$stmt->error;
    }
    $stmt->close();
}

// Fetch all suppliers
$suppliers = $conn->query("SELECT * FROM suppliers ORDER BY id DESC");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Suppliers Management</title>
<style>
/* ---------- Navbar ---------- */


/* ---------- Container ---------- */
.container { max-width:1200px; margin:30px auto; padding:0 15px; }

/* ---------- Form Styles ---------- */
form { background:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.1); margin-bottom:30px; }
form h2 { color:#314ce6; margin-bottom:15px; }
.form-group { margin-bottom:12px; }
.form-group label { display:block; margin-bottom:6px; font-weight:500; }
.form-group input, .form-group textarea { width:100%; padding:10px; border:1px solid #ccc; border-radius:8px; font-size:14px; }
textarea { height:60px; resize:none; }
button { background:#314ce6; color:#fff; border:none; padding:12px; font-size:16px; border-radius:10px; cursor:pointer; width:100%; transition:0.3s; }
button:hover { background:#243fcf; }

/* ---------- Grid Layout ---------- */
.grid { display:grid; gap:20px; }
.grid-3 { grid-template-columns: repeat(auto-fit, minmax(250px,1fr)); }

/* ---------- Card Styles ---------- */
.card { background:#fff; border-radius:12px; padding:15px; box-shadow:0 4px 15px rgba(0,0,0,0.1); }
.card h3 { color:#314ce6; font-size:1.1rem; margin-bottom:5px; }
.card p { font-size:0.9rem; margin:2px 0; }
.badge { display:inline-block; padding:2px 8px; background:#e0e0e0; border-radius:6px; font-size:0.75rem; font-family:monospace; }

/* ---------- Messages ---------- */
.message { text-align:center; padding:10px; border-radius:6px; margin-bottom:15px; font-weight:500; }
.success { background:#e8f0fe; color:#314ce6; border:1px solid #91b0ff; }
.error { background:#ffebee; color:#c62828; border:1px solid #ef5350; }

/* ---------- Responsive ---------- */
@media(max-width:600px) { .grid-3 { grid-template-columns:1fr; } }
</style>
</head>
<body>

<!-- ---------- Navbar ---------- 
<div class="navbar">
  <div class="brand">Medico Manager</div>
  <div class="menu">
    <a href="dashboard.php">Home</a>
    <a href="inventory.php">Inventory Management</a>
    <a href="supplier.php">Supplier Management</a>
    <a href="add_medicine.php">Add Medicine</a>
    <a href="contactus.php">Contact Us</a>
    <a href="aboutus.php">About Us</a>
    <a href="logout.php">Logout</a>
  </div>
</div> -->

<div class="container">
<h1>Suppliers Management</h1>
<p class="subtitle">Manage your medicine suppliers</p>

<?php if($success): ?><div class="message success"><?= $success ?></div><?php endif; ?>
<?php if($error): ?><div class="message error"><?= $error ?></div><?php endif; ?>

<!-- ---------- Add Supplier Form ---------- -->
<form method="POST">
    <h2>Add New Supplier</h2>
    <div class="form-group">
        <label>Company Name *</label>
        <input type="text" name="name" required>
    </div>
    <div class="form-group">
        <label>Contact Person *</label>
        <input type="text" name="contact_person" required>
    </div>
    <div class="form-group">
        <label>Phone *</label>
        <input type="tel" name="phone" required>
    </div>
    <div class="form-group">
        <label>Email *</label>
        <input type="email" name="email" required>
    </div>
    <div class="form-group">
        <label>GST Number</label>
        <input type="text" name="gst_number">
    </div>
    <div class="form-group">
        <label>Address *</label>
        <textarea name="address" required></textarea>
    </div>
    <button type="submit">Add Supplier</button>
</form>

<!-- ---------- Suppliers Grid ---------- -->
<div class="grid grid-3">
<?php while($row = $suppliers->fetch_assoc()): ?>
    <div class="card">
        <h3><?= htmlspecialchars($row['name']) ?></h3>
        <p><strong>Contact:</strong> <?= htmlspecialchars($row['contact_person']) ?></p>
        <p>📞 <?= htmlspecialchars($row['phone']) ?></p>
        <p>✉ <?= htmlspecialchars($row['email']) ?></p>
        <p>📍 <?= htmlspecialchars($row['address']) ?></p>
        <?php if($row['gst_number']): ?>
        <p>GST: <span class="badge"><?= htmlspecialchars($row['gst_number']) ?></span></p>
        <?php endif; ?>
    </div>
<?php endwhile; ?>
</div>

</div>
</body>
</html>
<?php 
include "footer.php"
?>
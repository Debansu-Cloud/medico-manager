<?php include "navbar.php"?>
<?php
include "db_connect.php";
$supplier_result= $conn->query("SELECT id,name FROM suppliers ORDER BY name ASC");


// ---------------- Supplier and Category Data ----------------

$categories = [
    "Pain Relief", "Antibiotics", "Vitamins", "Supplements",
    "Blood Pressure", "Diabetes", "Heart", "Allergy",
    "Respiratory", "Digestive", "Skin Care", "Eye Care", "Other"
];

$success = "";
$error = "";

// ---------------- Form Submission Logic ----------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $generic = $_POST['generic'];
    $category = $_POST['category'];
    $manufacturer = $_POST['manufacturer'];
    $supplierId = $_POST['supplierId'];
    $batchNumber = $_POST['batchNumber'];
    $quantity = (int)$_POST['quantity'];
    $unit_price = (float)$_POST['unit_price'];
    $selling_price = (float)$_POST['selling_price'];
    $mrp = (float)$_POST['mrp'];
    $reorder_level = (int)$_POST['reorder_level'];
    $rack = $_POST['rack'];
    $manufacture_date = $_POST['manufacture_date'];
    $expiry_date = $_POST['expiry_date'];
    $description = $_POST['description'];
    $prescription = isset($_POST['prescription']) ? 1 : 0;

    // ---- Validation ----
    if ($selling_price < $unit_price) {
        $error = "⚠ Selling price must be greater than or equal to Unit price.";
    } elseif ($mrp < $selling_price) {
        $error = "⚠ MRP must be greater than or equal to Selling price.";
    } elseif (strtotime($expiry_date) <= strtotime($manufacture_date)) {
        $error = "⚠ Expiry date must be after Manufacturing date.";
    } else {
        // ---- Insert Data ----
        $stmt = $conn->prepare("INSERT INTO medicines 
            (name, generic, category, manufacturer, supplierId, batchNumber, quantity, unit_price, selling_price, mrp, reorder_level, rack, manufacture_date, expiry_date, description, prescription)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param(
            "sssssiidddiisssi",
            $name, $generic, $category, $manufacturer, $supplierId, $batchNumber, $quantity,
            $unit_price, $selling_price, $mrp, $reorder_level, $rack,
            $manufacture_date, $expiry_date, $description, $prescription
        );

        if ($stmt->execute()) {
            $success = "✅ Medicine added successfully!";
        } else {
            $error = "❌ Failed to add medicine: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Medicine</title>
<style>
body { font-family: Arial,sans-serif; background:#f0f4ff; margin:0; padding:0; }

.container { max-width:900px; margin:30px auto; background:white; padding:25px 30px; border-radius:15px; box-shadow:0 6px 15px rgba(0,0,0,0.1);}
h1 { text-align:center; color:#314ce6; margin-bottom:10px; }
.subtitle { text-align:center; color:#555; margin-bottom:20px; }

.form-grid { display:grid; grid-template-columns: repeat(3, 1fr); gap:15px; }
.form-grid .full-width { grid-column: 1 / -1; }
label { display:block; font-weight:500; margin-bottom:4px; color:#333; }
input, select, textarea { width:100%; padding:8px 10px; border:1px solid #ccc; border-radius:6px; font-size:14px; }
input:focus, select:focus, textarea:focus { border-color:#314ce6; box-shadow:0 0 4px rgba(49,76,230,0.4); outline:none; }
textarea {resize:none; height:70px; }
input[type=checkbox] { width:auto; transform:scale(1.2); margin-right:5px; }

.btn { display:block; width:100%; background:#314ce6; color:white; border:none; border-radius:10px; padding:12px; font-size:15px; cursor:pointer; margin-top:20px; }
.btn:hover { background:#243fcf; }

.message { text-align:center; padding:10px; border-radius:6px; margin-bottom:15px; font-weight:500; }
.success { background:#e8f0fe; color:#314ce6; border:1px solid #91b0ff; }
.error { background:#ffebee; color:#c62828; border:1px solid #ef5350; }

@media(max-width:900px){ .form-grid { grid-template-columns: repeat(2, 1fr); } }
@media(max-width:600px){ .form-grid { grid-template-columns: 1fr; } }
</style>
</head>
<body>

<!-- Navbar 
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

<!-- Page Content -->
<div class="container">
  <h1>Add New Medicine</h1>
  <p class="subtitle">Enter the medicine details below</p>

  <?php if ($success) echo "<div class='message success'>$success</div>"; ?>
  <?php if ($error) echo "<div class='message error'>$error</div>"; ?>

  <form method="POST">
    <div class="form-grid">
      <div><label>Medicine Name *</label><input type="text" name="name" required></div>
      <div><label>Generic Name *</label><input type="text" name="generic" required></div>
      <div><label>Category *</label>
        <select name="category" required>
          <?php
          foreach ($categories as $cat) {
              echo "<option value='$cat'>$cat</option>";
          }
          ?>
        </select>
      </div>

      <div><label>Manufacturer *</label><input type="text" name="manufacturer" required></div>
      <div><label>Supplier *</label>
        <select name="supplierId" required>
            <option value="">--Select Supplier--</option>
          <?php
         while ($sup= $supplier_result->fetch_assoc()): 
          ?>
          <option value="<?=$sup['id'] ?> "><?= htmlspecialchars($sup['name'])?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <div><label>Batch Number *</label><input type="text" name="batchNumber" required></div>
      <div><label>Quantity *</label><input type="number" name="quantity" min="0" required></div>
      <div><label>Unit Price *</label><input type="number" name="unit_price" step="0.01" min="0" required></div>
      <div><label>Selling Price *</label><input type="number" name="selling_price" step="0.01" min="0" required></div>
      <div><label>MRP *</label><input type="number" name="mrp" step="0.01" min="0" required></div>
      <div><label>Reorder Level *</label><input type="number" name="reorder_level" min="0" required></div>
      <div><label>Rack Number *</label><input type="text" name="rack" required></div>
      <div><label>Manufacture Date *</label><input type="date" name="manufacture_date" required></div>
      <div><label>Expiry Date *</label><input type="date" name="expiry_date" required></div>

      <div class="full-width"><label>Description</label><textarea name="description"></textarea></div>
      <div class="full-width"><label><input type="checkbox" name="prescription"> Prescription Required</label></div>
    </div>

    <button type="submit" class="btn">Add Medicine</button>
  </form>
</div>
</body>
</html>
<?php 
include "footer.php"
?>
<?php include "navbar.php"?>
<?php
include "db_connect.php";

// ---------------- Fetch distinct categories for filter ----------------
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';
$categories = array();
$catRes = $conn->query("SELECT DISTINCT category FROM medicines");
while($row = $catRes->fetch_assoc()) {
    $categories[] = $row['category'];
}

// ---------------- Fetch medicines with optional category filter ----------------
$sql = "SELECT m.*, s.name as supplier_name FROM medicines m 
        LEFT JOIN suppliers s ON m.supplierId = s.id";
if($categoryFilter) {
    $sql .= " WHERE m.category='" . $conn->real_escape_string($categoryFilter) . "'";
}
$sql .= " ORDER BY m.name ASC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inventory Management</title>
<style>
body { font-family: Arial, sans-serif; background:#f0f4ff; margin:0; padding:0; }

/* Container */
.container { max-width:1400px; margin:30px auto; background:white; padding:20px 30px; border-radius:12px; box-shadow:0 8px 20px rgba(0,0,0,0.1); }

/* Heading */
h1 { color:#314ce6; margin-bottom:20px; text-align:center; }

/* Filters */
.filter-container { display:flex; justify-content: space-between; align-items:center; flex-wrap:wrap; margin-bottom:15px; gap:10px; }
.filter-container select, .filter-container input { padding:8px 12px; border-radius:8px; border:1px solid #ccc; outline:none; font-size:14px; }

/* Table */
table { width:100%; border-collapse: collapse; margin-top:10px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
th, td { padding:12px 15px; text-align:left; font-size:14px; transition:0.2s; }
th { background:#314ce6; color:white; text-transform: uppercase; letter-spacing: 0.5px; }
tr { border-bottom:1px solid #eee; }
tr:nth-child(even) { background:#f9f9f9; }
tr:hover { background: #e3f2fd; }

/* Low stock / expired */
.low-stock { background:#fff3cd; }  /* yellow */
.expired { background:#f8d7da; }    /* red */

/* Action buttons */
.btn { padding:6px 12px; border:none; border-radius:6px; cursor:pointer; color:white; font-size:13px; transition:0.3s; text-decoration:none; display:inline-block; }
.btn.edit { background:#43a047; }
.btn.edit:hover { background:#2e7d32; }
.btn.delete { background:#c62828; }
.btn.delete:hover { background:#8e0000; }

/* Responsive */
@media(max-width:768px){
    table, thead, tbody, th, td, tr { display:block; width:100%; }
    tr { margin-bottom:15px; border-bottom:2px solid #ddd; }
    th { display:none; }
    td { display:flex; justify-content:space-between; padding:10px 5px; border:none; border-bottom:1px solid #eee; }
    td:before { content: attr(data-label); font-weight:bold; width:45%; }
}

/* Search */
#searchInput { width:200px; }
</style>
</head>
<body>

<div class="container">
<h1>Inventory Management</h1>

<div class="filter-container">
  <!-- Category Filter -->
  <form method="GET" class="filter">
    <label>Filter by Category: </label>
    <select name="category" onchange="this.form.submit()">
      <option value="">All Categories</option>
      <?php
      foreach($categories as $cat) {
          $selected = ($cat == $categoryFilter) ? 'selected' : '';
          echo "<option value='".$cat."' ".$selected.">".$cat."</option>";
      }
      ?>
    </select>
  </form>

  <!-- Search Box -->
  <div>
    <label>Search: </label>
    <input type="text" id="searchInput" placeholder="Search medicines...">
  </div>
</div>

<!-- Medicines Table -->
<table id="medicineTable">
<thead>
<tr>
  <th>Name</th>
  <th>Generic</th>
  <th>Category</th>
  <th>Supplier</th>
  <th>Quantity</th>
  <th>Selling Price</th>
  <th>Expiry Date</th>
  <th>Rack</th>
  <th>Prescription</th>
  <th>Actions</th>
</tr>
</thead>
<tbody>
<?php
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $lowStock = $row['quantity'] <= $row['reorder_level'];
        $expired = strtotime($row['expiry_date']) < time();
        $rowClass = $expired ? 'expired' : ($lowStock ? 'low-stock':'');

        echo "<tr class='".$rowClass."'>";
        echo "<td data-label='Name'>".$row['name']."</td>";
        echo "<td data-label='Generic'>".$row['generic']."</td>";
        echo "<td data-label='Category'>".$row['category']."</td>";
        echo "<td data-label='Supplier'>".$row['supplier_name']."</td>";
        echo "<td data-label='Quantity'>".$row['quantity']."</td>";
        echo "<td data-label='Selling Price'>Rs ". number_format($row['selling_price'],2)."</td>";
        echo "<td data-label='Expiry Date'>".$row['expiry_date']."</td>";
        echo "<td data-label='Rack'>".$row['rack']."</td>";
        echo "<td data-label='Prescription'>".($row['prescription'] ? 'Yes' : 'No')."</td>";
        echo "<td data-label='Actions'>
                <a href='edit_medicine.php?id=".$row['id']."' class='btn edit'>Edit</a>
                <a href='delete_medicine.php?id=".$row['id']."' class='btn delete'>Delete</a>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='10' style='text-align:center;'>No medicines found</td></tr>";
}
?>
</tbody>
</table>
</div>

<script>
// Live search
document.getElementById('searchInput').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#medicineTable tbody tr');
    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>

</body>
</html>
<?php include "footer.php" ?>

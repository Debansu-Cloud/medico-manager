<?php
session_start();
include "db_connect.php";
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user'){
    header("Location: index.php");
    exit();
}

include "user_navbar.php";   // NAVBAR now shows full width




// Add to cart
if(isset($_POST['add_to_cart'])){
    $id = $_POST['med_id'];
    $name = $_POST['med_name'];
    $price = $_POST['med_price'];
    $qty = 1;

    if(isset($_SESSION['cart'][$id])){
        $_SESSION['cart'][$id]['qty'] += 1;
    } else {
        $_SESSION['cart'][$id] = ['name'=>$name,'price'=>$price,'qty'=>$qty];
    }

    header("Location: available_Medicine.php");
    exit();
}


// Fetch medicines
$sql = "SELECT id,name,quantity,selling_price,expiry_date,prescription 
        FROM medicines ORDER BY name ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Available Medicines</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body {
    font-family: Arial, sans-serif;
    background:#f0f4ff;
    padding: 0;
    margin: 0;
}

.container{
    padding:20px;
}

h2 {
    color:#314ce6;
}

table {
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

th,td {
    border:1px solid #ccc;
    padding:8px;
    text-align:left;
}

th {
    background:#314ce6;
    color:white;
}

button {
    padding:5px 10px;
    background:#4f46e5;
    color:white;
    border:none;
    border-radius:4px;
    cursor:pointer;
}

button:hover {
    background:#4338ca;
}

a {
    text-decoration:none;
    color:#4f46e5;
    font-weight:bold;
}

input[type="text"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #4f46e5;
    border-radius: 8px;
    margin-top: 15px;
    font-size: 16px;
}

@media(max-width:768px){
    table,thead,tbody,th,td,tr{display:block;}
    tr{margin-bottom:15px;border-bottom:2px solid #ddd;}
    th{display:none;}
    td{
        display:flex;
        justify-content:space-between;
        padding:8px;
        border:none;
        border-bottom:1px solid #eee;
    }
    td:before{
        content: attr(data-label);
        font-weight:bold;
    }
}
</style>
</head>

<body>

<div class="container">

<!-- REMOVE USERNAME -->
<h2></h2>

<a href="cart.php">🛒 View Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a>

<!-- SEARCH BAR -->
<input type="text" id="searchInput" placeholder="Search medicine by name...">

<table id="medicineTable">
<tr>
<th>Name</th>
<th>Quantity</th>
<th>Price</th>
<th>Expiry</th>
<th>Prescription</th>
<th>Action</th>
</tr>

<?php
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        echo "<tr>
        <td data-label='Name' class='med-name'>{$row['name']}</td>
        <td data-label='Quantity'>{$row['quantity']}</td>
        <td data-label='Price'>{$row['selling_price']}</td>
        <td data-label='Expiry'>{$row['expiry_date']}</td>
        <td data-label='Prescription'>".($row['prescription'] ? 'Yes' : 'No')."</td>
        <td data-label='Action'>
            <form method='POST'>
                <input type='hidden' name='med_id' value='{$row['id']}'>
                <input type='hidden' name='med_name' value='{$row['name']}'>
                <input type='hidden' name='med_price' value='{$row['selling_price']}'>
                <button type='submit' name='add_to_cart'>Add to Cart</button>
            </form>
        </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='6'>No medicines found</td></tr>";
}
?>
</table>

</div>

<script>
// LIVE SEARCH FILTER
document.getElementById("searchInput").addEventListener("keyup", function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#medicineTable tr");

    rows.forEach((row, index) => {
        if (index === 0) return; // skip header row
        let medName = row.querySelector(".med-name").textContent.toLowerCase();

        row.style.display = medName.includes(filter) ? "" : "none";
    });
});
</script>
<?php 
include "footer.php";
?>
</body>
</html>

<?php
include "db_connect.php";

// ---------------- Check ID ----------------
if (!isset($_GET['id'])) {
    die("Medicine ID not provided!");
}

$id = (int)$_GET['id'];

// ---------------- Delete Medicine ----------------
$stmt = $conn->prepare("DELETE FROM medicines WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    // Redirect to inventory page after deletion
    header("Location: inventory.php?msg=deleted");
    exit();
} else {
    $error = "Failed to delete medicine: " . $stmt->error;
    $stmt->close();
    $conn->close();
    die($error);
}
?>

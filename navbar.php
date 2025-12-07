<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Navbar</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: Arial, sans-serif; }
    body { background: #f0f4f8; color: #333; line-height: 1.6; }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #314ce6ff;
      color: white;
      padding: 12px 20px;
      flex-wrap: wrap;
    }

    .navbar .brand {
      font-size: 1.5rem;
      font-weight: bold;
    }

    .navbar .menu {
      display: flex;
      gap: 20px;
      align-items: center;
    }

    .navbar .menu a {
      color: white;
      text-decoration: none;
      padding: 8px 12px;
      border-radius: 6px;
      transition: 0.3s;
    }

    .navbar .menu a:hover {
      background: rgba(255, 255, 255, 0.2);
    }

    .nav-toggle {
      display: none;
      flex-direction: column;
      cursor: pointer;
      gap: 4px;
    }

    .nav-toggle span {
      width: 25px;
      height: 3px;
      background: white;
      border-radius: 2px;
    }

    @media(max-width:768px) {
      .nav-toggle {
        display: flex;
      }

      .navbar .menu {
        width: 100%;
        flex-direction: column;
        display: none;
        margin-top: 10px;
        gap: 10px;
      }

      .navbar .menu.active {
        display: flex;
      }
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar">
    <div class="brand">Medico Manager</div>
    <div class="nav-toggle" onclick="toggleMenu(this)">
      <span></span>
      <span></span>
      <span></span>
    </div>
    <div class="menu">
       <a href="dashboard.php">Home</a>
      <a href="inventory.php">Inventory</a>
      <a href="supplier.php">Supplier</a>
      <a href="add_medicine.php">Add Medicine</a>
      <a href="add_doctor.php">Add Doctors</a>
      <a href="admin_appointments.php">Appointments</a>
      <a href="contactus.php">Contact Us</a>
      <a href="aboutus.php">About Us</a>
      <a href="logout.php">Logout</a>
    </div>
  </nav>

  <script>
    function toggleMenu(button) {
      const menu = document.querySelector('.menu');
      menu.classList.toggle('active');
    }
  </script>
</body>
</html>

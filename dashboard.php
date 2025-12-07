<?php
session_start();
include "db_connect.php";
// Check if user is logged in
if(!isset($_SESSION['username'])){
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Medico Manager Dashboard</title>
<style>
/* ---------- Global Styles ---------- */
* { box-sizing: border-box; margin: 0; padding: 0; font-family: Arial, sans-serif; }
body { background: #f0f4f8; }

/* ---------- Navbar ---------- */
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

.navbar .menu a, .navbar .menu form button {
  color: white;
  text-decoration: none;
  padding: 8px 12px;
  border-radius: 6px;
  transition: 0.3s;
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1rem;
}

.navbar .menu a:hover, .navbar .menu form button:hover {
  background: rgba(61, 51, 245, 0.2);
}

/* ---------- Hamburger (Mobile) ---------- */
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

/* ---------- Responsive ---------- */
@media(max-width:768px) {
  .nav-toggle {
    display: flex;
  }
  .navbar .menu {
    width: 100%;
    flex-direction: column;
    display: none; /* Hidden by default */
    margin-top: 10px;
    gap: 10px;
  }
  .navbar .menu.active {
    display: flex;
  }
}

/* ---------- Page Content Example ---------- */
.container { max-width: 1200px; margin: auto; padding: 20px; }
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
    <!-- Logout Button -->
    <form action="logout.php" method="POST" style="display:inline;">
      <button type="submit">Logout</button>
    </form>
  </div>
</nav>

<!-- Page Content 
<div class="container">
  <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
  <p>This is your dashboard.</p>
</div> -->

<script>
  function toggleMenu(el){
    const menu = document.querySelector('.navbar .menu');
    menu.classList.toggle('active');
    el.classList.toggle('active');
  }
</script>

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>MEDICO MANAGEMENT</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
<style>
  :root {
    --primary: #004aad;
    --secondary: #50c9ce;
    --light-bg: #f0f4ff;
    --white: #ffffff;
    --gradient: linear-gradient(90deg, #004aad, #50c9ce);
  }
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
  }
  body {
    background: var(--light-bg);
    color: #222;
    line-height: 1.6;
    scroll-behavior: smooth;
  }
  header {
    position: relative;
    background: var(--gradient);
    color: var(--white);
    text-align: center;
    padding: 100px 20px 120px;
    border-radius: 0 0 50px 50px;
    overflow: hidden;
  }
  header h1 {
    font-size: 2.8rem;
    font-weight: 700;
    margin-bottom: 10px;
  }
  header p {
    font-size: 1.2rem;
    opacity: 0.95;
    margin-bottom: 35px;
  }
  .btn-primary {
    background: var(--white);
    color: var(--primary);
    padding: 14px 38px;
    border: none;
    border-radius: 32px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 8px 22px rgba(255, 255, 255, 0.35);
  }
  .btn-primary:hover {
    transform: translateY(-4px);
    background: #f3f7ff;
  }
  section {
    max-width: 1100px;
    margin: 70px auto;
    padding: 0 20px;
    text-align: center;
  }
  section h2 {
    color: var(--primary);
    font-size: 2rem;
    margin-bottom: 22px;
    font-weight: 700;
    position: relative;
  }
  section h2::after {
    content: "";
    position: absolute;
    width: 90px;
    height: 4px;
    background: var(--gradient);
    border-radius: 3px;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
  }
  #about {
    background: var(--white);
    border-radius: 20px;
    padding: 50px 35px;
    box-shadow: 0 8px 25px rgba(0, 50, 150, 0.08);
  }
  .feature-grid {
    margin-top: 40px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px,1fr));
    gap: 28px;
  }
  .feature-card {
    background: var(--white);
    border-radius: 16px;
    padding: 35px 20px;
    box-shadow: 0 4px 15px rgba(0, 80, 200, 0.08);
    transition: all 0.35s ease;
  }
  .feature-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 25px rgba(0, 60, 220, 0.15);
  }
  .feature-card .icon {
    font-size: 2.7rem;
    background: var(--gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 8px;
  }
  .sponsors {
    background: var(--white);
    padding: 50px 0;
    border-radius: 20px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
  }
  .sponsor-track {
    display: flex;
    justify-content: center;
  }
  .sponsor-list {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    max-width: 900px;
  }
  .sponsor {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--primary);
    background: var(--light-bg);
    padding: 14px 22px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 80, 200, 0.1);
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }
  .solutions {
    background: var(--white);
    border-radius: 20px;
    padding: 60px 40px;
    box-shadow: 0 8px 25px rgba(0, 50, 150, 0.08);
    margin-top: 90px;
  }
  .solutions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 28px;
    margin-top: 40px;
  }
  .solution-box {
    border-radius: 16px;
    padding: 35px 20px;
    color: var(--primary);
    background: var(--light-bg);
    box-shadow: 0 4px 18px rgba(0, 60, 220, 0.06);
    transition: all 0.3s ease;
  }
  .solution-box:hover {
    transform: translateY(-6px);
    background: var(--white);
    box-shadow: 0 10px 25px rgba(0, 60, 220, 0.15);
  }
  .icon-large {
    font-size: 2.4rem;
    margin-bottom: 12px;
    background: var(--gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }
  @media (max-width: 768px) {
    header h1 {font-size: 2rem;}
    section h2 {font-size: 1.6rem;}
  }
</style>
</head>
<body>
<header>
<!-- Page Content -->
<div class="container">
  <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
  <p>This is your Home.</p>
</div>

</header>


<section id="about">
  <h2>Your Complete Pharmacy Partner</h2>
  <p>Medico Manager helps pharmacists providers enhance operational accuracy, and visibility through modern inventory control, and clean analytics.</p>
</section>

<section id="features">
  <h2>Powerful & Modern Features</h2>
  <div class="feature-grid">
    
    <div class="feature-card">
      <div class="icon">📦</div>
      <h3>Inventory Management</h3>
      <p>Efficiently track your medicines, stock levels, expiry dates, and reorder alerts all in one place.</p>
    </div>

    <div class="feature-card">
      <div class="icon">🏭</div>
      <h3>Supplier Management</h3>
      <p>Maintain complete supplier records — contact details, GST info, and supply tracking for better coordination.</p>
    </div>

    <div class="feature-card">
      <div class="icon">💊</div>
      <h3>Add Medicine</h3>
      <p>Quickly add new medicines with supplier details, quantity, price, and batch number using a simple form.</p>
    </div>

    <div class="feature-card">
      <div class="icon">🤝</div>
      <h3>Customer Support</h3>
      <p>Provide instant support and handle feedback seamlessly through the integrated contact system.</p>
    </div>

    <div class="feature-card">
      <div class="icon">🏬</div>
      <h3>Multi-Store Manager Access</h3>
      <p>Manage multiple branches or users under one system — ensuring consistent data access and control.</p>
    </div>

    <div class="feature-card">
      <div class="icon">📱</div>
      <h3>Responsive Design</h3>
      <p>Enjoy a seamless experience across all devices — desktop, tablet, and mobile — with a fully responsive layout.</p>
    </div>

  </div>
</section>


<section class="sponsors">
  <h2>Our Proud Sponsors</h2>
  <div class="sponsor-track" aria-label="Sponsor logos">
    <div class="sponsor-list">
      <span class="sponsor">HealthWave Labs</span>
      <span class="sponsor">BlueCross Pharma</span>
      <span class="sponsor">CareBridge Solutions</span>
      <span class="sponsor">VitalMed Distributors</span>
      <span class="sponsor">GreenMed Corp</span>
      <span class="sponsor">MediFusion Systems</span>
      <span class="sponsor">WellCare Technologies</span>
    </div>
  </div>
</section>

<section class="solutions" id="solutions">
  <h2>Our Upcoming Solutions</h2>
  <div class="solutions-grid">
    <div class="solution-box">
      <div class="icon-large">⚙️</div>
      <h3>Automated Management</h3>
      <p>Digitize your workflow, from supplier handling to prescription sales and analytics.</p>
    </div>
    <div class="solution-box">
      <div class="icon-large">🤖</div>
      <h3>AI-driven Insights</h3>
      <p>Leverage data intelligence to make informed medical store decisions effortlessly.</p>
    </div>
    <div class="solution-box">
      <div class="icon-large">📈</div>
      <h3>Advanced Analytics</h3>
      <p>Interactive charts and insights to understand sales performance and trends.</p>
    </div>
    <div class="solution-box">
      <div class="icon-large">☁️</div>
      <h3>Cloud-Synced Security</h3>
      <p>Data is backed up securely online with instant device accessibility anywhere.</p>
    </div>
    <div class="solution-box">
      <div class="icon-large">🔗</div>
      <h3>EHR Integration</h3>
      <p>Sync directly with Electronic Health Records to optimize patient interactions.</p>
    </div>
    <div class="solution-box">
      <div class="icon-large">📱</div>
      <h3>Digital Patient Engagement</h3>
      <p>Enhance customer relationships via consultations, refills, and digital reminders.</p>
    </div>
  </div>
</section>
</body>
</html>

<?php 
include "footer.php"
?>
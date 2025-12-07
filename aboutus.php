<?php
include "navbar.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us - Medico Manager</title>
<style>
/* ---------- Global Styles ---------- */
* { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', Arial, sans-serif; }
body { background: #f0f4f8; color: #333; line-height: 1.6; }

/* ---------- Page Content ---------- */
.container { max-width: 900px; margin: 50px auto; padding: 20px; }

/* ---------- Header ---------- */
h1 { color:#314ce6; margin-bottom:30px; text-align:center; font-size:2.2rem; }

/* ---------- About Text ---------- */
.about-text { 
    background:white; 
    padding:30px 25px; 
    border-radius:12px; 
    box-shadow: 0 8px 20px rgba(0,0,0,0.05); 
    margin-bottom:50px; 
}
.about-text p { margin-bottom:15px; font-size:1rem; color:#555; }

/* ---------- Team Section ---------- */
.team-section h2 { text-align:center; color:#314ce6; margin-bottom:30px; font-size:2rem; }

.team-portrait {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: 30px;
}

/* ---------- Team Cards ---------- */
.team-card {
  background: linear-gradient(135deg, #314ce6, #50c9ce);
  color:white;
  width:180px;
  padding:25px 15px;
  border-radius:12px;
  box-shadow:0 8px 20px rgba(0,0,0,0.1);
  text-align:center;
  transition:0.3s;
}

.team-card:hover { transform: translateY(-5px); box-shadow:0 12px 25px rgba(0,0,0,0.2); }

/* Icon placeholder */
.team-card .icon {
  font-size:40px;
  margin-bottom:15px;
  background: rgba(255,255,255,0.2);
  width:60px;
  height:60px;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  border-radius:50%;
}

.team-card h3 { font-size:1.2rem; margin-bottom:5px; }
.team-card p { font-size:0.95rem; color:rgba(255,255,255,0.85); }

@media(max-width:768px){
    .team-portrait { gap: 20px; }
    .team-card { width: 140px; padding: 20px 10px; }
}
</style>
</head>
<body>

<div class="container">
  <!-- About Section -->
  <div class="about-text">
    <h1>About Medico Manager</h1>
    <p>Medico Manager is a modern and user-friendly platform designed to streamline pharmacy and medical inventory management. Our goal is to make it simple for store managers to track medicines, suppliers, and stock levels efficiently.</p>
    <p>Our team consists of five dedicated developers: Bishal, Debansu, Baitanik, Bristi, and Agnik. Together, we aim to provide a reliable solution for efficient inventory management in medical stores.</p>
    <p>Our mission is to empower healthcare providers and pharmacies with the tools they need to manage their inventory effectively, reduce wastage, and ensure that essential medicines are always available to patients.</p>
  </div>

  <!-- Team Section -->
  <div class="team-section">
    <h2>Our Team</h2>
    <div class="team-portrait">
      <div class="team-card">
        <div class="icon">💻</div>
        <h3>Bishal</h3>
        <p>Front-end Developer</p>
      </div>
      <div class="team-card">
        <div class="icon">🖥️</div>
        <h3>Debansu</h3>
        <p>Back-end Developer</p>
      </div>
      <div class="team-card">
        <div class="icon">🗄️</div>
        <h3>Baitanik</h3>
        <p>Database Admin</p>
      </div>
      <div class="team-card">
        <div class="icon">🎨</div>
        <h3>Bristi</h3>
        <p>UI/UX Designer</p>
      </div>
      <div class="team-card">
        <div class="icon">⚙️</div>
        <h3>Agnik</h3>
        <p>Full Stack Developer</p>
      </div>
    </div>
  </div>
</div>

</body>
</html>
<?php include "footer.php" ?>

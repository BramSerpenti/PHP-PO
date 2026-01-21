<?php
session_start();
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "po_webapp";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

$email = $_SESSION["username"]; // ✅ veilig en correct

$stmt = $conn->prepare("SELECT role FROM users WHERE username = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($role);
$stmt->fetch();
$_SESSION["role"] = $role;
$stmt->close();

// Taken ophalen
$stmtTasks = $conn->prepare("
    SELECT titel, datum 
    FROM todo 
    WHERE person = ?
    ORDER BY datum ASC
    LIMIT 5
");
$stmtTasks->bind_param("i", $userId);
$stmtTasks->execute();
$tasks = $stmtTasks->get_result();

// Groepen ophalen
$stmtGroups = $conn->prepare("
    SELECT g.titel 
    FROM groups g
    JOIN group_members gm ON g.id = gm.group_id
    WHERE gm.user_id = ?
");
$stmtGroups->bind_param("i", $userId);
$stmtGroups->execute();
$groups = $stmtGroups->get_result();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <link rel="stylesheet" href="algemeencsspagina.css">
  <title>PlanIt</title>
  <style>
   
    .cards {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      margin-top: 40px;
    }
    .card {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      min-height: 200px;
      margin: 10px;
    }
    .card h3 {
      margin-top: 0;
      font-size: 18px;
      color: #244376;
    }
    .task-buttons {
      margin-top: 20px;
    }
       body.dark {
  background-color: #1e1e1e;
  color: #f0f0f0;
}

body.dark .card {
  background-color: #2c2c2c;
  color: #f0f0f0;
}

body.dark input,
body.dark button {
  background-color: #444;
  color: #f0f0f0;
  border: 1px solid #666;
}

body.dark .sidebar {
  background-color: #111;
  color: #f0f0f0;
}

body.dark .sidebar .nav-item {
  color: #f0f0f0;
}

body.dark .sidebar .nav-item:hover {
  background-color: #222;
}

body.dark .sidebar a {
  color: #f0f0f0;
}
.card h3 {
  margin-bottom: 10px;
  padding-bottom: 6px;
  border-bottom: 1px solid #ccc;
}


  </style>
</head>
<body <?php if (!empty($_SESSION["dark_mode"])) echo 'class="dark"'; ?>>

<div class="sidebar">
    <h1>PlanIt</h1>
<a href = homepaginaphp.php>
       <div class="nav-item">🏠 Home</div></a> 
       <a href = groups.php>   <div class="nav-item">⚡ Groups</div></a>
        <a href = Tasks.php>   <div class="nav-item">📃 My Tasks</div></a>
         <a href = Friends.php> <div class="nav-item">👥 Friends & Teachers</div></a> 
           <?php if ($_SESSION["role"] == 2): ?>

    <a href="teacher_dashboard.php">
        <div class="nav-item">📘 Teacher Dashboard</div>
    </a>
<?php endif; ?>

         <!-- https://emojipedia.org/busts-in-silhouette --> 
          <a href = Settings.php> <div class="nav-item">⚙️ Settings</div></a>
           <?php if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true): ?> 
            <a href="login.php">
                 <div style="position:absolute; bottom:20px; left:20px; color:#244376; cursor:pointer;"> 👤 Login </div> </a>

                 
<?php endif; ?>


</div>

  <div class="main">
    <div class="header">Good day!</div>
    <div class="subheader">Let's finish your group projects!</div>

    <div class="cards">
      <div class="card">
        <h3>📅 Deadlines</h3>
        <div class="task-buttons">
    <?php if ($tasks->num_rows > 0): ?>
        <?php while ($t = $tasks->fetch_assoc()): ?>
            <p><strong><?= htmlspecialchars($t['titel']) ?></strong><br>
            Deadline: <?= htmlspecialchars($t['datum']) ?></p>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Geen deadlines 🎉</p>
    <?php endif; ?>
</div>

        </div>
      </div>

      <div class="card">
       <h3>👥 Groups</h3>
<?php if ($groups->num_rows > 0): ?>
    <?php while ($g = $groups->fetch_assoc()): ?>
        <p><?= htmlspecialchars($g['titel']) ?></p>
    <?php endwhile; ?>
<?php else: ?>
    <p>Je zit nog in geen enkele groep.</p>
<?php endif; ?>

      </div>

      <div class="card">
        <h3>📃 Tasks</h3>
<?php
// Zelfde query opnieuw uitvoeren omdat de eerste while-loop hem al heeft opgebruikt
$stmtTasks->execute();
$tasks = $stmtTasks->get_result();
?>

<?php if ($tasks->num_rows > 0): ?>
    <?php while ($t = $tasks->fetch_assoc()): ?>
        <p>• <?= htmlspecialchars($t['titel']) ?></p>
    <?php endwhile; ?>
<?php else: ?>
    <p>Geen taken toegewezen.</p>
<?php endif; ?>

      </div>
    </div>
  </div>
</body>
</html>

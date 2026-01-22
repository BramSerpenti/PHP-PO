<?php
session_start();
if (!isset($_SESSION["id"]) || $_SESSION["role"] != 2) {
    header("Location: homepaginaphp.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "po_webapp");
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

$teacherId = $_SESSION["id"];

// Alle groepen waar de docent in zit
$stmtGroups = $conn->prepare("
    SELECT g.id, g.titel
    FROM groups g
    JOIN group_members gm ON g.id = gm.group_id
    WHERE gm.user_id = ?
");
$stmtGroups->bind_param("i", $teacherId);
$stmtGroups->execute();
$groups = $stmtGroups->get_result();

// Query voor leerlingen
$stmtStudents = $conn->prepare("
    SELECT u.username
    FROM users u
    JOIN group_members gm ON u.id = gm.user_id
    WHERE gm.group_id = ? AND u.role = 1
");

// Query voor taken
$stmtTasks = $conn->prepare("
    SELECT t.titel, t.info, t.datum, u.username
    FROM todo t
    JOIN users u ON t.person = u.id
    WHERE t.group_id = ?
    ORDER BY t.datum ASC
");
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
<div class="main"><div class="header">📘 Teacher Dashboard</div>

<?php while ($group = $groups->fetch_assoc()): ?>
  <div class="card">
    <h3><?= htmlspecialchars($group['titel']) ?></h3>

    <strong>👥 Leerlingen:</strong><br>
    <?php
    $stmtStudents->bind_param("i", $group['id']);
    $stmtStudents->execute();
    $students = $stmtStudents->get_result();
    while ($s = $students->fetch_assoc()) {
        echo "- " . htmlspecialchars($s['username']) . "<br>";
    }
    ?>

    <br><strong>📃 Taken & Deadlines:</strong><br>
    <?php
    $stmtTasks->bind_param("i", $group['id']);
    $stmtTasks->execute();
    $tasks = $stmtTasks->get_result();
    while ($t = $tasks->fetch_assoc()) {
        echo "<p><strong>" . htmlspecialchars($t['titel']) . "</strong><br>";
        echo "Omschrijving: " . htmlspecialchars($t['info']) . "<br>";
        echo "Deadline: " . htmlspecialchars($t['datum']) . "<br>";
        echo "Student: " . htmlspecialchars($t['username']) . "</p>";
    }
    ?>
  </div>
<?php endwhile; ?>

</div>

</body>
</html> 

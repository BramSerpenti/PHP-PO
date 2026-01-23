<?php
//https://www.w3schools.com/php/php_mysql_insert.asp
// Start the session, moet bovenaan om userinfo uit te lezen en alleen te laten zien waar iemand recht op heeft., https://www.w3schools.com/php/php_sessions.asp
session_start();
// controleert of je bent ingelogd. hulp gehad van copilet om de code van https://www.w3schools.com/php/php_sessions.asp zo aan te passen dat het hierbij past
if (!isset($_SESSION["id"])) {
     header("Location: login.php");
    exit;
}


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
<?php


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "po_webapp";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

// Verwijder taak als formulier is ingediend
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['task_id'])) {
    $taskId = $_POST['task_id'];
    $userId = $_SESSION['id'];

    $stmt = $conn->prepare("DELETE FROM todo WHERE taakid = ? AND person = ?");
    $stmt->bind_param("ii", $taskId, $userId);
    $stmt->execute();

    header("Location: Tasks.php");
    exit;
}

// Taken ophalen
$userId = $_SESSION["id"];
$stmt = $conn->prepare("
    SELECT taakid, titel, info, datum
    FROM todo
    WHERE person = ?
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$tasks = $stmt->get_result();
?>


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
  <div class="header">Tasks</div>
  <div class="cards">
    <?php if ($tasks->num_rows > 0): ?>
      <?php while ($task = $tasks->fetch_assoc()): ?>
        <div class="card">
          <h3><?= htmlspecialchars($task['titel']) ?></h3>
          <p><strong>Omschrijving:</strong><br><?= htmlspecialchars($task['info']) ?></p>
          <p><strong>Deadline:</strong><br><?= htmlspecialchars($task['datum']) ?></p>
          <form method="POST">
            <input type="hidden" name="task_id" value="<?= $task['taakid'] ?>">
            <button type="submit">Taak afronden</button>
          </form>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>Je hebt nog geen taken toegewezen gekregen.</p>
    <?php endif; ?>
  </div>
</div>

</body>
</html>
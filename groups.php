<?php
// Start the session, moet bovenaan om userinfo uit te lezen en alleen te laten zien waar iemand recht op heeft., https://www.w3schools.com/php/php_sessions.asp
session_start();
// controleert of je bent ingelogd. hulp gehad van copilet om de code van https://www.w3schools.com/php/php_sessions.asp zo aan te passen dat het hierbij past
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
};
?>



<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PlanIt</title>
  <link rel="stylesheet" href="algemeencsspagina.css">
  <style>
    .cards {
      display: flex;
      gap: 20px;
      margin-top: 40px;
      flex-wrap: wrap;
    }
    .card {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      min-height: 20px;
      min-width: 50px;
    }
    .card h3 {
      margin-top: 0;
      font-size: 18px;
      color: #244376;
      cursor: pointer;
    }
    .task-buttons {
      margin-top: 20px;
    }
    input[type="text"], input[type="email"], input[type="wachtwoord"], input[type="date"] {
      width: 90%;
      padding: 10px;
      margin-top: 10px;
      margin-right: 20px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    .overlay {
      display: none;
      position: fixed;
      top:0; left:0;
      width:100%; height:100%;
      background: rgba(0,0,0,0.5);
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }
    .popup {
      background: white;
      padding: 20px;
      border-radius: 10px;
      width: 300px;
      max-width: 90%;
    }
    
  </style>
</head>
<body>
<?php

$_SESSION["id"];
$_SESSION["username"];


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "po_webapp";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Verbinding mislukt: " . $conn->connect_error);
}
?>

<?php
// https://www.w3schools.com/php/php_mysql_insert.asp en https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php en https://stackoverflow.com/questions/41440464/prepared-statements-checking-if-row-exists

// https://www.w3schools.com/php/php_looping_foreach.asp  
if ($_SERVER["REQUEST_METHOD"] === "POST") {
     if ($_POST['type'] === 'group') {

    $leden = $_POST['leden'] ?? [];
    $teachers = $_POST['groupTeachers'] ?? [];
    $titel = $_POST['grouptitel'] ?? '';

    // Groep opslaan
    $stmt = $conn->prepare("INSERT INTO groups (titel) VALUES (?)");
    $stmt->bind_param("s", $titel);
    $stmt->execute();
    $group_id = $stmt->insert_id;
    // Voeg de maker van de groep toe als lid
    $stmt = $conn->prepare("INSERT INTO group_members (group_id, user_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $group_id, $_SESSION["id"]);
    $stmt->execute();


    // Leden opslaan
    foreach ($leden as $lid) {
        $stmt = $conn->prepare("INSERT INTO group_members (group_id, user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $group_id, $lid);
        $stmt->execute();
    }

    // Teachers opslaan
    foreach ($teachers as $teacher) {
        $stmt = $conn->prepare("INSERT INTO group_teachers (group_id, user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $group_id, $teacher);
        $stmt->execute();
    }
    header("Location: groups.php");
    exit;

}
    if ($_POST['type'] === 'task') {
 //taken opslaan
   $titeltaak = $_POST['taaktitel'] ?? '';
   $om = $_POST['taakom'] ?? '';
   $deadline = $_POST['taakdeadline'] ?? '';
   $group_id = $_POST['group_id'] ?? null;


    $stmt = $conn->prepare("INSERT INTO todo (titel, info, datum, group_id) VALUES (?,?,?,?)");
    $stmt->bind_param("sssi", $titeltaak, $om, $deadline, $group_id);
    $stmt->execute();
    $taakid = $stmt->insert_id;


}
}

  


?>

<div class="sidebar">
  <h1>PlanIt</h1>
  <a href="homepaginaphp.php"><div class="nav-item">🏠 Home</div></a>
  <a href="groups.php"><div class="nav-item">⚡ Groups</div></a>
  <a href="Tasks.php"><div class="nav-item">📃 My Tasks</div></a>
  <a href="Friends.php"><div class="nav-item">👥 Friends & Teachers</div></a>
  <a href="Settings.php"><div class="nav-item">⚙️ Settings</div></a>
  <?php if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true): ?>
    <a href="login.php">
        <div style="position:absolute; bottom:20px; left:20px; color:#244376; cursor:pointer;">
            👤 Login
        </div>
    </a>
<?php endif; ?>
</div>

<div class="main">
  <div class="header">Groups</div>
  <button class="btn" style="float: right;" onclick="openPopup()">New Group</button>

  <div class="cards">
<?php
$user_id = $_SESSION["id"];

// https://www.w3schools.com/sql/sql_join.asp en met behulp van copilet verfijnt.
$stmt = $conn->prepare("
    SELECT g.*
    FROM groups g
    JOIN group_members gm ON g.id = gm.group_id
    WHERE gm.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo '<div class="card" data-group-id="' . $row["id"] . '">';
    echo '<h3>' . htmlspecialchars($row["titel"]) . '</h3>';
    echo '<div class="task-buttons">';

// Taken ophalen voor deze groep
$groupId = $row["id"];
$stmt2 = $conn->prepare("SELECT * FROM todo WHERE group_id = ?");
$stmt2->bind_param("i", $groupId);
$stmt2->execute();
$tasks = $stmt2->get_result();

while ($task = $tasks->fetch_assoc()) {
    echo '<div class="task">' . htmlspecialchars($task["titel"]) . '</div>';
}

echo '</div>';
    echo '<button class="btn" onclick="openTaskPopup(this.parentNode)">Nieuwe Taak</button>';
    echo '</div>';
}
?>
</div>

</div>


<form method="POST" action="groups.php">
  <input type="hidden" name="type" value="group">

<div class="overlay" id="popupOverlay">
  <div class="popup">
    <h2>New Group</h2>
    <label for="ftitel">Titel</label>
    <input type="text" id="grouptitel" name="grouptitel" placeholder="Titel..."><br>

    <label>Invite Friends:</label><br>
<input type="checkbox" name="leden[]" value="1"> Friend 1<br>
<input type="checkbox" name="leden[]" value="2"> Friend 2<br>
<input type="checkbox" name="leden[]" value="3"> Friend 3<br>
<input type="checkbox" name="leden[]" value="4"> Friend 4<br>

    <label>Invite Teachers:</label><br>
<input type="checkbox" name="groupTeachers[]" value="1"> Teacher 1<br>
<input type="checkbox" name="groupTeachers[]" value="2"> Teacher 2<br>
<input type="checkbox" name="groupTeachers[]" value="3"> Teacher 3<br>
<input type="checkbox" name="groupTeachers[]" value="4"> Teacher 4<br>


    <button type="submit" class="btn" onclick="addElement()">Make</button>

    <button class="close-btn" onclick="closePopup()">❌ Sluiten</button>
  </div>
</div>
</form>

<!-- Task Popup -->
 <form method="POST" action="groups.php">
  <input type="hidden" name="type" value="task">
  <input type="hidden" name="group_id" id="group_id">

<div class="overlay" id="taskPopupOverlay">
  <div class="popup">
    <h2>Nieuwe Taak</h2>
    <input type="text" name = 'taaktitel'id="taaktitel" placeholder="Titel van taak"><br>
    <input type="text" name = "taakom" id="taakom" placeholder="Omschrijving"><br>
    <input type="date" name="taakdeadline" id="taakdeadline"><br>
     <label>verdeel taak</label><br>
<input type="checkbox" name="leden[]" value="1"> Friend 1<br>
<input type="checkbox" name="leden[]" value="2"> Friend 2<br>
<input type="checkbox" name="leden[]" value="3"> Friend 3<br>
<input type="checkbox" name="leden[]" value="4"> Friend 4<br><br>

    <button type="submit" class="btn" onclick="createTask()">Toevoegen</button>
    <button class="close-btn" onclick="closeTaskPopup()">Sluiten</button>
  </div>
</div>
</form>

<!-- Task Detail Popup -->
<div class="overlay" id="taskDetailOverlay">
  <div class="popup">
    <h2 id="taskDetailTitle"></h2>
    <p id="taskDetailDescription"></p>
    <p><strong>Deadline:</strong> <span id="taskDetailDeadline"></span></p>
    <button class="btn" onclick="finishTask()">✅ Finish Task</button>
    <button class="close-btn" onclick="closeTaskDetail()">❌ Close</button>
  </div>
</div>

<!-- Group Detail Popup -->
<div class="overlay" id="groupDetailOverlay">
  <div class="popup">
    <h2 id="GroupkDetailTitle"></h2>
    <p id="GroupDetailDescription"></p>
    <button class="close-btn" onclick="leaveGroup()">❌ Leave Group</button>
    <button class="close-btn" onclick="closeGroupDetail()">Sluiten</button>
  </div>
</div>

<script>
let currentGroupCard = null;
let currentGroupDetail = null;

/* --- Groep popup --- */
function openPopup() {
  document.getElementById('popupOverlay').style.display = 'flex';
}
function closePopup() {
  document.getElementById('popupOverlay').style.display = 'none';
}





function leaveGroup() {
  if (currentGroupDetail) currentGroupDetail.remove();
  closeGroupDetail();
}

/* --- Task popup --- */
function openTaskPopup(groupCard) {
    currentGroupCard = groupCard;

    // Haal group_id uit de kaart
    const groupId = groupCard.getAttribute('data-group-id');

    // Zet hem in het formulier
    document.getElementById('group_id').value = groupId;

    // Open popup
    document.getElementById('taskPopupOverlay').style.display = 'flex';
}
function closeTaskPopup() {
  document.getElementById('taskPopupOverlay').style.display = 'none';
}

/* --- Task systeem --- */

function finishTask() {
  let title = document.getElementById('taskDetailTitle').textContent;
  document.querySelectorAll('.task').forEach(task => {
    if (task.textContent === title && task.dataset.finished === "false") {
      task.style.textDecoration = 'line-through';
      task.dataset.finished = "true";
    }
  });
  document.getElementById('taskDetailOverlay').style.display = 'none';
}
function closeTaskDetail() {
  document.getElementById('taskDetailOverlay').style.display = 'none';
}
</script>




  





</body>
</html>
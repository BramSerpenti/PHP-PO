<?php
// Start the session, moet bovenaan om userinfo uit te lezen en alleen te laten zien waar iemand recht op heeft., https://www.w3schools.com/php/php_sessions.asp
session_start();
// controleert of je bent ingelogd. hulp gehad van copilet om de code van https://www.w3schools.com/php/php_sessions.asp zo aan te passen dat het hierbij past
if (!isset($_SESSION["id"])) {
     header("Location: login.php");
    exit;
}
$user_id = $_SESSION["id"];

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
/* Groepstitel en taken */
body.dark .task {
  background-color: #244376;
  color: #f0f0f0;
  padding: 8px;
  margin-bottom: 6px;
  border-radius: 6px;
  cursor: pointer;
}

body.dark .task:hover {
  background-color: #3a5a8c;
}

/* Header en main */
body.dark .header {
  background-color: #2c2c2c;
  color: #f0f0f0;
  padding: 10px;
  font-size: 24px;
  font-weight: bold;
}

body.dark .main {
  background-color: #1e1e1e;
}

/* Popups */
body.dark .popup {
  background-color: #2c2c2c;
  color: #f0f0f0;
}

body.dark .popup input,
body.dark .popup button {
  background-color: #444;
  color: #f0f0f0;
  border: 1px solid #666;
}

/* Overlay transparantie blijft hetzelfde */
body.dark .overlay {
  background: rgba(0, 0, 0, 0.6);
}

    
  </style>
</head>
<body <?php if (!empty($_SESSION["dark_mode"])) echo 'class="dark"'; ?>>

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
if ($_POST['type'] === 'leave_group') {
    $group_id = $_POST['group_id'];
    $user_id = $_SESSION['id'];

    // Verwijder uit members
    $stmt = $conn->prepare("DELETE FROM group_members WHERE group_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $group_id, $user_id);
    $stmt->execute();

    // Verwijder uit teachers
    $stmt = $conn->prepare("DELETE FROM group_teachers WHERE group_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $group_id, $user_id);
    $stmt->execute();

    header("Location: groups.php");
    exit;
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
 echo '<div class="card" 
        data-group-id="' . $row["id"] . '" 
        data-group-title="' . htmlspecialchars($row["titel"]) . '"
      >';

echo '<h3 onclick="openGroupDetail(this)">' . htmlspecialchars($row["titel"]) . '</h3>';
    echo '<div class="task-buttons">';

// Taken ophalen voor deze groep
$groupId = $row["id"];
$stmt2 = $conn->prepare("SELECT * FROM todo WHERE group_id = ?");
$stmt2->bind_param("i", $groupId);
$stmt2->execute();
$tasks = $stmt2->get_result();

if ($tasks->num_rows > 0) {
    while ($task = $tasks->fetch_assoc()) {
        echo '<div class="task"
                data-id="' . $task["taakid"] . '"
                data-title="' . htmlspecialchars($task["titel"]) . '"
                data-info="' . htmlspecialchars($task["info"]) . '"
                data-deadline="' . htmlspecialchars($task["datum"]) . '"
                onclick="openTaskDetail(this)"
              >' . htmlspecialchars($task["titel"]) . '</div>';
    }
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
    <button type="button" class="close-btn" onclick="closeTaskPopup()">Sluiten</button>

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
<form id="leaveGroupForm" method="POST" action="groups.php" style="display:none;">
    <input type="hidden" name="type" value="leave_group">
    <input type="hidden" name="group_id" id="leave_group_id">
</form>





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
    let groupId = currentGroupDetail.getAttribute('data-group-id');
    document.getElementById('leave_group_id').value = groupId;

    document.getElementById('leaveGroupForm').submit();
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




function openGroupDetail(el) {
    let card = el.closest('.card');
    let title = card.dataset.groupTitle;
    let groupId = card.dataset.groupId;

    document.getElementById('GroupkDetailTitle').textContent = title;
    document.getElementById('GroupDetailDescription').textContent = "Group ID: " + groupId;
    document.getElementById('groupDetailOverlay').style.display = 'flex';

    currentGroupDetail = card;
}

function closeGroupDetail() {
    document.getElementById('groupDetailOverlay').style.display = 'none';
}

let currentTaskElement = null;

function openTaskDetail(taskElement) {
    currentTaskElement = taskElement;

    document.getElementById('taskDetailTitle').textContent = taskElement.dataset.title;
    document.getElementById('taskDetailDescription').textContent = taskElement.dataset.info || "Geen omschrijving";
    document.getElementById('taskDetailDeadline').textContent = taskElement.dataset.deadline || "Geen deadline";

    document.getElementById('taskDetailOverlay').style.display = 'flex';
}
function finishTask() {
    if (!currentTaskElement) return;

    currentTaskElement.style.textDecoration = 'line-through';
    currentTaskElement.style.opacity = '0.5';

    document.getElementById('taskDetailOverlay').style.display = 'none';
}

</script>




  





</body>
</html>
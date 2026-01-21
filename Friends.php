<?php
// Start the session, moet bovenaan om userinfo uit te lezen en alleen te laten zien waar iemand recht op heeft., https://www.w3schools.com/php/php_sessions.asp
session_start();
// controleert of je bent ingelogd. hulp gehad van copilet om de code van https://www.w3schools.com/php/php_sessions.asp zo aan te passen dat het hierbij past
if (!isset($_SESSION["id"])) {
     header("Location: login.php");
    exit;
}




$currentUserId = $_SESSION['id'];

 

$host = "localhost";

$user="root";

$pass = "";

$dbname="po_webapp";

 

$conn = new mysqli($host, $user, $pass, $dbname);

if($conn->connect_error){

    die("Connectie mislukt: " . $conn->connect_error);

}

 

// vriend toevoegen

if (isset($_POST['add_friend'])) {

    $email = $_POST['person2'];

    $stmt = $conn->prepare("
        SELECT u.id
        FROM users u
        JOIN userinfo ui ON u.id = ui.user_id
        WHERE ui.email = ?
    ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $person2 = $row['id'];

        $stmt2 = $conn->prepare("
            INSERT INTO friends (person1, person2)
            VALUES (?, ?)
        ");
        $stmt2->bind_param("ii", $currentUserId, $person2);
        $stmt2->execute();
    }

    header("Location: Friends.php");
    exit;
}


 

// vriend verwijderen

if (isset($_POST['delete_friend'])) {

    $friendid = intval($_POST['friendid']);

    $stmt = $conn->prepare("
        DELETE FROM friends
        WHERE friendid = ? AND person1 = ?
        LIMIT 1
    ");
    $stmt->bind_param("ii", $friendid, $currentUserId);
    $stmt->execute();

    header("Location: Friends.php");
    exit;
}


 

// vrienden ophalen

$friends = $conn->query("
SELECT f.friendid, ui.firstname, ui.lastname, ui.email
FROM friends f
JOIN users u ON f.person2 = u.id
JOIN userinfo ui ON u.id = ui.user_id
WHERE f.person1 = $currentUserId
");

$teachers = $conn->query("
SELECT f.friendid, ui.firstname, ui.lastname, ui.email
FROM friends f
JOIN users u ON f.person2 = u.id
JOIN userinfo ui ON u.id = ui.user_id
WHERE f.person1 = $currentUserId AND u.role = 'teacher'
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

 

table {

width:100%;

border-collapse:collapse;

margin-top:15px;

}

 

th, td{

border:1px solid #ddd;

padding:10px;

text-align:left;

}

 

th {

background-color:#244376;

color:white;

}

 

tr:nth-child(even){

background-color:#f9f9f9;

}

 

button{

background:#244376;

color:white;

border:none;

padding:6px 12px;

border-radius:5px;

cursor:pointer;

}

 

button:hover { background:#1a3158; }

 

input[type="text"]{

padding:6px;

margin-right:10px;

border-radius:5px;

border:1px solid #ccc;

}

 

.add-friend-form {

margin-top:10px;

display:flex;

align-items:center;

gap:10px;

}

  </style>
</head>
<body <?php if (!empty($_SESSION["dark_mode"])) echo 'class="dark"'; ?>>
<?php

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

<div class="header">Friends</div>

 

<div class="cards">
 

<div class="card">

<h3>Voeg een vriend toe</h3>

<form method="POST" class="add-friend-form">

<label>Email:</label>

<input type="text" name="person2" required>



<button type="submit" name="add_friend">Toevoegen</button>

</form>

</div>
 

<div class="card">
<h3>Mijn vrienden</h3>

<?php if ($friends->num_rows > 0): ?>
<table>
<thead>
<tr>
<th>Voornaam</th>
<th>Achternaam</th>
<th>Email</th>
<th>Actie</th>
</tr>
</thead>

<tbody>
<?php while($row = $friends->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($row['firstname']) ?></td>
<td><?= htmlspecialchars($row['lastname']) ?></td>
<td><?= htmlspecialchars($row['email']) ?></td>
<td>
    <form method="POST">
        <input type="hidden" name="friendid" value="<?= $row['friendid'] ?>">
        <button type="submit" name="delete_friend">Verwijderen</button>
    </form>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<?php else: ?>
<p>Je hebt nog geen vrienden toegevoegd.</p>
<?php endif; ?>

</div>

 
</div>
<div class="main">

<div class="header">Teachers</div>

 

<div class="cards">
<div class="card">

<h3>Voeg een teacher toe</h3>

<form method="POST" class="add-friend-form">

<label>Email:</label>

<input type="text" name="person2" required>



<button type="submit" name="add_friend">Toevoegen</button>

</form>

</div>

 
<div class="card">
<h3>Mijn teachers</h3>

<?php if ($teachers->num_rows > 0): ?>
<table>
<thead>
<tr>
<th>Voornaam</th>
<th>Achternaam</th>
<th>Email</th>
<th>Actie</th>
</tr>
</thead>

<tbody>
<?php while($row = $teachers->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($row['firstname']) ?></td>
<td><?= htmlspecialchars($row['lastname']) ?></td>
<td><?= htmlspecialchars($row['email']) ?></td>
<td>
    <form method="POST">
        <input type="hidden" name="friendid" value="<?= $row['friendid'] ?>">
        <button type="submit" name="delete_friend">Verwijderen</button>
    </form>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<?php else: ?>
<p>Je hebt nog geen teachers toegevoegd.</p>
<?php endif; ?>

</div>

 



</div>
</div>


 

</body>

</html>
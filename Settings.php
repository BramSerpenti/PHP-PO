<?php
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
      padding: 10px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      min-height: 600px;
      min-width: 700px
    }
    .card h3 {
      margin-top: 0;
      font-size: 18px;
      color: #244376;
    }
    .card form {
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
<body <?php if (isset($_SESSION["dark_mode"]) && $_SESSION["dark_mode"]) echo 'class="dark"'; ?>>

<?php




$servername = "localhost";
$username = "root";
$password = "";
$dbname = "po_webapp";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["form_type"]) && $_POST["form_type"] === "password") {


         $user_id = $_SESSION["id"];

    $old_password = $_POST["old_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

   
    if ($new_password !== $confirm_password) {
        die("Fout: nieuwe wachtwoorden komen niet overeen.");
    }


    $stmt = $conn->prepare("SELECT wachtwoord FROM users WHERE id = ?");
  $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($current_hash);
    $stmt->fetch();
    $stmt->close();

 if (!$current_hash) {
        die("Gebruiker niet gevonden.");
    }

 
    if (!password_verify($old_password, $current_hash)) {
        die("Fout: oude wachtwoord is onjuist.");
    }

 
    $new_hash = password_hash($new_password, PASSWORD_DEFAULT);

    
    $stmt = $conn->prepare("UPDATE users SET wachtwoord = ? WHERE id = ?");
    $stmt->bind_param("si", $new_hash, $user_id);

    if ($stmt->execute()) {
        echo "Wachtwoord succesvol gewijzigd!";
    } else {
        echo "Er ging iets mis bij het opslaan.";
    }

header("Location: settings.php");
exit;

    }
     elseif (isset($_POST["form_type"]) && $_POST["form_type"] === "profile") {


$user_id = $_SESSION["id"];
    $firstname = $_POST["name"]; 
    $email = $_POST["email"];


    $stmt1 = $conn->prepare("SELECT userinfoid FROM userinfo WHERE user_id = ?");
$stmt1->bind_param("i", $user_id);
$stmt1->execute();
$stmt1->store_result();

if ($stmt1->num_rows > 0) {
    $stmt1->close();

    $stmt2 = $conn->prepare("UPDATE userinfo SET firstname = ?, email = ? WHERE user_id = ?");
    $stmt2->bind_param("ssi", $firstname, $email, $user_id);
    $stmt2->execute();
    $stmt2->close();

    $stmt3 = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
    $stmt3->bind_param("si", $email, $user_id);
    $stmt3->execute();
    $stmt3->close();


   } else {
    $stmt1->close(); // sluit de SELECT
    $stmt4 = $conn->prepare("INSERT INTO userinfo (firstname, email, user_id) VALUES (?, ?, ?)");
    $stmt4->bind_param("ssi", $firstname, $email, $user_id);
    $stmt4->execute();
    $stmt4->close();

    $stmt5 = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
    $stmt5->bind_param("si", $email, $user_id);
    $stmt5->execute();
    $stmt5->close();
}

   
    header("Location: Settings.php?success=profile");
    exit;

    } 
   elseif (isset($_POST["form_type"]) && $_POST["form_type"] === "theme") {
      
    $_SESSION["dark_mode"] = isset($_POST["dark_mode"]) ? true : false;
    header("Location: Settings.php");
    exit;

    }

}


?>


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
    <div class="header">Settings</div>
 <div class="cards">
      <div class="card">

    <!-- 1. Wachtwoord wijzigen -->
    <form action="Settings.php" method="POST" class="setting-block">
      <input type="hidden" name="form_type" value="password">
        <h2>Wachtwoord wijzigen</h2>
        

        <label for="old_password">Oude wachtwoord</label>
        <input type="password" id="old_password" name="old_password" required>
        <br>
        <label for="new_password">Nieuw wachtwoord</label>
        <input type="password" id="new_password" name="new_password" required>
        <br>
        <label for="confirm_password">Bevestig nieuw wachtwoord</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <br>
        <button type="submit" class ='btn'>Wachtwoord opslaan</button>
    </form>

    <!-- 3. Profielinformatie aanpassen -->
    <form action="Settings.php" method="POST" enctype="multipart/form-data" class="setting-block">
      <input type="hidden" name="form_type" value="profile">

        <h2>Profielinformatie</h2>

        <label for="name">Naam</label>
        <input type="text" id="name" name="name" required>
<br>
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" required>
<br>
        <button type="submit" class ='btn'>Profiel opslaan</button>
    </form>


    <!-- 4. Donkere modus toggle -->
    <form action="Settings.php" method="POST" class="setting-block">
      <input type="hidden" name="form_type" value="theme">

        <h2>Weergave</h2>

        <label>
            <input type="checkbox" name="dark_mode" <?php if (isset($_SESSION["dark_mode"]) && $_SESSION["dark_mode"]) echo "checked"; ?>>


            Donkere modus
        </label>
<br>
        <button class ='btn' type="submit">Thema opslaan</button>
    </form>



   
        </h3>
      
            
        </div>
      </div>

      
    </div>
  </div>



  
</body>
</html>
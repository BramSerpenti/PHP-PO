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
      padding: 50px;
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

    #btn {

      background: #0a6bff;
      border: none;
      padding: 10px 14px;
      color: white;
      border-radius: 6px;
      cursor: pointer;
      margin-right: 10px;
      margin-top: 50px;
      height: 40px;
    }

    input[type="text"], input[type="email"], input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-top: 10px;
      margin-right: 20px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

   
  </style>
</head>
<body>


  <div class="main">
    <div class="header">Registeren</div>
 


    <div class="cards">
      <div class="card">
        <h3></h3>
        <div class="task-buttons">
          <h3>Vul je gegevens in</h3>

        <form method="POST">

          <label for="femail">E-mail:</label>
          <input type="email" id="femail" name="femail" placeholder="e-mailadres...">

          <label for="fwachtwoord">Wachtwoord:</label>
          <input type="password" id="fwachtwoord" name="fwachtwoord" placeholder="wachtwoord...">

 <label for="status">Invite Friends:</label><br>
            <select id="status" name="status" multiple size="2">

                  <option value="1">student</option>
                  <option value="2">teacher</option>
   
    </select><br><br>

          <button type="submit" id="btn">Versturen</button>
        </form>
            
        </div>
      </div>

    

      
    </div>
  </div>
  <a href = login.php><button class = btn id = btn>Inloggen</button></a>
</body>
</html>
<!-- http://w3schools.com/php/php_forms.asp --> 
<?php
//https://www.w3schools.com/php/php_mysql_insert.asp
session_start();
//  https://www.w3schools.com/php/php_mysql_connect.asp
$servername = "localhost";
$username = "root";       
$password = "";         
$dbname = "po_webapp";         

// Maak verbinding
$conn = new mysqli($servername, $username, $password, $dbname);

// Controleer verbinding
if ($conn->connect_error) {
  die("Verbinding mislukt: " . $conn->connect_error);
};

// https://www.w3schools.com/php/php_mysql_insert.asp en https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php en https://stackoverflow.com/questions/41440464/prepared-statements-checking-if-row-exists

// Verwerk formulier alleen als het is verzonden
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST['femail'] ?? '';
  $wachtwoord = $_POST['fwachtwoord'] ?? '';
  $status = $_POST['status'] ?? []; // bij multiple select moet dit een array zijn

  // Zet status om naar string (bijv. "student,teacher")
  if (is_array($status)) {
    $statusString = implode(",", $status);
  } else {
    $statusString = $status;
  }

  // Simpele validatie
  if (!empty($email) && !empty($wachtwoord) && !empty($statusString)) {
    // Wachtwoord versleutelen vóórdat je het opslaat
    $hashed_password = password_hash($wachtwoord, PASSWORD_DEFAULT);

    // ✅ Controleer eerst of email al bestaat
    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "⚠️ Dit e-mailadres is al geregistreerd.";
    } else {
        // Prepared statement om SQL-injectie te voorkomen
        $stmt = $conn->prepare("INSERT INTO users (username, wachtwoord, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $hashed_password, $statusString);

        if ($stmt->execute()) {
          // na succesvolle insert in users:
         $_SESSION['user_id'] = $conn->insert_id;   // mysqli manier
          $_SESSION['username'] = $email;

        header("Location: userinfo.php");
        exit();

          exit();
        } else {
          echo "❌ Fout bij toevoegen: " . $stmt->error;
        }

        $stmt->close();
    }
    $check->close();

  } else {
    echo "⚠️ Vul zowel e-mail als wachtwoord in.";
  }
}

$conn->close();

?>
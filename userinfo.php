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

    input[type="text"], input[type="email"], input[type="wachtwoord"] {
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
    <div class="header">Persoonlijke informatie</div>

    <div class="cards">
      <div class="card">
        <h3></h3>
        <div class="task-buttons">
          <h3>Vul je gegevens in</h3>

          <form method="POST">
            <label for="fname">Naam:</label>
            <input type="text" id="fname" name="fname" placeholder="Je naam...">

            <label for="lastname">Achtermaam:</label>
            <input type="text" id="lastname" name="lastname" placeholder="Je Achternaam ...">

            <button type="submit" id="btn">Versturen</button>
          </form>
        </div>
      </div>
    </div>
  </div>

<?php
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

// ✅ Start sessie zodat we weten welke gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    die("⚠️ Je moet eerst inloggen.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = $_POST['fname'] ?? '';
  $lastname = $_POST['lastname'] ?? '';
}

// Simpele validatie
if (!empty($name) && !empty($lastname)) {
    $userId = $_SESSION['user_id']; // user_id uit sessie

    // ✅ Haal email (username) van de ingelogde gebruiker op
    $stmtEmail = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $stmtEmail->bind_param("i", $userId);
    $stmtEmail->execute();
    $result = $stmtEmail->get_result();
    $row = $result->fetch_assoc();
    $email = $row['username']; // hier gebruik je username als email
    $stmtEmail->close();

    // Prepared statement om SQL-injectie te voorkomen
    // ✅ Nu voegen we firstname, lastname, email én user_id toe
    $stmt = $conn->prepare("INSERT INTO userinfo (firstname, lastname, email, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $name, $lastname, $email, $userId);

    if ($stmt->execute()) {
      

      header("Location: homepaginaphp.php");
      exit();

        echo "✅ informatie succesvol toegevoegd!";
        header("Location: homepaginaphp.php");
        exit();
    } else {
        echo "❌ Fout bij toevoegen: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "⚠️ Vul zowel de velden in.";
}

$conn->close();
?>
</body>
</html>
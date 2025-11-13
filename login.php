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
    <div class="header">Inloggen</div>
 


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


          <button type="submit" id="btn">Versturen</button>
        </form>
            
        </div>
      </div>

    

      
    </div>
  </div>
  <a href = registeren.php><button class = btn id = btn>Registreren</button></a>
  <?php
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST['femail'] ?? '';
  $wachtwoord = $_POST['fwachtwoord'] ?? '';

  header("Location: homepagina.php"); //https://www.w3schools.com/php/func_network_header.asp
  exit();
} else {
  echo "geen gegevens ingevuld!";
}
?>
</body>

</html>
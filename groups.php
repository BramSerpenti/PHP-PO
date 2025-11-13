<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PlanIt</title>
  <link rel="stylesheet" href="algemeencsspagina.css">
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
  </style>
</head>
<body>

  <div class="container">
    <div class="sidebar">
      <h1>PlanIt</h1>

      <a href="homepaginaphp.php"><div class="nav-item">🏠 Home</div></a>
      <a href="groups.php"><div class="nav-item">⚡ Groups</div></a>
      <a href="Tasks.php"><div class="nav-item">📃 My Tasks</div></a>
      <a href="Friends.php"><div class="nav-item">👥 Friends & Teachers</div></a>
      <a href="Settings.php"><div class="nav-item">⚙️ Settings</div></a>

      <div style="position:absolute; bottom:20px; left:20px; color:#244376; cursor:pointer;">👤 Login</div>
    </div>

    <div class="main">
      <div class="header">Groups</div>
      <button class="btn" style="float: right;" onclick="openPopup()">New Group</button>

      <div class="cards">
        <div class="card">
          <h3>📅 Deadlines</h3>
          <div class="task-buttons">
            <!-- task buttons hier -->
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Popup -->
  <div class="overlay" id="popupOverlay">
    <div class="popup">
      <h2>Dit is een popup!</h2>
      <p>Je kunt hier inhoud plaatsen.</p>
      <button class="close-btn" onclick="closePopup()">Sluiten</button>
    </div>
  </div>

  <script>
    function openPopup() {
      document.getElementById('popupOverlay').style.display = 'flex';
    }

    function closePopup() {
      document.getElementById('popupOverlay').style.display = 'none';
    }
  </script>

</body>
</html>

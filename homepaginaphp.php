<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kumo Study Mockup</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #e8f3fa;
      display: flex;
    }
    .sidebar {
      width: 240px;
      background: #f7f9fc;
      height: 100vh;
      padding: 20px;
      box-sizing: border-box;
      border-right: 1px solid #d8e2ee;
    }
    .sidebar h2 {
      margin-top: 0;
      margin-bottom: 30px;
      font-size: 20px;
      color: #244376;
    }
    .nav-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 0;
      cursor: pointer;
      color: #244376;
      font-size: 16px;
    }
    .main {
      flex: 1;
      padding: 40px;
    }
    .header {
      font-size: 32px;
      font-weight: 600;
      color: #1b4e9b;
    }
    .header span {
      color: #0a6bff;
    }
    .subheader {
      font-size: 18px;
      margin-top: 10px;
      color: #244376;
    }
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
    .btn {
      background: #0a6bff;
      border: none;
      padding: 10px 14px;
      color: white;
      border-radius: 6px;
      cursor: pointer;
      margin-right: 10px;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>KUMO STUDY</h2>

    <div class="nav-item">🏠 Home</div>
    <div class="nav-item">⚡ My Progress</div>
    <div class="nav-item">🗓️ Planner</div>
    <div class="nav-item">⏱️ Task Timer</div>
    <div class="nav-item">🚫 Site Blocker</div>
    <div class="nav-item">🔖 My Bookmarks</div>
    <div class="nav-item">⚙️ Settings</div>

    <div style="position:absolute; bottom:20px; left:20px; color:#244376; cursor:pointer;">↳ Log out</div>
  </div>

  <div class="main">
    <div class="header">Good afternoon, <span>bram!</span></div>
    <div class="subheader">Let's start your study routine!</div>

    <div class="cards">
      <div class="card">
        <h3>📅 Today's Agenda</h3>
        <div class="task-buttons">
          <button class="btn">Task Timer</button>
          <button class="btn">Go to planner</button>
        </div>
      </div>

      <div class="card">
        <h3>👥 Collaboration</h3>
      </div>

      <div class="card">
        <h3>🔖 Most Recent Bookmarks</h3>
      </div>
    </div>
  </div>
</body>
</html>

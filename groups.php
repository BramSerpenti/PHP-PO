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

<div class="sidebar">
  <h1>PlanIt</h1>
  <a href="homepaginaphp.php"><div class="nav-item">🏠 Home</div></a>
  <a href="groups.php"><div class="nav-item">⚡ Groups</div></a>
  <a href="Tasks.php"><div class="nav-item">📃 My Tasks</div></a>
  <a href="Friends.php"><div class="nav-item">👥 Friends & Teachers</div></a>
  <a href="Settings.php"><div class="nav-item">⚙️ Settings</div></a>
  <a href="login.php"><div style="position:absolute; bottom:20px; left:20px; color:#244376; cursor:pointer;">👤 Login</div></a>
</div>

<div class="main">
  <div class="header">Groups</div>
  <button class="btn" style="float: right;" onclick="openPopup()">New Group</button>

  <div class="cards"></div>
</div>

<!-- Nieuwe Group Popup -->
<div class="overlay" id="popupOverlay">
  <div class="popup">
    <h2>New Group</h2>
    <label for="ftitel">Titel</label>
    <input type="text" id="ftitel" placeholder="Titel..."><br>

    <label for="groupFriends">Invite Friends:</label><br>
    <select id="groupFriends" name= "groupFriends[]" multiple size="4">
      <option value="1">Friend 1</option>
      <option value="2">Friend 2</option>
      <option value="3">Friend 3</option>
      <option value="4">Friend 4</option>
    </select><br><br>

    <label for="groupTeachers">Invite Teachers:</label><br>
    <select id="groupTeachers" multiple size="3">
      <option value="1">Teacher 1</option>
      <option value="2">Teacher 2</option>
      <option value="3">Teacher 3</option>
    </select><br><br>

    <button class="btn" onclick="addElement()">Make</button>
    <button class="close-btn" onclick="closePopup()">❌ Sluiten</button>
  </div>
</div>

<!-- Task Popup -->
<div class="overlay" id="taskPopupOverlay">
  <div class="popup">
    <h2>Nieuwe Taak</h2>
    <input type="text" id="taskTitle" placeholder="Titel van taak"><br>
    <input type="text" id="taskDescription" placeholder="Omschrijving"><br>
    <input type="date" id="taskDeadline"><br><br>
    <button class="btn" onclick="createTask()">Toevoegen</button>
    <button class="close-btn" onclick="closeTaskPopup()">Sluiten</button>
  </div>
</div>

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

/* --- Group toevoegen --- */
function addElement() {
  const titel = document.getElementById("ftitel").value || "Nieuwe Groep";

  const newDiv = document.createElement("div");
  newDiv.className = "card";

  const newTitle = document.createElement("h3");
  newTitle.textContent = titel;
  newTitle.onclick = () => openGroupDetail(newDiv);
  newDiv.appendChild(newTitle);

  const taskButtons = document.createElement("div");
  taskButtons.className = "task-buttons";
  newDiv.appendChild(taskButtons);

  const newTaskButton = document.createElement("button");
  newTaskButton.className = "btn";
  newTaskButton.textContent = "Nieuwe Taak";
  newTaskButton.onclick = () => openTaskPopup(newDiv);
  newDiv.appendChild(newTaskButton);

  // Meerdere friends/teachers opslaan
  const selectedFriends = Array.from(document.getElementById("groupFriends").selectedOptions).map(opt => opt.textContent);
  const selectedTeachers = Array.from(document.getElementById("groupTeachers").selectedOptions).map(opt => opt.textContent);
  newDiv.dataset.friends = selectedFriends.join(", ");
  newDiv.dataset.teachers = selectedTeachers.join(", ");

  document.querySelector(".cards").appendChild(newDiv);

  // Velden resetten
  document.getElementById("ftitel").value = "";
  document.getElementById("groupFriends").selectedIndex = -1;
  document.getElementById("groupTeachers").selectedIndex = -1;

  closePopup();
}

/* --- Group detail --- */
function openGroupDetail(groupCard) {
  currentGroupDetail = groupCard;
  document.getElementById("GroupkDetailTitle").textContent = groupCard.querySelector("h3").textContent;
  document.getElementById("GroupDetailDescription").textContent =
    "Friends: " + (groupCard.dataset.friends || "None") + "\nTeachers: " + (groupCard.dataset.teachers || "None");
  document.getElementById("groupDetailOverlay").style.display = "flex";
}

function closeGroupDetail() {
  document.getElementById("groupDetailOverlay").style.display = "none";
  currentGroupDetail = null;
}

function leaveGroup() {
  if (currentGroupDetail) currentGroupDetail.remove();
  closeGroupDetail();
}

/* --- Task popup --- */
function openTaskPopup(groupCard) {
  currentGroupCard = groupCard;
  document.getElementById('taskPopupOverlay').style.display = 'flex';
}
function closeTaskPopup() {
  document.getElementById('taskPopupOverlay').style.display = 'none';
}

/* --- Task systeem --- */
function createTask() {
  const title = document.getElementById('taskTitle').value;
  const description = document.getElementById('taskDescription').value;
  const deadline = document.getElementById('taskDeadline').value;

  if (!title || !currentGroupCard) return;

  const taskDiv = document.createElement('div');
  taskDiv.className = 'task';
  taskDiv.textContent = title;
  taskDiv.onclick = () => {
    document.getElementById('taskDetailTitle').textContent = title;
    document.getElementById('taskDetailDescription').textContent = description;
    document.getElementById('taskDetailDeadline').textContent = deadline;
    document.getElementById('taskDetailOverlay').style.display = 'flex';
    taskDiv.dataset.finished = "false";
  };

  currentGroupCard.querySelector('.task-buttons').appendChild(taskDiv);

  document.getElementById('taskTitle').value = "";
  document.getElementById('taskDescription').value = "";
  document.getElementById('taskDeadline').value = "";
  closeTaskPopup();
}

function finishTask() {
  const title = document.getElementById('taskDetailTitle').textContent;
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

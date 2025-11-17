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
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      margin-top: 40px;
    }
    .card {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      min-height: 20px;
    }
    .card h3 {
      margin-top: 0;
      font-size: 18px;
      color: #244376;
    }
    .task-buttons {
      margin-top: 20px;
    }

    input[type="text"], input[type="email"], input[type="wachtwoord"] input[type="date"] {
      width: 90%;
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

    <div class="sidebar">
      <h1>PlanIt</h1>

  <a href = homepaginaphp.php>   <div class="nav-item">🏠 Home</div></a>
    <a href = groups.php>   <div class="nav-item">⚡ Groups</div></a>
    <a href = Tasks.php>   <div class="nav-item">📃 My Tasks</div></a>
    <a href = Friends.php> <div class="nav-item">👥 Friends & Teachers</div></a> <!-- https://emojipedia.org/busts-in-silhouette -->
    <a href = Settings.php> <div class="nav-item">⚙️ Settings</div></a>
    <a href = login.php><div style="position:absolute; bottom:20px; left:20px; color:#244376; cursor:pointer;">👤 Login</div></a>
  </div>

    <div class="main">
      <div class="header">Groups</div>
      <button class="btn" style="float: right;" onclick="openPopup()">New Group</button>

      <div class="cards">
        <div class="card">
          <h3>Maatschapijleer</h3>
          <div class="task-buttons">
            <!-- task buttons hier -->
          </div>
          <button class="btn" onclick="openTaskPopup(this.parentElement)">Nieuwe Taak</button>

        </div>
    

        <div class="card">
          <h3>Informatica</h3>
          <div class="task-buttons">
            <!-- task buttons hier -->
          </div>
          <button class="btn" onclick="openTaskPopup(this.parentElement)">Nieuwe Taak</button>

        </div>
 


        <div class="card">
          <h3>Nederlands</h3>
          <div class="task-buttons">
            <!-- task buttons hier -->
          </div>
          <button class="btn" onclick="openTaskPopup(this.parentElement)">Nieuwe Taak</button>

        </div>
  

        <div class="card">
          <h3>Engels</h3>
             <div class="task-buttons">
    <!-- task buttons hier -->
     
            </div>
            
            <button class="btn" onclick="openTaskPopup(this.parentElement)">Nieuwe Taak</button>

        </div>
    


      </div>
    </div>


  <!-- Popup -->
  <div class="overlay" id="popupOverlay">
    <div class="popup">
      <h2>New group</h2>
      <p>
        <form method="POST">

          <label for="ftitel">Titel</label>
          <input type="text" id="ftitel" name="ftitel" placeholder="Titel..."><br>

        <label for="group">Invite Friends:</label><br>
          <select id="group" name="group" required>
           <!-- Opties kunnen dynamisch worden gevuld vanuit database -->
          <option value="1">Friend 1</option>
          <option value="2">Friend 2</option>
            </select><br><br>
          
        <label for="group">Invite Theacher:</label><br>
          <select id="group" name="group" required>
           <!-- Opties kunnen dynamisch worden gevuld vanuit database -->
          <option value="1">theacher 1</option>
          <option value="2">theacher 2</option>
            </select><br><br>

        </form>
            </p>
      <button class="close-btn" onclick="addElement()">Make</button>
    </div>
  </div>



  



<!-- Popup voor taak aanmaken -->
<div class="overlay" id="taskPopupOverlay">
  <div class="popup">
    <h2>Nieuwe Taak</h2>
    <input type="text" id="taskTitle" placeholder="Titel van taak"><br><br>
    <input type="text" id="taskDescription" placeholder="Omschrijving"><br><br>
    <input type="date" id="taskDeadline"><br><br>
    <button class="btn" onclick="createTask()">Toevoegen</button>
    <button class="close-btn" onclick="closeTaskPopup()">Sluiten</button>
  </div>
</div>

<!-- Popup voor taakdetails -->
<div class="overlay" id="taskDetailOverlay">
  <div class="popup">
    <h2 id="taskDetailTitle">Taak Titel</h2>
    <p id="taskDetailDescription">Omschrijving</p>
    <p><strong>Deadline:</strong> <span id="taskDetailDeadline"></span></p>
    <button class="btn" onclick="finishTask()">✅ Finish Task</button>
    <button class="close-btn" onclick="closeTaskDetail()">❌ Close</button>
  </div>
</div>


  
  <script>
    function openPopup() {
      document.getElementById('popupOverlay').style.display = 'flex';
    }

    function closePopup() {
      document.getElementById('popupOverlay').style.display = 'none';
    }

 


    // https://developer.mozilla.org/en-US/docs/Web/API/Document/createElement 
function addElement() {
  document.getElementById('popupOverlay').style.display = 'none';

  const newDiv = document.createElement("div");
  newDiv.className = "card";

  const titelInput = document.getElementById("ftitel").value;

  // h3 titel
  const newTitle = document.createElement("h3");
  newTitle.textContent = titelInput || "Nieuwe Groep";
  newTitle.onclick = function() {
    document.getElementById('popupOverlay2').style.display = 'flex';
  };
  newDiv.appendChild(newTitle);

  // task-buttons div
  const taskButtons = document.createElement("div");
  taskButtons.className = "task-buttons";
  newDiv.appendChild(taskButtons);

  // nieuwe taak knop
  const newTaskButton = document.createElement("button");
  newTaskButton.className = "btn";
  newTaskButton.textContent = "Nieuwe Taak";
  newTaskButton.onclick = function() {
    openTaskPopup(this.parentElement);
  };
  newDiv.appendChild(newTaskButton);

  // voeg toe aan container
  const container = document.querySelector(".cards");
  container.appendChild(newDiv);

  // inputveld leegmaken
  document.getElementById("ftitel").value = "";
}


  let currentGroupCard = null;

  function openTaskPopup(groupCard) {
    currentGroupCard = groupCard;
    document.getElementById('taskPopupOverlay').style.display = 'flex';
  }

  function closeTaskPopup() {
    document.getElementById('taskPopupOverlay').style.display = 'none';
  }

  function createTask() {
    const title = document.getElementById('taskTitle').value;
    const description = document.getElementById('taskDescription').value;
    const deadline = document.getElementById('taskDeadline').value;

    if (!title || !currentGroupCard) return;

    const taskDiv = document.createElement('div');
    taskDiv.className = 'task';
    taskDiv.textContent = title;

    taskDiv.onclick = function () {
      document.getElementById('taskDetailTitle').textContent = title;
      document.getElementById('taskDetailDescription').textContent = description;
      document.getElementById('taskDetailDeadline').textContent = deadline;
      document.getElementById('taskDetailOverlay').style.display = 'flex';
      taskDiv.dataset.finished = "false";
      taskDiv.dataset.ref = taskDiv;
    };

    currentGroupCard.querySelector('.task-buttons').appendChild(taskDiv);

    document.getElementById('taskTitle').value = '';
    document.getElementById('taskDescription').value = '';
    document.getElementById('taskDeadline').value = '';
    closeTaskPopup();
  }

  function finishTask() {
    const overlay = document.getElementById('taskDetailOverlay');
    const title = document.getElementById('taskDetailTitle').textContent;

    const allTasks = document.querySelectorAll('.task');
    allTasks.forEach(task => {
      if (task.textContent === title && task.dataset.finished === "false") {
        task.style.textDecoration = 'line-through';
        task.dataset.finished = "true";
      }
    });

    overlay.style.display = 'none';
  }

  function closeTaskDetail() {
    document.getElementById('taskDetailOverlay').style.display = 'none';
  }
</script>

</body>
</html>
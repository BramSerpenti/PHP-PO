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
          <h3 onclick="openPopup2()">Maatschapijleer</h3>
          <div class="task-buttons">
            <!-- task buttons hier -->
          </div>
        </div>
    

        <div class="card">
          <h3 onclick="openPopup3()">Informatica</h3>
          <div class="task-buttons">
            <!-- task buttons hier -->
          </div>
        </div>
 


        <div class="card">
          <h3 onclick="openPopup4()">Nederlands</h3>
          <div class="task-buttons">
            <!-- task buttons hier -->
          </div>
        </div>
  

        <div class="card">
          <h3 onclick="openPopup5()">Engels</h3>
             <div class="task-buttons">
    <!-- task buttons hier -->
            </div>
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




  <div class="overlay" id="popupOverlay2">
    <div class="popup">
      <h2>New Task</h2>
      <p>
        <form method="POST">

          <label for="ftask">Name</label>
          <input type="text" id="ftitel" name="ftitel" placeholder="Name of Task..."><br>

          <label for="fomschrijving">omschrijving</label>
          <input type="text" id="ftitel" name="ftitel" placeholder="........"><br>

          
          <label for="deadline">Deadline:</label><br>
          <input type="date" id="deadline" name="deadline"><br><br>

          <label for="group">Friends:</label><br>
          <select id="group" name="group" required>
           <!-- Opties kunnen dynamisch worden gevuld vanuit database -->
          <option value="1">Friend 1</option>
          <option value="2">Friend 2</option>
            </select><br><br>

          
        </form>
            </p>
      <button class="close-btn" onclick="closePopup2()">Make</button>
    </div>
  </div>


   <div class="overlay" id="popupOverlay3">
    <div class="popup">
      <h2>New Task</h2>
      <p>
        <form method="POST">

          <label for="ftask">Name</label>
          <input type="text" id="ftitel" name="ftitel" placeholder="Name of Task..."><br>

          <label for="fomschrijving">omschrijving</label>
          <input type="text" id="ftitel" name="ftitel" placeholder="........"><br>

          
          <label for="deadline">Deadline:</label><br>
          <input type="date" id="deadline" name="deadline"><br><br>

          <label for="group">Friends:</label><br>
          <select id="group" name="group" required>
           <!-- Opties kunnen dynamisch worden gevuld vanuit database -->
          <option value="1">Friend 1</option>
          <option value="2">Friend 2</option>
            </select><br><br>

          
        </form>
            </p>
      <button class="close-btn" onclick="closePopup3()">Make</button>
    </div>
  </div>

  
   <div class="overlay" id="popupOverlay4">
    <div class="popup">
      <h2>New Task</h2>
      <p>
        <form method="POST">

          <label for="ftask">Name</label>
          <input type="text" id="ftitel" name="ftitel" placeholder="Name of Task..."><br>

          <label for="fomschrijving">omschrijving</label>
          <input type="text" id="ftitel" name="ftitel" placeholder="........"><br>

          
          <label for="deadline">Deadline:</label><br>
          <input type="date" id="deadline" name="deadline"><br><br>

          <label for="group">Friends:</label><br>
          <select id="group" name="group" required>
           <!-- Opties kunnen dynamisch worden gevuld vanuit database -->
          <option value="1">Friend 1</option>
          <option value="2">Friend 2</option>
            </select><br><br>

          
        </form>
            </p>
      <button class="close-btn" onclick="closePopup4()">Make</button>
    </div>
  </div>


  
   <div class="overlay" id="popupOverlay5">
    <div class="popup">
      <h2>New Task</h2>
      <p>
        <form method="POST">

          <label for="ftask">Name</label>
          <input type="text" id="ftitel" name="ftitel" placeholder="Name of Task..."><br>

          <label for="fomschrijving">omschrijving</label>
          <input type="text" id="ftitel" name="ftitel" placeholder="........"><br>

          
          <label for="deadline">Deadline:</label><br>
          <input type="date" id="deadline" name="deadline"><br><br>

          <label for="group">Friends:</label><br>
          <select id="group" name="group" required>
           <!-- Opties kunnen dynamisch worden gevuld vanuit database -->
          <option value="1">Friend 1</option>
          <option value="2">Friend 2</option>
            </select><br><br>

          
        </form>
            </p>
      <button class="close-btn" onclick="closePopup5()">Make</button>
    </div>
  </div>




  
  <script>
    function openPopup() {
      document.getElementById('popupOverlay').style.display = 'flex';
    }

    function closePopup() {
      document.getElementById('popupOverlay').style.display = 'none';
    }


     function openPopup2() {
      document.getElementById('popupOverlay2').style.display = 'flex';
    }

    function closePopup2() {
      document.getElementById('popupOverlay2').style.display = 'none';
    }

    function openPopup3() {
      document.getElementById('popupOverlay3').style.display = 'flex';
    }

    function closePopup3() {
      document.getElementById('popupOverlay3').style.display = 'none';
    }

    function openPopup4() {
      document.getElementById('popupOverlay4').style.display = 'flex';
    }

    function closePopup4() {
      document.getElementById('popupOverlay4').style.display = 'none';
    }

    function openPopup5() {
      document.getElementById('popupOverlay5').style.display = 'flex';
    }

    function closePopup5() {
      document.getElementById('popupOverlay5').style.display = 'none';
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

  // verwijderknop
  const removeBtn = document.createElement("button");
  removeBtn.textContent = "❌ Verwijderen";
  removeBtn.style.marginTop = "10px";
  removeBtn.onclick = function() {
    removeGroup(removeBtn);
  };
  newDiv.appendChild(removeBtn);

  // voeg toe aan container
  const container = document.querySelector(".cards");
  container.appendChild(newDiv);

  // inputveld leegmaken
  document.getElementById("ftitel").value = "";
}

function removeGroup(button) {
  // button is het element waarop geklikt is
  const card = button.parentElement; // parentElement is de .card
  card.remove(); // verwijdert de card uit de DOM
}
  
  </script>

</body>
</html>

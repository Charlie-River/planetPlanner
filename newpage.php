<?php 
session_start(); // Start the session (if not already started)

//DELETE TASK AFTER 7 DAYS COMPLETE - COMPLETED
  try {
    // Get the user ID variable for the query
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        require_once("connectdb.php");

        // Getting all tasks from user
        $alltasksquery = "SELECT tasks.* FROM tasks JOIN folders ON tasks.folder_id = folders.folder_id WHERE folders.user_id = ?";

        //Prepare/Execute to avoid injections!
        $stmt = $db->prepare($alltasksquery);
        $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
        // Execute the query
        $stmt->execute();
        
        // Run the query
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Output the results for testing
        //print_r($rows);

        $alltasksquery = "DELETE FROM tasks WHERE completed_at <= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        //Prepare/Execute to avoid injections!
        $stmt = $db->prepare($alltasksquery);
        $stmt->execute();
        } else {
        // If user ID is not in the session
        echo "Issue with function: remove after 7"; }
      } 
      catch (PDOException $ex) {
        // Display an error if there is an issue connecting to the database
        echo ("Failed to connect to the database.<br>");
        echo ($ex->getMessage());
        exit;
  }

//CREATE A FOLDER - COMPLETED
if (isset($_POST['createFolder'])) {
      // Check that there is something being submitted
      if (!isset($_POST['addFolder'])) {
          // Display an error if either username or password is missing
          exit('Please enter a form name!');
      }

      // Include the file to connect to the database, get the user_id
      require_once("connectdb.php");
      $user_id = $_SESSION['user_id'];

      try {
        $addFolderQuery = 'INSERT INTO folders (user_id, folderName) VALUES (?, ?)';
        //Prepare/Execute to avoid injections!
        $stmt = $db->prepare($addFolderQuery);
        $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $_POST['addFolder'], PDO::PARAM_STR);
        // Execute the query
        $stmt->execute();
        header("Location: newpage.php");
        exit();

      } catch (PDOException $ex) {
          // Display an error if there is an issue connecting to the database
          echo ("Failed to connect to the database.<br>");
          echo ($ex->getMessage());
          exit;
      }
}

// CREATE TASKS - COMPLETED
if (isset($_POST['createTask'])) {
    // Check that there is something being submitted
    if (!isset($_POST['addTask'])) {
        // Display an error if the task name is missing
        exit('Please enter a Task!');
    }
    // Include the file to connect to the database, get the user_id and folder_id
    require_once("connectdb.php");
    $user_id = $_SESSION['user_id'];
    $folder_id = $_POST['taskid'];

     // Check if task description is provided, if not, set it to NULL or a placeholder value
     $taskDesc = isset($_POST['taskDesc']) ? $_POST['taskDesc'] : "";

    try {
        $addTask = 'INSERT INTO tasks (folder_id, taskName, taskDescription) VALUES (?, ?, ?)';
        //Prepare/Execute to avoid injections!
        $stmt = $db->prepare($addTask);
        $stmt->bindParam(1, $folder_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $_POST['addTask'], PDO::PARAM_STR);
        $stmt->bindParam(3, $taskDesc, PDO::PARAM_STR);
        // Execute the query
        $stmt->execute();
        header("Location: newpage.php");
        exit();
        
    } catch (PDOException $ex) {
        // Display an error if there is an issue connecting to the database
        echo ("Failed to connect to the database.<br>");
        echo ($ex->getMessage());
        exit;
    }
}

//FETCH ALL FOLDERS FOR DISPLAY
try {
  // Get the user ID variable for the query
  if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    require_once("connectdb.php");

    // Getting all folders that the user has to be displayed
    $foldersquery = "SELECT folders.*
                    FROM folders
                    WHERE folders.user_id = '$user_id'";
    // Run the query
    $rows = $db->query($foldersquery);
    } else {
      // If user ID is not in the session
      echo "User ID not set in the session.";
    }
  } catch (PDOException $ex) {
      echo "An error occurred: " . $ex->getMessage();
} 

// DELETE TASK - COMPLETE
if (isset($_POST['deleteTask'])) { 

  // Include the file to connect to the database, get the task_id
  require_once("connectdb.php");
  $task_id = $_POST['deleteid'];

  try {
      $deleteTask = 'DELETE FROM tasks WHERE task_id = ?';
      //Prepare/Execute to avoid injections!
      $stmt = $db->prepare($deleteTask);
      $stmt->bindParam(1, $task_id, PDO::PARAM_INT);
      // Execute the query
      $stmt->execute();
      header("Location: newpage.php");
      exit();
  } catch (PDOException $ex) {
      // Display an error if there is an issue connecting to the database
      echo ("Failed to connect to the database.<br>");
      echo ($ex->getMessage());
      exit;
  }
}

// COMPLETE TASK - COMPLETE
if (isset($_POST['completeTask'])) { 

  // Include the file to connect to the database, get the task_id
  require_once("connectdb.php");
  $task_id = $_POST['completeid'];

  try {
      $completeTask = 'UPDATE tasks SET completed = ?, completed_at = ? WHERE task_id = ?';
      $stmt = $db->prepare($completeTask);
      $stmt->bindValue(1, 1, PDO::PARAM_INT);
      $stmt->bindValue(2, date('Y-m-d'), PDO::PARAM_STR);
      $stmt->bindValue(3, $task_id, PDO::PARAM_INT);
      // Execute the query
      $stmt->execute();
      header("Location: newpage.php");
      exit();
  } catch (PDOException $ex) {
      // Display an error if there is an issue connecting to the database
      echo ("Failed to connect to the database.<br>");
      echo ($ex->getMessage());
      exit;
  }
}

//LOGOUT - COMPLETE
if (isset($_POST['logout'])) {
  // Get the user ID
  if (isset($_SESSION['user_id'])) {
      $user_id = $_SESSION['user_id'];

      require_once("connectdb.php");

      //Destroy the session
      session_unset();
      session_destroy();

      //Unset any session variables - there should be none anyway
      $_SESSION = array();

      // Expire the session cookie
      setcookie(session_name(), '', time() - 3600, '/'); // Setting expiration time to past removes the cookie
      header("Location: index.php");
      exit();
  }
}

// DELETE FOLDER FUNCTION - COMPLETE
function deleteFolder($db, $folder_id) {
  require_once("connectdb.php");
  $folder_id = $folder_id;
  try {
    $deleteFolder = 'DELETE FROM folders WHERE folder_id = ?';
    //Prepare/Execute to avoid injections!
    $stmt = $db->prepare($deleteFolder);
    $stmt->bindParam(1, $folder_id, PDO::PARAM_INT);
    // Execute the query
    $stmt->execute();
    header("Location: newpage.php");
    exit();
  } catch (PDOException $ex) {
      // Display an error if there is an issue connecting to the database
      echo ("Failed to connect to the database.<br>");
      echo ($ex->getMessage());
      exit;
  }
}

//DELETE FOLDER SWITCH - STARTS DELETE FUNCTION
if(isset($_GET['action'])) {
  $action = $_GET['action'];
  //var_dump($_GET); // testing
  
  // Check if action is delete_folder and folder_id is set
  if($action === 'delete_folder' && isset($_GET['folder_id'])) {
      $folder_id = $_GET['folder_id'];
      deleteFolder($db, $folder_id);
  } 
  else {
      echo "Invalid action or folder ID.";
  }
}

// EDIT TASK - COMPLETE
if (isset($_POST['editFolder'], $_POST['folderId'])) { 

    // Get the folder ID and new folder name from the POST parameters
    $folder_id = $_POST['folderId'];
    $newName = $_POST['editFolder'];

    try {
        // Prepare the SQL query to update the folder name
        $editFolder = 'UPDATE folders SET folderName = ? WHERE folder_id = ?';
        $stmt = $db->prepare($editFolder);
        $stmt->bindParam(1, $newName, PDO::PARAM_STR);
        $stmt->bindParam(2, $folder_id, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        // Redirect back to the page after successful update
        header("Location: newpage.php");
        exit();
    } catch (PDOException $ex) {
        // Display an error if there is an issue executing the query
        echo ("Failed to update folder name.<br>");
        echo ($ex->getMessage());
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<!-- Copy this for comment -->

<head>
	<meta charset="utf-8">
	<title>Planet Planner</title>
	<link rel="icon" href="" type="image/x-icon">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
	<link rel="stylesheet" type="text/css" href="styles/indexstylesheet.css">
  <link rel="stylesheet" type="text/css" href="styles/newpagestylesheet.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="index.js"></script>
	<script src="newpagescript.js"></script>
</head>

<header>
  <div class="heading-container">
    <div class="split-column">
      <div class="heading">
        <h1>  Hello <?php echo $_SESSION["username"]; ?></h1> 
      </div>
      <div class="icons">
        <form method="post">
          <button type="submit" name="logout" class="quit-button">
            <img src="styles/quit.png" class="quit-img" alt="Logout">
          </button>
        </form>
      </div>
    </div>
  </div>
</header>

<body>

  <div class="page-container">
    <div class="left-section">
      <div class="up">
        <button class="newFolder" onclick="openForm()"> + New Folder </button>
        <?php
        // Display the product information
        if ($rows && $rows->rowCount() > 0) {
          foreach ($rows as $row) {
          $folder_id = $row['folder_id'];
          ?>
        <button class="newFolderChild" onclick="loadTasks(<?php echo $folder_id; ?>)">
          <div class="folder-right-section ">
            <input type="hidden" name="openfolder" id="openfolder" value="<?php echo $row['folderName']; ?>">
            <?php echo $row['folderName']; ?>
          </div>
          <div class="folder-left-section">
                <span class="nestedButton" onclick="toggleDropdown(this)">
                    <img src="styles/options.png" class="optionIcon" alt="Folder Options">
                    <div class="dropdown-content">
                        <a href="?action=delete_folder&folder_id=<?php echo $folder_id; ?>">Delete Folder</a>
                        <a onclick="openEditForm(<?php echo $folder_id; ?>)">Edit Folder (In Progress)</a>
                    </div>
                </span>
            </div>
        </button>
        
        <?php
          }
        } else {
          echo "Create a folder!";
        } 
      ?>
      </div>

      <div class="down">

        <div class="addFolderPopup" id="addFolderForm">
          <form method="post" class="form-container">
            <h2> Create A Folder </h2>
            <input type="text" placeholder="Folder Name" name="addFolder">
            <div class="split-column space-around">
              <div class="split-section">
                <input type="submit" id="createFolder" name="createFolder" value="Create"/>
              </div>
              <div class="split-section">
                <input type="submit" id="closeFolder" name="closeFolder" value="Close"/>
              </div>
            </div>
          </form>
        </div>

        <div class="editFolderPopup" id="editFolderForm">
          <form method="post" class="form-container">
              <h2> Edit Your Folder </h2>
              <input type="text" placeholder="New Folder Name" id="editFolder" name="editFolder">
              <div class="split-column space-around">
                  <div class="split-section">
                      <button type="button" id="editFolderButton" onclick="openEditForm(<?php echo $folder_id; ?>)">
                          Edit
                      </button>
                  </div>
                  <div class="split-section">
                      <input type="submit" id="closeEditFolder" name="closeEditFolder" value="Close"/>
                  </div>
              </div>
          </form>
      </div>

        <div class="addTaskPopup" id="addTaskForm">
          <form method="post" class="form-container">
            <h2> New Task </h2>
            <input type="hidden" name="taskid" id="taskid" value="">
            <input type="text" placeholder="Task Name" name="addTask">
            <input type="text" placeholder="Task Description" name="taskDesc">
            <div class="split-column space-around">
              <div class="split-section">
              <input type="submit" id="createTask" name="createTask" value="Create" />
              </div>
              <div class="split-section">
                <input type="submit" id="closeTask" name="closeTask" value="Close"/>
              </div>
            </div>
          </form>
        </div>

      </div>
    </div>

    <div class="right-section">
    <h2><div class="folderHeading">  </div></h2>

    <button class="newTask" onclick="openTaskForm()"> + New Task </button>
      <div class="task-container"></div>
    </div>

    <div class="star-container"></div>

  </div>
</body>

<script src="index.js"></script>
<script src="newpagescript.js"></script>

</html>

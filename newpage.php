<?php
session_start(); // Start the session (if not already started)

// Check if the form has been submitted
if (isset($_POST['createform'])) {
    // Check that there is something being submitted
    if (!isset($_POST['addFolder'])) {
        // Display an error if either username or password is missing
        exit('Please enter a form name!');
    }

    // Include the file to connect to the database, get the user_id
    require_once("connectdb.php");
    $user_id = $_SESSION['user_id'];

    try {
      $addFolder = $db->prepare('INSERT INTO folders (user_id, folderName) VALUES (?, ?)');
      $addFolder->execute([$user_id, $_POST['addFolder']]);
      header("Location: newpage.php");
      exit();

    } catch (PDOException $ex) {
        // Display an error if there is an issue connecting to the database
        echo ("Failed to connect to the database.<br>");
        echo ($ex->getMessage());
        exit;
    }
}

try {
    // Get the user ID variable for the query
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        require_once("connectdb.php");

        // Getting products from the wishlist for the user
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
	<h1>  Hello <?php echo $_SESSION["username"]; ?></h1>
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
        ?>
        <button class="newFolder" onclick="loadTasks(<?php echo $row['folder_id']; ?>)">
          <?php echo $row['folderName']; ?>
        </button>
        <?php
            }
          } else {
              echo "No products found in the wishlist.";
            }
        ?>
      </div>

      <div class="down">

        <div class="addFolderPopup" id="addFolderForm">
          <form method="post" class="form-container">
            <h2> Create A Folder </h2>
            <input type="text" placeholder="Folder Name" name="addFolder">
            <div class="split-row">
              <div class="split-section">
                <input type="submit" id="create" name="createform" value="Create"/>
              </div>
              <div class="split-section">
                <input type="submit" id="close" name="closeform" value="Close"/>
              </div>
            </div>
          </form>
        </div>

        <div class="task-form-container" id="addTaskForm">
          <form method="post" class="task-form">
          <h2> New Task </h2>
            <h4> Name </h4>
            <input type="text" placeholder="Task Name" name="addTask">
            <h4> Description </h4>
            <input type="text" placeholder="Task Desctiption" name="addTask">
            <div class="split-row split-section">
              <input type="submit" id="create" name="createform" value="Create"/>
            </div>
            <div class="split-section">
              <input type="submit" id="close" name="closeform" value="Close"/>
            </div>
          </form>
        </div>

      </div>
    </div>

    <div class="right-section">

      <div class="task-container">
      </div>

      <button class="newTask" onclick=""> + New Task </button>


    </div>

    <div class="star-container"></div>

  </div>
</body>

<script src="index.js"></script>
<script src="newpagescript.js"></script>

</html>

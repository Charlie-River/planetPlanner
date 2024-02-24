<?php
//Test commit
session_start(); // Start the session (if not already started)

	// Check if the form has been submitted
	if (isset($_POST['submitted'])) {
			// Check if both username and password are set in the form data
			if (!isset($_POST['username'], $_POST['password'])) {
					// Display an error if either username or password is missing
					exit('Please fill both the username and password fields!');
			}

			// Include the file to connect to the database
			require_once("connectdb.php");

			try {
					// Query the database to find the matching username/password
					$stat = $db->prepare('SELECT user_id, username, password FROM users WHERE username = ?');
					$stat->execute(array($_POST['username']));

					// Fetch the result row
					$row = $stat->fetch(PDO::FETCH_ASSOC);

					// Check if a matching username is found
					if ($row) {
							// Verify the entered password against the stored hash
							if (password_verify(trim($_POST['password']), $row['password'])) {
									// Passwords match: start the session and redirect to index.php
									session_start();
									$_SESSION["user_id"] = $row['user_id'];
									$_SESSION["username"] = $row['username'];
									header("Location: newpage.php");
									exit();
							} else {
									// Passwords do not match: display an error
									echo "<p style='color:red'>Error logging in, password does not match</p>";
							}
					} else {
							// Username not found: display an error
							echo "<p style='color:red'>Error logging in, Username not found</p>";
					}
			} catch (PDOException $ex) {
					// Display an error if there is an issue connecting to the database
					echo ("Failed to connect to the database.<br>");
					echo ($ex->getMessage());
					exit;
			}
	}

	// Check if the form has been submitted
	if (isset($_POST['submitted-signup'])) {
	    // Check if both username and password are set in the form data
	    if (!isset($_POST['signup-username'], $_POST['signup-password'])) {
	        // Display an error if either username or password is missing
	        exit('Please fill both the username and password fields!');
	    }

	    // Include the file to connect to the database
	    require_once("connectdb.php");

	    // Sanitize and validate the input
	    $signupUsername = filter_var($_POST['signup-username'], FILTER_SANITIZE_STRING);
	    $signupPassword = $_POST['signup-password'];

	    // Hash the password
	    $hashedPassword = password_hash($signupPassword, PASSWORD_DEFAULT);

	    try {
	        // Check if the username is already taken
	        $checkUsername = $db->prepare('SELECT user_id FROM users WHERE username = ?');
	        $checkUsername->execute([$signupUsername]);

	        if ($checkUsername->rowCount() > 0) {
	            // Username is taken: display an error
	            echo "<p style='color:red'>Error signing up, username already exists</p>";
	        } else {
	            // Insert the new user into the database
	            $insertUser = $db->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
	            $insertUser->execute([$signupUsername, $hashedPassword]);

	            // Registration successful: redirect to login page
	            header("Location: index.php");
	            exit();
	        }
	    } catch (PDOException $ex) {
	        // Display an error if there is an issue connecting to the database
	        echo ("Failed to connect to the database.<br>");
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
	<link rel="stylesheet" type="text/css" href="styles/indexstylesheet.css">
	<script src="index.js"></script>
</head>

<header>
	<h1> Planet Planner </h1>
</header>

<body>
	<!-- Image of my spaceman, css animation added  -->
	<img src="astronaut.png" class="mySpaceman" src="image of a spaceman cartoon">

	<div class="page-container">

			<div class="login-form" id="login-form">
				<h3 class="form-heading" id="signin-form-heading"> Log In </h3>
				<form method="post" id="login-form-content">
					<input type="text" id="username" name="username" placeholder="Enter Username">
					<input type="password" id="password" name="password" placeholder="Enter Password">
					<input type="submit" id="submit" name="submitted" value="Login"/>
				</form>
				<p id="toggle-link"> Don't have an account? <a href="#" id="toggle-signup">Sign Up</a> </p>
			</div>


			<div class="signup-form hidden" id="signup-form">
				<h3 class="form-heading" id="signup-form-heading"> Sign Up </h3>
				<form method="post" id="signup-form-content">
					<input type="text" id="signup-username" name="signup-username" placeholder="Create a Username">
					<input type="password" id="signup-password" name="signup-password" placeholder="Create a Password">
					<input type="submit" id="submit-signup" name="submitted-signup" value="Sign Up"/>
				</form>
				<p id="toggle-link-signup"> Already have an account? <a href="#" id="toggle-login">Sign In</a> </p>
			</div>
		</div>

		<?php

?>

<!-- Star container, stars are separately made in javascript to avoid unnessecary code -->
<div class="star-container"></div>

</body>

<script src="index.js"></script>
</html>

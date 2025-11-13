<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" >
    <meta name="description" content="Web application development" >
    <meta name="keywords" content="PHP, HTML, CSS" >
    <meta name="author" content="Quoc Co Luc (Tyler)" >
    <title>Assignment 2 - Registration Page</title>
	<link href="style.css" rel="stylesheet" >
</head>
<body>
    <?php
      include_once "includes/header.inc";
      include_once "includes/nav.inc";
    ?>
	
	<h2>Registration Page</h2>
	<div class="content">
	  <form action = "signup.php" method = "POST">
	    <div class="group">
	       <label for="Email"><strong>Email:</strong></label>
		   <input type="email" name="email" id="Email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" >
		</div>
		
		<div class="group">
		   <label for="Prname"><strong>Profile Name:</strong></label>
		   <input type="text" name="prname" id="Prname" value="<?php echo isset($_POST['prname']) ? htmlspecialchars($_POST['prname']) : ''; ?>" >
		</div>
		
		<div class="group">
		   <label for="Pass"><strong>Password:</strong></label>
		   <input type="password" name="pass" id="Pass" >
		</div>
		
		<div class="group">
		   <label for="Cpass"><strong>Confirm Password:</strong></label>
		   <input type="password" name="cpass" id="Cpass" >
		</div>
		
		<p class="buttons">
           <input type="submit" value="Register" >
           <input type="reset" value="Clear" >
        </p>
	  </form>
	</div>
	
	<a href="index.php" class="link">Home Page</a>

	<?php
	      session_start();

          if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST["email"]) && isset($_POST["prname"]) && isset($_POST["pass"]) && isset($_POST["cpass"])) {
               $email = $_POST["email"];
               $prname = $_POST["prname"];
               $pass = $_POST["pass"];
		       $cpass = $_POST["cpass"];

               require_once("settings.php");
			   $conn = @mysqli_connect($host, $user, $pswd) or die('Fail to connect to server');
               @mysqli_select_db($conn, $dbnm) or die('Database not available');

               function sanitise_input($data)
               {
                   $data = trim($data);
                   $data = stripslashes($data);
                   $data = htmlspecialchars($data);
                   return $data;
               }
            
			   if (isset($_POST["email"])) {
				  $email = $_POST["email"];
				  $email = sanitise_input($email);
			   } 
	  
			   if (isset($_POST["prname"])) {
				  $prname = $_POST["prname"];
				  $prname = sanitise_input($prname);
			   } 
		 
			   if (isset($_POST["pass"])) {
				  $pass = $_POST["pass"];
				  $pass = sanitise_input($pass);
			   } 
		 
			   if (isset($_POST["cpass"])) {
				  $cpass = $_POST["cpass"];
				  $cpass = sanitise_input($cpass);
			   } 

			   $errMsg = "";
			   if ($email == "") {
				   $errMsg .= "<p style='text-align: center; font-style: italic;'>You must enter the email!</p>";
			   } else if (!preg_match("/^[a-zA-Z0-9.]+@[a-zA-Z0-9]+\.[a-zA-Z0-9]+$/", $email)) {
				   $errMsg .= "<p style='text-align: center; font-style: italic;'>The email must be in the correct format!</p>";
			   } else {
				   $query = "SELECT * FROM `friends` WHERE `friend_email` = '$email'";
				   $result = mysqli_query($conn, $query);
			       if ($result) {
					  if (mysqli_num_rows($result) > 0) {
						$errMsg .= "<p style='text-align: center; font-style: italic;'>Email already exists, please enter another email!</p>";
					  } 
				   } else {
					    $errMsg .= "<p style='text-align: center; font-style: italic;'>Error with retrieving email: " . mysqli_error($conn) . "</p>";
				   }
			   }
			
               if ($prname == "") {
				    $errMsg .= "<p style='text-align: center; font-style: italic;'>You must enter the profile name!</p>";
			   } else if (!preg_match("/^[a-zA-Z ]+$/", $prname)) {
				    $errMsg .= "<p style='text-align: center; font-style: italic;'>The profile name must contain only letters!</p>";
			   }
		
			   if ($pass == "") {
				    $errMsg .= "<p style='text-align: center; font-style: italic;'>You must enter the password!</p>";
			   } else if (!preg_match("/^[a-zA-Z0-9]+$/", $pass)) {
				    $errMsg .= "<p style='text-align: center; font-style: italic;'>The password must contain only letters and numbers!</p>";
			   }
		
			   if ($cpass == ""){
				    $errMsg .= "<p style='text-align: center; font-style: italic;'>You must enter the password to confirm!</p>";
			   } else if (!preg_match("/^[a-zA-Z0-9]+$/", $cpass)) {
				    $errMsg .= "<p style='text-align: center; font-style: italic;'>The password to confirm must contain only letters and numbers!</p>";
			   } else if ($cpass != $pass) {
				    $errMsg .= "<p style='text-align: center; font-style: italic;'>Password do not match, please try again!</p>";
			   }

			   if (!empty($errMsg)) {
                    echo "$errMsg";
               } else {
				    $currentDate = date("Y-m-d");
				    $num_of_friends = 0;
				    $sql_table = 'friends';
				    $query1 = "INSERT INTO $sql_table (friend_email, password, profile_name, date_started, num_of_friends) VALUES ('$email', '$pass', '$prname', '$currentDate', '$num_of_friends')";
				    $result1 = mysqli_query($conn, $query1);   
                    if ($result1) {
				        $_SESSION["email"] = $email;
				        $_SESSION["prname"] = $prname;
						$_SESSION["loggedin"] = "true";
						header("Location: friendadd.php");
                        exit();
                    } else {
                        echo "<p style='text-align: center; font-style: italic;'>Error when registering: " . mysqli_error($conn) . "</p>";
                    }
				}
				
			   mysqli_close($conn);
           } else {
                echo "<p style='text-align: center; font-style: italic;'>Please fill in the required fields!</p>";
           }
         }
    ?>

	<?php
       include_once "includes/footer.inc";
    ?>
</body>
</html>
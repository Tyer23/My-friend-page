<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" >
    <meta name="description" content="Web application development" >
    <meta name="keywords" content="PHP, HTML, CSS" >
    <meta name="author" content="Quoc Co Luc (Tyler)" >
    <title>Assignment 2 - Log In Page</title>
	<link href="style.css" rel="stylesheet" >
</head>
<body>
    <?php
      include_once "includes/header.inc";
      include_once "includes/nav.inc";
    ?>
	
	<h2>Log In Page</h2>
	<div class="content">
	  <form action = "login.php" method = "POST">
	    <div class="group">
	       <label for="Email"><strong>Email:</strong></label>
		   <input type="email" name="email" id="Email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" >
		</div>
		
		<div class="group">
		   <label for="Pass"><strong>Password:</strong></label>
		   <input type="password" name="pass" id="Pass" >
		</div>
		
		<p class="buttons">
           <input type="submit" value="Log In" >
           <input type="reset" value="Clear" >
        </p>
	  </form>
	</div>
	
	<a href="index.php" class="link">Home Page</a>

	<?php
	      session_start();

          if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST["email"]) && isset($_POST["pass"])) {
               $email = $_POST["email"];
               $pass = $_POST["pass"];

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
		 
			   if (isset($_POST["pass"])) {
				  $pass = $_POST["pass"];
				  $pass = sanitise_input($pass);
			   } 
		 
			   $errMsg = "";
               if ($email == "") {
				  $errMsg .= "<p style='text-align: center; font-style: italic;'>You must enter the email!</p>";
			   } else if (!preg_match("/^[a-zA-Z0-9.]+@[a-zA-Z0-9]+\.[a-zA-Z0-9]+$/", $email)) {
				  $errMsg .= "<p style='text-align: center; font-style: italic;'>The email must be in correct format!</p>";
			   } 
			
			   if ($pass == "") {
				    $errMsg .= "<p style='text-align: center; font-style: italic;'>You must enter the password!</p>";
			   } else if (!preg_match("/^[a-zA-Z0-9]+$/", $pass)) {
				    $errMsg .= "<p style='text-align: center; font-style: italic;'>The password must contain only letters and numbers!</p>";
			   }

			   if (!empty($errMsg)) {
                    echo "$errMsg";
               } else {
				    $sql_table = 'friends';
				    $query = "SELECT * FROM $sql_table WHERE friend_email = '$email' AND password = '$pass'";
				    $result = mysqli_query($conn, $query);   
                    if (mysqli_num_rows($result) == 1) {
                         $row = mysqli_fetch_assoc($result);
                         $_SESSION['loggedin'] = true;
                         $_SESSION['email'] = $row['friend_email'];
                         $_SESSION['prname'] = $row['profile_name'];
						 $_SESSION["pass"] = $pass;
                         header("Location: friendlist.php");
                         exit();
                    } else {
                         echo "<p style='text-align: center; font-style: italic;'>The email or password is not correct. Please try again!</p>";
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
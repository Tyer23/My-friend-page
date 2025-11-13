<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Web application development">
    <meta name="keywords" content="PHP, HTML, CSS">
    <meta name="author" content="Quoc Co Luc (Tyler)">
    <title>Assignment 2 - Friend List Page</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <?php
        include_once "includes/header.inc";
        include_once "includes/nav.inc";
    ?>

    <?php
           session_start(); 
           require_once("settings.php");
           $conn = @mysqli_connect($host, $user, $pswd) or die('Fail to connect to server');
           @mysqli_select_db($conn, $dbnm) or die('Database not available');

           if (!isset($_SESSION['loggedin'])) {
               header("Location: login.php");
               exit();
           }

           if (!isset($_SESSION['num_of_friends'])) {
               $_SESSION['num_of_friends'] = 0;
           }

           if (!isset($_SESSION['friend_id'])) {
               $_SESSION['friend_id'] = null; // Set friend_id as null
           }

           $friend_id = $_SESSION['friend_id'];
           $num_of_friends = $_SESSION['num_of_friends'];
           $email = $_SESSION['email'];
           $prname = $_SESSION['prname'];

           echo "<h2>My Friend System</h2>";
           echo "<h3>{$prname}'s Friend List Page</h3>";
           echo '<h3>Total number of friends is ' . $num_of_friends . '</h3>';

           $conn = @mysqli_connect($host, $user, $pswd) or die('Fail to connect to server');
           @mysqli_select_db($conn, $dbnm) or die('Database not available');

           $sql_table = 'myfriends';
           $sql_table1 = 'friends';

           // Query for showing friend list using friend_id1 from 'myfriends' table and execute the queru
           $query = "SELECT * FROM $sql_table WHERE `friend_id1` = '$friend_id'";
           $result = mysqli_query($conn, $query);

           if (mysqli_num_rows($result) > 0) {
                echo '<table style="border: 1px solid black;">';
                echo '<tr><th style="border: 1px solid black;">Profile Name</th>';
                echo '<th style="border: 1px solid black;">Action</th></tr>';

                while ($row = mysqli_fetch_assoc($result)) {
                    $friend_id1 = $row['friend_id2'];
                    $query1 = "SELECT * FROM $sql_table1 WHERE friend_id = '$friend_id1'";
                    $result1 = mysqli_query($conn, $query1);

                    if (mysqli_num_rows($result1) > 0) {
                         while ($row1 = mysqli_fetch_assoc($result1)) {
                            $profile_name = $row1['profile_name'];

                            echo '<tr>';
                            echo '<td style="border: 1px solid black;">' . $profile_name . '</td>';
                            echo '<td style="border: 1px solid black;">';
                            echo '<form method="POST">';
                            echo '<input type="hidden" name="friend_id" value="' . $friend_id1 . '">';
                            echo '<input type="submit" name="unfriend" value="Unfriend">';
                            echo '</form>';
                            echo '</td>';
                            echo '</tr>';
                         }
                    }
                }
                echo '</table>';
           } else {
                echo "<p style='text-align: center; font-style: italic;'>You currently have no friends.</p>";
           }

           // Logic when pushing "Unfriend" button 
           if (isset($_POST["unfriend"])) {
               $friend_id2 = $_POST["friend_id"];
    
               // Query for deleting friends using friend_id1 and friend_id2 from 'myfriends' table and execute the query
               $query2 = "DELETE FROM $sql_table WHERE `friend_id1` = '$friend_id' AND `friend_id2` = '$friend_id2'";
               $result2 = mysqli_query($conn, $query2);

               if ($result2) {
                   // Query for updating the total number of friends after deleting and execute the query
                   $query3 = "UPDATE $sql_table1 SET `num_of_friends` = (SELECT COUNT(*) FROM $sql_table WHERE `friend_id1` = '$friend_id') WHERE `friend_id` = '$friend_id'";
                   $result3 = mysqli_query($conn, $query3);

                   if ($result3) {
                      // Query for updating the num_of_friends session 
                      $query4 = "SELECT * FROM $sql_table1 WHERE `friend_id` = '$friend_id'";
                      $result4 = mysqli_query($conn, $query4);
                      $row2 = mysqli_fetch_assoc($result4);
                      $_SESSION['num_of_friends'] = $row2['num_of_friends'];

                      // Display success message
                      $_SESSION['success'] = "Successfully deleted!";
                      header("Location: friendlist.php");
                      exit();
                   }
                } else {
                     echo "<p style='text-align: center; font-style: italic;'>Unable to remove friend.</p>";
                }
           }

           // Display success message
           if (isset($_SESSION['success'])) {
               echo "<p style='text-align: center; font-style: italic;'>{$_SESSION['success']}</p>";
               unset($_SESSION['success']); 
           }
      ?>
    </div>

    <a href="friendadd.php" class="link">Add Friends</a>
    <a href="logout.php" class="link">Log Out</a>

    <?php
       include_once "includes/footer.inc";
    ?>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="description" content="Web application development" >
    <meta name="keywords" content="PHP, HTML, CSS" >
    <meta name="author" content="Quoc Co Luc (Tyler)" >
    <title>Assignment 2 - Friend Add Page</title>
    <link href="style.css" rel="stylesheet" >
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

      $sql_table = 'myfriends';
      $sql_table1 = 'friends';

      if (!isset($_SESSION['loggedin'])) {
        $_SESSION['num_of_friends'] = 0;
        header("Location: login.php");
        exit();
      }

      if (!isset($_SESSION['num_of_friends'])) {
        $_SESSION['num_of_friends'] = 0;
      }
      if (!isset($_SESSION['email'])) {
        $_SESSION['email'] = '';
      }
      if (!isset($_SESSION['prname'])) {
        $_SESSION['prname'] = '';
      }

      $num_of_friends = $_SESSION['num_of_friends'];
      $email = $_SESSION['email'];
      $prname = $_SESSION['prname'];

      // Set friend_id using query
      if (!isset($_SESSION['friend_id'])) {
           $query = "SELECT friend_id FROM friends WHERE friend_email = '$email'";
           $result = mysqli_query($conn, $query);
           if ($row = mysqli_fetch_assoc($result)) {
               $_SESSION['friend_id'] = $row['friend_id']; 
           } else {
               echo "<p style='text-align: center; font-style: italic;'>Error retrieving friend ID!</p>";
           }
      }
      $friend_id = $_SESSION['friend_id'];

      $query1 = "SELECT friend_id, profile_name, num_of_friends FROM $sql_table1 WHERE friend_email = '$email'";
      $result1 = mysqli_query($conn, $query1);
      $row1 = mysqli_fetch_assoc($result1);

      $friend_id1 = $row1['friend_id'];
      $num_of_friends1 = $row1['num_of_friends'];
      
      echo "<h2>My Friend System</h2> ";
      echo "<h3>{$prname}'s Add Friend Page</h3>";
      echo '<h3>Total number of friends is ' . $num_of_friends1 . '</h3>';
    
      // Display success message 
      if (isset($_SESSION['success'])) {
          echo "<p style='text-align: center; font-style: italic;'>Add friend successfully!</p>";
          unset($_SESSION['success']); 
      }

      // Logic when pressing the add friend button
      if (isset($_POST["addfriend"])) {
          $friend_id2 = $_POST["friend_id"];

          // Query for inserting the friend into the myfriends table
          $query2 = "INSERT INTO $sql_table (friend_id1, friend_id2) VALUES ($friend_id1, $friend_id2)";
          mysqli_query($conn, $query2);

          // Query for updating the number of friends 
          $num_of_friends1++;
          $query3 = "UPDATE $sql_table1 SET num_of_friends = $num_of_friends1 WHERE friend_id = $friend_id1";
          mysqli_query($conn, $query3);
          $_SESSION['num_of_friends'] = $num_of_friends1;

          // Query for updating the number of friends of the added friend
          $query4 = "SELECT num_of_friends FROM $sql_table1 WHERE friend_id = $friend_id2";
          $result2 = mysqli_query($conn, $query4);
          $row2 = mysqli_fetch_assoc($result2);
          $num_of_friends2 = $row2['num_of_friends'] + 1;

          $query5 = "UPDATE $sql_table1 SET num_of_friends = $num_of_friends2 WHERE friend_id = $friend_id2";
          mysqli_query($conn, $query5);

          $_SESSION['success'] = true;

          // Redirect 
          header("Location: friendadd.php?page={$_POST['page']}");
          exit();
      }

      // Define variables for pagination
      $limit = 10;
      if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) {
          $page = $_GET['page'];
      } else {
          $page = 1;
      }
      $pagination = ($page - 1) * $limit;

      // Query for pagination
      $query6 = "SELECT friend_id, profile_name FROM $sql_table1 WHERE friend_id NOT IN 
                (SELECT friend_id1 FROM $sql_table WHERE friend_id2 = '$friend_id1' UNION 
                 SELECT friend_id2 FROM $sql_table WHERE friend_id1 = '$friend_id1')
                AND friend_id != '$friend_id1' LIMIT $pagination, $limit";
      $result3 = mysqli_query($conn, $query6);

      if (mysqli_num_rows($result3) > 0) {
        echo '<table style="border: 1px solid black;" >';
        echo '<tr>';
        echo '<th style="border: 1px solid black;" >Name</th>';
        echo '<th style="border: 1px solid black;" >Mutual Friends</th>';
        echo '<th style="border: 1px solid black;" >Action</th>';
        echo '</tr>';

        while ($row3 = mysqli_fetch_assoc($result3)) {
            $friend_id3 = $row3['friend_id'];
            $profile_name = $row3['profile_name'];

            // Query for counting mutual friends
            $query7 = "SELECT COUNT(*) AS mutual_friends FROM $sql_table
                        WHERE (friend_id1 = '$friend_id3' AND friend_id2 IN 
                        (SELECT friend_id2 FROM $sql_table WHERE friend_id1 = '$friend_id1' 
                        UNION SELECT friend_id1 FROM $sql_table WHERE friend_id2 = '$friend_id1'))
                        OR (friend_id2 = '$friend_id3' AND friend_id1 IN 
                        (SELECT friend_id2 FROM $sql_table WHERE friend_id1 = '$friend_id1' 
                        UNION SELECT friend_id1 FROM $sql_table WHERE friend_id2 = '$friend_id1'))";
            $result4 = mysqli_query($conn, $query7);
            $count = mysqli_fetch_assoc($result4)['mutual_friends'];

            echo '<tr>';
            echo '<td style="border: 1px solid black;" >' . $profile_name . '</td>';
            echo '<td style="border: 1px solid black;" >' . $count . ' mutual friends</td>';
            echo '<td style="border: 1px solid black;" >';
            echo '<form method="POST">';
            echo '<input type="hidden" name="friend_id" value="' . $friend_id3 . '">';
            echo '<input type="submit" name="addfriend" value="Add as friend">';
            echo '</form>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';
      } else {
        echo "<p style='text-align: center; font-style: italic;'>There are no friends to add!</p>";
      }

      //Query for navigating pages for pagination
      $query8 = "SELECT COUNT(*) AS total FROM $sql_table1 WHERE friend_id NOT IN 
                (SELECT friend_id1 FROM $sql_table WHERE friend_id2 = '$friend_id1' UNION 
                 SELECT friend_id2 FROM $sql_table WHERE friend_id1 = '$friend_id1')
                AND friend_id != '$friend_id1'";
      $result5 = mysqli_query($conn, $query8);
      $total = mysqli_fetch_assoc($result5)['total'];
      $total_pages = ceil($total / $limit);

      if ($page > 1) {
          echo '<a href="?page=' . ($page - 1) . '">Previous</a> ';
      }
      if ($page < $total_pages) {
          echo '<a href="?page=' . ($page + 1) . '">Next</a>';
      }

      mysqli_close($conn);
    ?>
    
    <a href="friendlist.php" class="link">Friend Lists</a>
    <a href="logout.php" class="link">Log Out</a>

    <?php
       include_once "includes/footer.inc";
    ?>
</body>
</html>

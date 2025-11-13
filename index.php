<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" >
    <meta name="description" content="Web application development" >
    <meta name="keywords" content="PHP, HTML, CSS" >
    <meta name="author" content="Quoc Co Luc (Tyler)" >
    <title>Assignment 2 - Index Page</title>
	<link href="style.css" rel="stylesheet" >
</head>
<body>
    <?php
      include_once "includes/header.inc";
      include_once "includes/nav.inc";
    ?>
    
    <h2>Assignment Home Page</h2>

	<div class="content">
	   <p><strong>Name:</strong> Quoc Co Luc (Tyler)</p>
       <br>
	   <p><strong>Student ID:</strong> 103830572</p>
       <br>
	   <p><strong>Email:</strong> 103830572@student.swin.edu.au</p>
       <br>
	   <p><em>I declare that this assignment is my individual work. I have not worked 
	       collaboratively, nor have I copied from any other student’s work or from any other source.</em></p>
    </div>

    <?php
        require_once("settings.php");

        $conn = @mysqli_connect($host, $user, $pswd) or die('Fail to connect to server');
        @mysqli_select_db($conn, $dbnm) or die('Database not available');

        // Query for creating the "friends" table and execute the query
        $sql_table = "friends";
        $query = "CREATE TABLE IF NOT EXISTS $sql_table (
                           friend_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                           friend_email VARCHAR(50) NOT NULL,
                           password VARCHAR(20) NOT NULL,
                           profile_name VARCHAR(30) NOT NULL,
                           date_started DATE NOT NULL,
                           num_of_friends INT UNSIGNED NOT NULL
                   )";

        $result = mysqli_query($conn, $query);
        if ($result) {
            echo "<p style='text-align: center; font-style: italic;'>'$sql_table' table successfully created!</p>";
        } else {
            echo "<p style='text-align: center; font-style: italic;'>Error creating '$sql_table' table: " . mysqli_error($conn) . "</p>";
        }

        // Query for creating the "myfriends" table and execute the query
        $sql_table1 = "myfriends";
        $query1 = "CREATE TABLE IF NOT EXISTS $sql_table1 (
                                    friend_id1 INT NOT NULL,
                                    friend_id2 INT NOT NULL,
                                    CHECK (friend_id1 != friend_id2) #check if friend_id2 is not equal to friend_id1
                   )";

        $result1 = mysqli_query($conn, $query1);
        if ($result1) {
            echo "<p style='text-align: center; font-style: italic;'>'$sql_table1' table successfully created!</p>";
        } else {
            echo "<p style='text-align: center; font-style: italic;'>Error creating '$sql_table1' table: " . mysqli_error($conn) . "</p>";
        }

        // Query for checking whether the data in the "friends" table before populating the records and execute the query
        $query2 = "SELECT * FROM $sql_table";
        $result2 = mysqli_query($conn, $query2);
        if (mysqli_num_rows($result2) > 0) {
            echo "<p style='text-align: center; font-style: italic;'>'$sql_table' table already had populated data!</p>";
        } else {
            $query3 = "INSERT INTO $sql_table (friend_email, password, profile_name, date_started, num_of_friends) VALUES
                                               ('tylerl@gmail.com', '001', 'Tyler L', '2024-01-01', 6),
                                               ('mariaj@gmail.com', '002', 'Maria J', '2024-01-02', 6),
                                               ('shawnm@gmail.com', '003', 'Shawn M', '2024-01-03', 6),
                                               ('justinb@gmail.com', '004', 'Justin B', '2024-01-04', 6),
                                               ('charliep@gmail.com', '005', 'Charlie P', '2024-01-05', 6),
                                               ('edwardc@gmail.com', '006', 'Edward C', '2024-01-06', 6),
                                               ('kellyc@gmail.com', '007', 'Kelly C', '2024-01-07', 6),
                                               ('michaelb@gmail.com', '008', 'Michael B', '2024-01-08', 6),
                                               ('adaml@gmail.com', '009', 'Adam L', '2024-01-09', 6),
                                               ('brunom@gmail.com', '010', 'Bruno M', '2024-01-10', 6)";

            $result3 = mysqli_query($conn, $query3);   
            if ($result3) {
                echo "<p style='text-align: center; font-style: italic;'>Sample records for '$sql_table' table are successfully populated!</p>";
            } else {
                echo "<p style='text-align: center; font-style: italic;'>Error inserting records for '$sql_table' table: " . mysqli_error($conn) . "</p>";
            }
        }

        // Query for checking whether the data in the "myfriends" table before populating the records and execute its query
        $query4 = "SELECT * FROM $sql_table1";
        $result4 = mysqli_query($conn, $query4);
        if (mysqli_num_rows($result4) > 0) {
            echo "<p style='text-align: center; font-style: italic;'>'$sql_table1' table already had populated data!</p>";
        } else {
            $query5 = "INSERT INTO $sql_table1 (friend_id1, friend_id2) VALUES (1, 2),
                                                                               (1, 3),
                                                                               (1, 4),
                                                                               (1, 5),
                                                                               (1, 6),
                                                                               (1, 7),
                                                                               (1, 8),
                                                                               (1, 9),
                                                                               (1, 10),
                                                                               (2, 3),
                                                                               (2, 4),
                                                                               (2, 5),
                                                                               (2, 6),
                                                                               (2, 7),
                                                                               (2, 8),
                                                                               (2, 9),
                                                                               (2, 10),
                                                                               (3, 4),
                                                                               (3, 5),
                                                                               (3, 6)";

            $result5 = mysqli_query($conn, $query5);
            if ($result5) {
                echo "<p style='text-align: center; font-style: italic;'>Sample records for '$sql_table1' table are successfully populated!</p>";
            } else {
                echo "<p style='text-align: center; font-style: italic;'>Error inserting records for '$sql_table1' table: " . mysqli_error($conn) . "</p>";
            }
        }

        mysqli_close($conn);
    ?>

	<?php
       include_once "includes/footer.inc";
    ?>
</body>
</html>

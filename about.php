<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="description" content="Web application development">
    <meta name="keywords" content="PHP, HTML, CSS">
    <meta name="author" content="Quoc Co Luc (Tyler)">
    <title>Assignment 2 - About Page</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <?php
      include_once "includes/header.inc";
      include_once "includes/nav.inc";
    ?>
    <h2>About Page</h2>
    <ul>
        <li><strong>What tasks you have not attempted or not completed?</strong> <br>
            There are no tasks that I have not attempted or not completed.</li>
        <li><strong>What special features have you done, or attempted, in creating the site that we should know about?</strong> <br>
            For this project, I have done some special features:
            <ul>
                <li>Create a sanitising input function for providing security to the website such as stripslashes() and htmlspecialchars().</li>
                <li>Create .inc files for minimising the length of code.</li>
            </ul>
        </li>
        <li><strong>Which parts did you have trouble with?</strong> <br>
            I had trouble implementing extra challenge parts for the friendadd.php page, which led to late submission, but I had resolved the issue.</li>
        <li><strong>What would you like to do better next time?</strong> <br>
            I would like to have some more time working on the assignment so that I can finish on time.</li>
        <li><strong>What additional features did you add to the assignment? (if any)</strong> <br>
            I did not have any additional features for this assignment.</li>      
    </ul>
    <h3>Discussion that I participated:</h3>
	<div class="figurebox">
	    <figure>
            <img src="discussion.png" alt="Discussion">
            <figcaption><em>Discussion that I attended on Canvas</em></figcaption>
        </figure>
    </div>
    <br>
	<br>
    <a href="friendlist.php" class="link">Friend Lists</a>
    <a href="friendadd.php" class="link">Add Friends</a>
    <a href="index.php" class="link">Home Page</a>
	
	<?php
       include_once "includes/footer.inc";
    ?>
</body>
</html>

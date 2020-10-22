<!DOCTYPE html>
<html lang="en">
<head>
	<style>
    body{
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .container{
      width: 60%;
      padding-right: 15px;
      padding-left: 15px;
      margin-right: auto;
      margin-left: auto;
    }
  </style>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Информация о профиле</title>
</head>
<body>
	 <div class="container">
    <h1>Информация о профиле</h1>


    <form method="post">  
        <?php
            require_once 'connection.php';
           $stmt = $pdo->query("SELECT first_name, last_name, email, headline, summary FROM `profile` 
            WHERE profile_id = ' ".$_GET['profile_id']."'; ");
           $row = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "<p>First Name: ".$row['first_name']." </p>";
            echo "<p>Last Name: ".$row['last_name']." </p>";
            echo "<p>Email: ".$row['email']." </p>";
            echo "<p>Headline: <br>".$row['headline']."</p>";
            echo "<p>Summary: <br>".$row['summary']."</p>";

            $stmt = $pdo->prepare("SELECT concat(year, \" \" ,  name) as result FROM `education` 
              JOIN institution on education.institution_id = institution.institution_id 
              WHERE profile_id = :id_prof ORDER BY rank");
            $stmt->execute(array( ':id_prof' => $_GET['profile_id']) );
             $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
             if(count($rows) > 0) {
              echo "<p>Education</p>";
              echo "<ul>";
              foreach ( $rows as $row ) {
                echo "<li>";
                echo($row['result']);
                echo "</li>";
               }
              echo "</ul>";
              } 
            
        ?>
      
	    <p><a href="index.php">Done</a></p>

    </form>
  </div>
</body>
</html>
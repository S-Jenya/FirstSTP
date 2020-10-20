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
 <link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7"
    crossorigin="anonymous">

<link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r"
    crossorigin="anonymous">

<link rel="stylesheet" 
    href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" 
    integrity="sha384-xewr6kSkq3dBbEtB6Z/3oFZmknWn7nHqhLVLrYgzEFRbU/DHSxW7K3B44yWUN60D" 
    crossorigin="anonymous">

<script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>

<script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
  integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
  crossorigin="anonymous"></script>
  
	<title>Изменить карточку</title>
</head>
<body>
	 <div class="container">
    <h1>Изменить карточку</h1>

    <?php

      require_once 'connection.php';
      require_once "util.php";
      session_start();
      if(array_key_exists('btn_save',$_POST)){

	      if (isset($_POST['first_name']) && isset($_POST['last_name'])
	          && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {
            
	          // Data validation

            $msg = validatePos();
            $msgEdu = validateEdu();
        
	          if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1
	            || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1) {
	            echo "<p style=\"color:red\">All fields are required</p>";

	          } elseif (strpos($_POST['email'],'@') === false) {
	            echo "<p style=\"color:red\">Email address must contain @</p>";
	          } elseif (is_string($msg)){
               echo "<p style=\"color:red\">";
               echo $msg;
               echo "</p>";
             } elseif (is_string($msgEdu)){
                 echo "<p style=\"color:red\">";
                 echo $msgEdu;
                 echo "</p>";   
            } else{

	           	$stmt = $pdo->query("UPDATE `profile` SET 
  	        	`first_name` = '".$_POST['first_name']."', 
  	        	`last_name` = '".$_POST['last_name']."', 
  	        	`email` = '".$_POST['email']."', 
  	        	`headline` = '".$_POST['headline']."', 
  	        	`summary` = '".$_POST['summary']."' 
  	        	WHERE `profile`.`profile_id` = '".$_GET['profile_id']."'; ");
  	        	$stmt->execute();

              $stmt = $pdo->prepare('DELETE FROM `position` WHERE profile_id = :profile_id');
              $stmt->execute(array(':profile_id' => $_GET['profile_id']));
             
              $rank = 1;
              for($i = 1; $i<=9; $i++){
                if( isset($_POST['year'.$i]) && isset($_POST['desc'.$i]) ) {
                  $year = $_POST['year'.$i];
                  $desc = $_POST['desc'.$i];
                  $stmt = $pdo->prepare('INSERT INTO `position` (`profile_id`, `rank`, `year`, `description`) 
                      VALUES (:pid, :rank, :year, :descc)');
                  $stmt->execute(array( 
                      ':pid' => $_GET['profile_id'], 
                      ':rank' => $rank, 
                      ':year' => $year, 
                      ':descc' => $desc)
                    );
                  $rank++;
                } 
              }

              $stmt = $pdo->prepare('DELETE FROM `Education` WHERE profile_id = :profile_id');
              $stmt->execute(array(':profile_id' => $_GET['profile_id']));
              $rank = 1;
              for($i = 1; $i<=9; $i++){
                  if( isset($_POST['edu_year'.$i]) && isset($_POST['edu_school'.$i]) ) {
                     $year = $_POST['edu_year'.$i];
                      $school = $_POST['edu_school'.$i];
                      $institution_id;
                      $stmt = $pdo->prepare('SELECT * FROM `institution` WHERE name = :inst_name');
                      $stmt->execute(array( ':inst_name' => $school));
                      $row = $stmt->fetch(PDO::FETCH_ASSOC);
                      if($row !== false) $institution_id = $row['institution_id'];
                      if ($row === false) {
                        $stmt = $pdo->prepare('INSERT INTO `institution` (`name`) VALUES (:inst_name)');
                        $stmt->execute(array( ':inst_name' =>  $school));
                        $institution_id = $pdo->lastInsertId();
                      }
                       $stmt = $pdo->prepare('INSERT INTO `education` (`profile_id`, `institution_id`, `rank`, `year`) 
                        VALUES (:pid, :inst_id, :rank, :year)');
                       $stmt->execute(array( 
                          ':pid' => $_GET['profile_id'], 
                          ':inst_id' => $institution_id,
                          ':rank' => $rank, 
                          ':year' => $year)
                        );
                        $rank++; 
                  }
              }

                //echo '<pre>';
               // print_r($_POST);
                //echo '</pre>';
              $_SESSION['success_upd'] = 'Profile updated';
  	           header('Location: index.php');
              return;
            } 

        } 

      }
    
    ?>

    <form method="post">  
        <?php
            require_once 'connection.php';
           $stmt = $pdo->query("SELECT first_name, last_name, email, headline, summary FROM `profile` 
            WHERE profile_id = ' ".$_GET['profile_id']."'; ");
           $row = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "<p>First Name: <input type=\"text\" name=\"first_name\" size=\"60\" value=\"".$row['first_name']."\">		</p>";
            echo "<p>Last Name: <input type=\"text\" name=\"last_name\" size=\"60\" value=\"".$row['last_name']."\">		</p>";
            echo "<p>Email: <input type=\"text\" name=\"email\" size=\"30\" value=\"".$row['email']."\"></p>";
            echo "<p>Headline: </p> <input type=\"text\" name=\"headline\" size=\"80\" value=\"".$row['headline']."\">		</p>";
            echo "<p>Summary: </p><textarea name=\"summary\" rows=\"8\" cols=\"80\">".$row['summary']."</textarea>";

            $stmt = $pdo->prepare("SELECT year, name FROM `education` 
              JOIN institution on education.institution_id = institution.institution_id 
              WHERE profile_id = :id_prof ORDER BY rank");
            $stmt->execute(array( ':id_prof' => $_GET['profile_id']) );
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<p>";
            echo "Education: <input type=\"submit\" id=\"addEdu\" value=\"+\">";
            echo "</p>"; 
            echo "<div id=\"edu_fields\">";
           // echo "<pre>";
           // print_r($rows);
           // echo "</pre>";
           

            $countPos = 0;
             foreach ( $rows as $row ) {
              $countPos++;
              echo "<div id=\"edu".$countPos."\">";
              echo "<p>Год окончания: <input type=\"text\" name=\"edu_year".$countPos."\" value=\"".$row['year']."\">";
              echo "<input type=\"button\" value=\"-\"onclick=\"$('#edu".$countPos."').remove();return false;\"></p>";
              $own_name = $row['name'];
              echo "Учебное учреждение: 
              <input type=\"text\" name=\"edu_school".$countPos."\" value=\"".$own_name."\" list=\"exampleList\">  
                <datalist id=\"exampleList\">";
              $stmt = $pdo->query('SELECT name FROM institution');
              while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
                if( $row['name'] != $own_name ) {
                  echo "<option value=\"";
                  echo ($row['name']);
                  echo "\"></option>";
                }
              }
              echo "</datalist>";
              echo "</p></div>";
              
            } 
            echo "</div>";

            $countPos = 0;
            $stmt = $pdo->query("SELECT * FROM `position` 
              WHERE profile_id = ' ".$_GET['profile_id']."' ORDER BY rank");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<p>";
            echo "Position: <input type=\"submit\" id=\"addPos\" value=\"+\">";
            echo "</p>"; 
            echo "<div id=\"position_fields\">";
            foreach ( $rows as $row ) {
              $countPos++;
              echo "<div id='position".$countPos."'>";
              echo "<p>Year: <input type=\"text\" name=\"year".$countPos."\" value=\"".$row['year']."\">";
              echo "<input type=\"button\" value=\"-\"onclick=\"$('#position".$countPos."').remove();return false;\"></p>";
              echo "<p><textarea name=\"desc".$countPos."\" rows=\"3\" cols=\"80\">".$row['description']."";
              echo "</textarea>";
              echo "</p></div>";
            }
            echo "</div>";
        ?>
       <br>

	    <input type="submit" name="btn_save" value="Save">
	    <input type="submit" onclick="javascript:history.back(); return false;" value="Cancel">

    </form>
    <script>
      
        countPos = $('#position_fields').children().length;
           countEdu = $('#edu_fields').children().length;

        $(document).ready(function(){
            window.console && console.log('Document ready called');
            $('#addPos').click(function(event){
                // http://api.jquery.com/event.preventdefault/
                event.preventDefault();
                if ( countPos >= 9 ) {
                    alert("Maximum of nine position entries exceeded");
                    return;
                }
                countPos++;
                window.console && console.log("Adding position "+countPos);
                $('#position_fields').append(
                    '<div id="position'+countPos+'"> \
                    <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
                    <input type="button" value="-" \
                    onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
                    <textarea name="desc'+countPos+'" rows="3" cols="80"></textarea>\
                    </div>');
            });

             $('#addEdu').click(function(event){
              event.preventDefault();
              if ( countEdu >= 9 ) {
                  alert("Maximum of nine education entries exceeded");
                  return;
              }

               var HTMLcode =  '<div id="edu'+countEdu+'"> \
                      <p>Год окончания: <input type="text" name="edu_year'+countEdu+'" value="" /> \
                      <input type="button" value="-" onclick="$(\'#edu'+countEdu+'\').remove();return false;"><br>\
                      </p></div>';

                $.ajax({
                  url: "school.php",
                  type: "GET",
                  dataType: "json", 
                  success:function(data){
                    data = JSON.parse(JSON.stringify(data));
                    HTMLcode += 'Учебное учреждение: <input type="text" name="edu_school'+countEdu+'" list="exampleList">\
                                    <datalist id="exampleList">';
                      for(var i = 0; i < data.length; i++){
                        HTMLcode += '<option value="'+data[i]+'">';
                      }
                      HTMLcode +='</datalist>';
                      $('#edu_fields').append(HTMLcode);
                  }
                });

              countEdu++;
          });

          $('.school').autocomplete({
              source: "school.php"
          });

        });
    </script>

  </div>
</body>
</html>




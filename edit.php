<!DOCTYPE html>
<html lang="en">
<head>
   <link rel="stylesheet" href="style.css">
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

              $stmt = $pdo->prepare('DELETE FROM `Education` WHERE profile_id = :profile_id');
              $stmt->execute(array(':profile_id' => $_GET['profile_id']));
              $rank = 1;

              for($i = 1; $i<=9; $i++){

               // echo("Итерация: ". $i. "\n");
               // echo($_POST['edu_school'.$i]. "\n");
                //echo(isset($_POST['edu_school'.$i]). "\n");
                //echo("---\n");

                  if( isset($_POST['edu_school'.$i])) {
                  echo("Я в условии\n");
                     $year = $_POST['edu_year'.$i];
                      $school = $_POST['edu_school'.$i];
                      $institution_id;
                      $stmt = $pdo->prepare('SELECT institution_id FROM `institution` WHERE name = :inst_name');
                      $stmt->execute(array( ':inst_name' => $school));
                      $row = $stmt->fetch(PDO::FETCH_ASSOC);
                      $institution_id = $row['institution_id'];
                      echo("ID института: ".$institution_id);
                      
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

               // echo '<pre>';
              // print_r($_POST);
               // echo '</pre>';
               // echo ($rank);
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
            echo "<p>Summary: </p><textarea name=\"summary\" rows=\"2\" cols=\"80\">".$row['summary']."</textarea>";

            $stmt = $pdo->prepare("SELECT year, name FROM `education` 
              JOIN institution on education.institution_id = institution.institution_id 
              WHERE profile_id = :id_prof ORDER BY rank");
            $stmt->execute(array( ':id_prof' => $_GET['profile_id']) );
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<p>";
            echo "<p>Данные об образовании:</p>";
            
           // echo "<pre>";
           // print_r($rows);
           // echo "</pre>";


            $countPos = 0;
              echo "<div id=\"edu_fields\">";
             foreach ( $rows as $row ) {
              $countPos++;
              $nn = $row['name'];
              echo "<div class=\"edu_case\" style=\"width: 400px;\">";
              echo "<div id=\"edu".$countPos."\" style=\"background-color: #FFF8DC; margin-top: 15px; padding: 10px;\">";
              echo "<p>Год окончания: <input type=\"text\" name=\"edu_year".$countPos."\" value=\"".$row['year']."\">";
              echo "<input type=\"button\" value=\"Удалить\"onclick=\"btn_del(".$countPos.");\"></p>";
              echo "Учебное учреждение: 
              <SELECT name=\"edu_school".$countPos."\" value=\"".$row['name']."\" >  ";
              $stmt = $pdo->query('SELECT name FROM institution');
               while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
                if($nn == $row['name']) {
                  echo "<option selected=\"selected\" value=\"".$row['name']."\">".$row['name']."";
                } else {
                  echo "<option value=\"".$row['name']."\">".$row['name']."";
                } 
              }
              echo "</SELECT>";
              echo "</p></div></div>";

              
              
            } 
             echo "</div>";
              echo "<input type=\"submit\" id=\"addEdu\" value=\"Добавить\">";
           // echo "</div>";
           
        ?>
       <br>

        <p>
          <div class="btn_control">
            <input type="submit" name="btn_save" value="Сохранить">
            <input type="submit" onclick="javascript:history.back(); return false;" value="Отменить">
          </div>
        </p>
	    
    </form>
    <script>
      
        countPos = $('#position_fields').children().length;
           countEdu = $('#edu_fields').children().length;
           console.log("Учреждений загружено = ", countEdu);

           var wasHear = false;

        function btn_del(count) {
            console.log("Фукция вызвана");
            $('#edu'+count).remove();
            countEdu--;
            console.log("После удаления = " + countEdu);
            return false;
        }

        $(document).ready(function(){
            window.console && console.log('Document ready called');

             $('#addEdu').click(function(event){
              event.preventDefault();
              if ( countEdu >= 9 ) {
                  alert("Максимальное число учреждений 9шт.");
                  return;
              }
              countEdu++;
          console.log("После добавления = ", countEdu);
              var HTMLcode =  '<div class="edu_case">\
                                  <div id="edu'+countEdu+'" style="background-color: #FFF8DC; margin-top: 15px; padding: 10px;"> \
                                    <p>Год окончания: <input type="text" name="edu_year'+countEdu+'" value="" /> \
                                    <input type="button" value="Удалить" onclick="btn_del('+countEdu+');"><br>\
                                    </p>';
//onclick="$(\'#edu'+countEdu+'\').remove();return false;"
                $.ajax({
                  url: "school.php",
                  type: "GET",
                  dataType: "json", 
                  success:function(data){
                    data = JSON.parse(JSON.stringify(data));
                    HTMLcode += 'Учебное учреждение: <select name="edu_school'+countEdu+'">';
                      for(var i = 0; i < data.length; i++){
                        HTMLcode += '<option value="'+data[i]+'">'+data[i]+'';
                      }
                      HTMLcode +='</select>\
                          </div></div>';
                      $('#edu_fields').append(HTMLcode);
                  }
                });
          });

        });
    </script>

  </div>
</body>
</html>




 <?php
  require_once "connection.php";
      session_start();
      if (isset($_POST['email']) && isset($_POST['password'])) {

          // Data validation
          if ( strlen($_POST['email']) < 1 || strlen($_POST['password']) < 1) {
              $_SESSION['error'] = 'Missing data';
              header("Location: login.php");
              return;
          }

          if ( strpos($_POST['email'],'@') === false ) {
              $_SESSION['error'] = 'Bad data';
              header("Location: login.php");
              return;
          }

          $check = hash('md5', $salt.$_POST['password']);
          $stmt = $pdo->prepare('SELECT user_id FROM users WHERE email = :em AND password = :pw');
          $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          $_SESSION['success'] = $row['user_id'];
          print_r($_SESSION['success']);

          //return;
      }

      if ( isset($_SESSION['error']) ) {
          echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
          unset($_SESSION['error']);
          header( 'Location: login.php' ) ;
      }
      if ( isset($_SESSION['success']) ) {
          echo <p style="color:green">'.$_SESSION['success']."</p>\n";
          unset($_SESSION['success']);
          header( 'Location: index.php' ) ;
      }
    ?>
<?php 
  #error_reporting(0);
  $FLAG = "ese{c0ntrol_the_3l3ments_1s_th3_k3y}";
  session_start(); 
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <link rel="icon" href="./res/favicon.ico">
    <link href="./index.css" rel="stylesheet">
    <title>FlagShop</title>
  </head>

  <body>
    <article>
      <?php
        # Display login form
        if(!isset($_SESSION['username']) or !isset($_SESSION['money'])){
          ?>
          <header> 
            <h1>FlagShop</h1>
            <h2>Buy Flag or Send Money to your friend</h2>  
          </header>

          <?php
          # Check login and set var
          if(isset($_POST['username']) && isset($_POST['password'])){
            # SQL Injection
            include 'config.php';
            $req = $DB->prepare("SELECT * FROM users WHERE (username=:username AND password='".str_replace(';', '[REMOVED]', $_POST['password'])."')")  ;
            $req->execute(array(
              "username" => $_POST['username'],
              ));

            # Good credential set variable
            if($result = $req->fetch()){
                $_SESSION['username'] = $result['username'];
                $_SESSION['money']    = $result['money']; 
            }

            header('Location: index.php');
          }

          # Not logged in
          else{
            ?>
            <form id='loginForm' name='loginForm' action='' method="POST">
              <input type=text id='username' name='username' placeholder='Identifiant'><br>
              <input type=password id='password' name='password' placeholder='Mot de passe'><br>
              <input type=submit id='btn' value='Connexion'>
            </form>
            <?php
          }
        }


        # Display money mouvement
        else{

          # Log out
          if(isset($_GET['logout'])){
            $_SESSION = array();
            session_destroy();
            header('Location: index.php');
          }

          ?>      
          <header> 
            <h1>FlagShop<?php echo " - <a href='?logout'>Logout</a>"; ?></h1>
            <h2>Buy Flag or Send Money to your friend</h2>  
          </header>

          <p>Bienvenue <?php echo $_SESSION['username']?>, vous disposez de <?php echo $_SESSION['money']?> €</p>
          <form id='buyFlag' name='buyFlag' method="POST">
            <?php 
              # Buy the flag
              if(isset($_POST['buyFlagInput'])){

                # Display flag and reset money
                if($_SESSION['money'] >= 100){
                  echo "<span id='success'>Merci de votre achat, voici votre flag : ".$FLAG."</span><br>";
                  include 'config.php';
                  $req = $DB->prepare("UPDATE users SET money = 10 WHERE username=:username");
                  $req->execute(array("username" => $_SESSION['username']));
                }

                # Error you don't have enough
                else{
                  echo "<span id='error'>/!\Vous n'avez pas assez d'argent pour acheter un flag</span>";
                }
              }
            ?>
            <input type=hidden name='buyFlagInput'>
            <input type=submit value='ACHETER UN FLAG (100€)'>
          </form>


          <p>ou</p>


          <form id='sendMoney' name='sendMoney'>
            <?php
              # Give Money to a friend
              function giveMoney($to){
                 if($_SESSION['money'] >= 10){
                  include 'config.php';
                  $req = $DB->prepare("UPDATE users SET money = money+10 WHERE username=:username");
                  $req->execute(array("username" => $to));
                  return true;
                 }
                 else{
                  echo "<span id='error'>/!\Vous n'avez pas assez d'argent pour en envoyer</span>";
                 }
                 return false;
              }

              # Remove Money to $_SESSION['username']
              function removeMoney(){
                  include 'config.php';
                  $req = $DB->prepare("UPDATE users SET money = money-10 WHERE username=:username");
                  $req->execute(array("username" => $_SESSION['username']));
                  $_SESSION['money'] = ($_SESSION['money']-10);
              }

              function displayMoney(){
                echo "<p>Vous avez envoyé 10€ à votre ami. </p>";
              }


              if(isset($_GET['do']) && isset($_GET['to'])){

                # Give money
                if(giveMoney($_GET['to']) === true){

                  # Display XXX money envoyé or execute a single function ? 
                  $do = $_GET["do"];
                  $do();

                  # Remove money
                  removeMoney();
                }
              }
 

            ?>
            <input type=text name='to' id='to' placeholder='Destinataire - Ex: admin'>
            <input type=hidden name='do' id='do' value='displayMoney'>
            <input type=submit value="ENVOYER 10€ A UN AMI">
          </form>
          <?php
        }
      ?>
    </article>
  </body> 

  <footer>
    <p>FlagShop Inc</p>
  </footer>
</html>

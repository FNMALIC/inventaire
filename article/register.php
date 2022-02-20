<?php
    include "functions.php";

    $user = new USER();

if($user->is_loggedin()!="") {
    $user->redirect('index.php');
}

if(isset($_POST['btn-signup'])) {
    $uname = strip_tags($_POST['txt_uname']);
    $umail = strip_tags($_POST['txt_umail']);
    $upass = strip_tags($_POST['txt_upass']);   

    if($uname=="")  {
  $error[] = "Provide username!";   
    }
    else if($umail=="") {
  $error[] = "Provide email!";  
    }
    else if(!filter_var($umail, FILTER_VALIDATE_EMAIL)) {
  $error[] = 'Please enter a valid email address!';
    }
    else if($upass=="") {
  $error[] = "Provide password!";
    }
    else if(strlen($upass) < 6){
  $error[] = "Password must be atleast 6 characters!";  
    } else {
        try {
            $stmt = $user->runQuery("SELECT user_name, user_email FROM users WHERE user_name=:uname OR user_email=:umail");
            $stmt->execute(array(':uname'=>$uname, ':umail'=>$umail));
            $row=$stmt->fetch(PDO::FETCH_ASSOC);

            if($row['user_name']==$uname) {
                $error[] = "Sorry username already taken!";
            } else if($row['user_email']==$umail) {
                $error[] = "Sorry email id already taken!";
            } else {
                if($user->register($uname,$umail,$upass)){  
                    $user->redirect('login.php?joined');
                }
            }
        }
        catch(PDOException $e) {
            echo $e->getMessage();
        }
    }   
}
?>


<?= template_header('HOME');?>
<div class="container">
  <h1>Register or <a href="login.php">Login</a></h1>
  <hr/>
  <?php 
   if(isset($error)) {
    foreach($error as $error) {
    echo "<p class='error'>$error</p>";
    }
   }
  ?>
  <form method="post">
   <input type="text" name="txt_uname" placeholder="Username" value="<?php if(isset($error)){echo $uname;}?>" />
   <input type="text" name="txt_umail" placeholder="Email" value="<?php if(isset($error)){echo $umail;}?>" />
   <input type="password" name="txt_upass" placeholder="Password" />
   <button type="submit" name="btn-signup">Register</button>
  </form>
 </div>
<?= template_footer() ?>
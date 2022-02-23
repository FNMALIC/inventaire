<?php
// include filss
include 'functions.php';
include 'components/__header.php';

$user = new USER();

// if($user->is_loggedin()!="") {
//     $user->redirect('index.php');
// }

if (isset($_POST['btn-signup'])) {
    $uname = strip_tags($_POST['txt_uname']);
    $umail = strip_tags($_POST['txt_umail']);
    $upass = strip_tags($_POST['txt_upass']);
    $upass_2 = strip_tags($_POST['txt_upass_2']);

    if ($uname == "") {
        $error[] = "Provide username!";
    } else if ($umail == "") {
        $error[] = "Provide email!";
    } else if (!filter_var($umail, FILTER_VALIDATE_EMAIL)) {
        $error[] = 'Please enter a valid email address!';
    } else if ($upass == "") {
        $error[] = "Provide password!";
    } else if (strlen($upass) < 6) {
        $error[] = "Password must be atleast 6 characters!";
    } else if ($upass != $upass_2) {
        $error[] = "Password of first fild doesnot match that of second!";
    } else {
        try {
            $stmt = $user->runQuery("SELECT user_name, user_email FROM users WHERE user_name=:uname OR user_email=:umail");
            $stmt->execute(array(':uname' => $uname, ':umail' => $umail));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row['user_name'] == $uname) {
                $error[] = "Sorry username already taken!";
            } else if ($row['user_email'] == $umail) {
                $error[] = "Sorry email id already taken!";
            } else {
                if ($user->register_sup($uname, $umail, $upass)) {
                    sleep(3);
                    $user->redirect('index.php?joined');
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
?>


<?=template_header('HOME', '');?>
<div class="container mt-5">
  <h1>Register a New user</h1>
  <hr/>
  <?php
if (isset($error)) {
    foreach ($error as $error) {
        // echo "<p class='error'>$error</p>";
        print('<div class="alert alert-warning alert-dismissible fade show" role="alert">
   ' . $error . '
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>');
    }
}
?>
  <form method="post" class="form-control p-5">
    <div class="col-md-6 m-2">
        <label for="newUser" class="form-label">Name of new_sup user:</label>
        <input type="text" name="txt_uname" placeholder="Username" class="form-control" id="inputEmail4" >
    </div>
    <div class="col-md-6 m-2">
        <label for="Email" class="form-label">User Email:</label>
        <input  type="text" name="txt_umail" placeholder="Email" class="form-control" id="inputEmail4" >
    </div>
    <div class="col-md-6 m-2">
        <label for="pasword" class="form-label">Password:</label>
        <input type="password" name="txt_upass" placeholder="Password" class="form-control" id="inputEmail4" >
    </div>
    <div class="col-md-6 m-2">
        <label for="pasword" class="form-label">Re-enter Password:</label>
        <input type="password" name="txt_upass_2" placeholder="Password" class="form-control" id="inputEmail4" >
    </div>


   <button type="submit" name="btn-signup" class="btn btn-success m-2">Register</button>
  </form>
 </div>
<?=template_footer()?>
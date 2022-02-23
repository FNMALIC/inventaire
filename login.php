<?php
error_reporting(E_ERROR | E_PARSE);
// include filss
include 'functions.php';
include 'components/__header.php';
$login = new USER();

if ($login->is_loggedin() != "") {
    $login->redirect('index.php');
}

if (isset($_POST['btn-login'])) {
    $uname = strip_tags($_POST['txt_uname_email']);
    $umail = strip_tags($_POST['txt_uname_email']);
    $upass = strip_tags($_POST['txt_password']);

    if ($login->doLogin($uname, $umail, $upass)) {
        $login->redirect('index.php');
    } else {
        $error = "Wrong Details!";
    }
}
?>

<?=template_header('Home', "")?>


<div class="container">
    <div class="row">
        <h1>Login</h1>
  <hr/>
  <div class="error">
   <?php
if (isset($error)) {
    print('<div class="alert alert-warning alert-dismissible fade show" role="alert">
        ' . $error . '
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>');
}
if (isset($_GET['joined'])) {
    echo "<p class='success'>Successfully registered please login</p>";
}
?>
  </div>
  <form method="post" id="login-form" class="form-control">
    <div class="col-md-6 m-2">
        <label for="User" class="form-label">Username:</label>
       <input type="text" name="txt_uname_email" placeholder="Username or Email" class="form-control"/>
    </div>
    <div class="col-md-6 m-2">
        <label for="User" class="form-label">Password:</label>
       <input type="password" name="txt_password" placeholder="Password"  class="form-control"/>
    </div>

    <button type="submit" name="btn-login" class="btn btn-success m-2">Login</button>
   </form>
    </div>

 </div>
<?=template_footer()?>
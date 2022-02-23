<?php

// echo $user_id;

function template_header($title, $path = "../")
{
    $user_id = $_SESSION['user_session'];

    $user = new USER;
    print('
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>' . $title . '</title>
		<link href="' . $path . 'style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>

        </head>
	<body>
    <nav class="navtop">
    	<div>
    		<h1><b style="font-size:xx-large;">BELAVIE</b></h1>



        <a href="' . $path . 'index.php"><i class="fas fa-address-book"></i>Articles</a>
				<a href="' . $path . 'piscine/index_p.php"><i class="fas fa-arrow-circle-down"></i>Piscine</a>
				');

    print('</div>
    </nav>');

}

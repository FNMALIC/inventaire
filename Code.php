<?php

$name = "Nixon";
//creates a unique id with the 'about' prefix
echo "Using the date: <br> ";
$a = uniqid(Date('d', true));

echo $a;
echo "<br> <hr>";
//creates a longer unique id with the 'about' prefix
echo "Using the Name: <br> ";
$b = uniqid($name, true);

echo "<br>";
echo $b;

echo "<br> <hr>";
//creates a unique ID with a random number as a prefix - more secure than a static prefix
echo "Using the Rand numbers from 0 - 500: <br> ";
$c = uniqid(rand(0, 500), true);
echo "<br>";
echo $c;
echo "<br> <hr>";
//this md5 encrypts the username from above, so its ready to be stored in your database
echo "Pass the previous one in an md5 hashing to obtain 16 characters: <br> ";
$md5c = md5($c);
echo "<br>";
echo $md5c;

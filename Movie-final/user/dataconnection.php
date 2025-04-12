<?php

$connect= mysqli_connect("localhost","root","","lex_movie");// fill out database name

if(!$connect)
{
	echo "not connected to database";
}
?>

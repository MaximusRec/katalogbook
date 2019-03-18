<?php
/**
 * Created by PhpStorm.
 * User: user
 */

require "publishing.php";

require $_SERVER['DOCUMENT_ROOT'] . "/header.php";

echo "<center><h1>Список издательств</h1><form action='new.php' method='post' ><input type='image' src='../images/add.png' name='new' alt='Новая рубрика'></form></center>";

$obj = NEW publishing();

echo $obj->viewAll();

require $_SERVER['DOCUMENT_ROOT'] . "/footer.php";
?>

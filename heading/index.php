<?php
/**
 * Created by PhpStorm.
 * User: user
 */

require "heading.php";

require $_SERVER['DOCUMENT_ROOT'] . "/header.php";

echo "<center><h1>Рубрики <form action='new.php' method='post' ><input type='image' src='../images/add.png' name='new' alt='Новая рубрика'></form></h1></center>";

$obj = NEW heading();

echo $obj->viewAll();

require $_SERVER['DOCUMENT_ROOT'] . "/footer.php";
?>

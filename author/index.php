<?php
/**
 * Created by PhpStorm.
 * User: user
 */
require $_SERVER['DOCUMENT_ROOT'] . "/header.php";
require $_SERVER['DOCUMENT_ROOT'] . "/author/author.php";

echo "<center><h1>Список авторов</h1><form action='new.php' method='post' ><input type='image' src='../images/add.png' name='new' alt='Новый автор'></form></center>";

$obj = NEW author();

echo $obj->viewAll();

require $_SERVER['DOCUMENT_ROOT'] . "/footer.php";
?>

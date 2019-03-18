<?php
require __DIR__ . "/header.php";
require __DIR__ . "/book.php";

$obj = NEW book;

echo $obj->viewTable();

require __DIR__ . "/footer.php";
?>



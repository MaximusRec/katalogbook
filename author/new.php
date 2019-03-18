<?php
require $_SERVER['DOCUMENT_ROOT'] . "/header.php";
require $_SERVER['DOCUMENT_ROOT'] . "/author/author.php";

if( isset( $_POST['new_author'] ) ) {
    $result = author::AddAuthor ( $_POST['authorNew'] );
    if ( $result == true ) load_page( "/author/index.php" );
}
?>
<center><h1>Добавить автора</h1></center>

<form enctype='multipart/form-data' method='post' name='author'>
    Ф.И.О. автора<br>
    <input type="text" value="" size="100" name="authorNew[name_author]" required ><br><br>

    <input class="btn btn-primary center-block" type='submit' name='new_author' value='Добавить'  />

</form>

<? require $_SERVER['DOCUMENT_ROOT'] . "/footer.php"; ?>


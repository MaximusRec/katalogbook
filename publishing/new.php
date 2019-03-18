<?php
require $_SERVER['DOCUMENT_ROOT'] . "/header.php";
require "publishing.php";

if( isset( $_POST['new_publishing'] ) ) {
    $result = publishing::AddPublishing ( $_POST['publishingNew'] );
    if ( $result == true ) load_page( "/publishing/index.php" );
}
?>
<center><h1>Новое издательство</h1></center>

<form enctype='multipart/form-data' method='post' name='publishing'>
    Название издательства<br>
    <input type="text" value="" size="100" name="publishingNew[name_publishing]" required ><br><br>

    Адрес издательства<br>
    <input type="text" value="" size="100" name="publishingNew[addres_publishing]" required ><br><br>

    Телефон издательства<br>
    <input type="text" size="30" name="publishingNew[tel_publishing]" placeholder='+(380)00000000'><br><br>

    <input class="btn btn-primary center-block" type='submit' name='new_publishing' value='Добавить'  />

</form>

<? require $_SERVER['DOCUMENT_ROOT'] . "/footer.php"; ?>
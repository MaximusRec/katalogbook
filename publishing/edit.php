<?php
require $_SERVER['DOCUMENT_ROOT'] . "/header.php";
require "publishing.php";

if ( isset( $_POST['delete_x'] ) )
{
    $result = publishing::DeletePublishing ($_POST['publishing_id'] );
    if ( $result == true )  load_page( "/publishing/index.php" );
}

if ( isset( $_POST['update_publishing'] ) )
{
    $result = publishing::UpdatePublishing ( $_POST['publishingEdit'] );
    if ( $result == true )  load_page( "/publishing/index.php" );
}

if( isset( $_POST['edit_x'] ) )
{   $obj= NEW publishing;
    $record = $obj->readOneRecord ($_POST['publishing_id']);

?>
<center><h1>Редактируем свойства издательства</h1></center>

<form enctype='multipart/form-data' method='post' name='publishing'>

    <input type='hidden' name='publishingEdit[publishing_id]' value = '<? echo $record['publishing_id'] ?>' >
    Название издательства<br>
    <input type="text" name="publishingEdit[name_publishing]" size="100" value="<? echo $record['name_publishing'] ?>" required ><br><br>

    Адрес издательства<br>
    <input type="text" name="publishingEdit[addres_publishing]" size="100" value="<? echo $record['addres_publishing'] ?>" ><br><br>

    Телефон издательства<br>
    <input type="text" name="publishingEdit[tel_publishing]" value="<? echo $record['tel_publishing'] ?>" placeholder='+(380)00000000' ><br><br>

    <input class="btn btn-primary center-block" type='submit' name='update_publishing' value='Сохранить' />

</form>


<?php
}
require $_SERVER['DOCUMENT_ROOT'] . "/footer.php";
?>
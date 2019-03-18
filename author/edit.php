<?php
require $_SERVER['DOCUMENT_ROOT'] . "/header.php";
require "author.php";

if ( isset( $_POST['delete_x'] ) )
{
    $result = author::DeleteAuthor ($_POST['author_id'] );
    if ( $result == true )  load_page( "/author/index.php" );
}

if ( isset( $_POST['update_author'] ) )
{
    $result = author::UpdateAuthor ( $_POST['authorEdit'] );
    if ( $result == true )  load_page( "/author/index.php" );
}

if( isset( $_POST['edit_x'] ) )
{   $obj= NEW author;

    $record = $obj->readOneRecord ($_POST['author_id']);

?>
<center><h1>Редактируем свойства издательства</h1></center>

<form enctype='multipart/form-data' method='post' name='author'>

    <input type='hidden' name='authorEdit[author_id]' value = '<? echo $record['author_id'] ?>' >
    Название издательства<br>
    <input type="text" name="authorEdit[name_author]" size="100" value="<? echo $record['name_author'] ?>" required ><br><br>

    <input class="btn btn-primary center-block" type='submit' name='update_author' value='Сохранить' />

</form>


<?php
}
 require $_SERVER['DOCUMENT_ROOT'] . "/footer.php";
?>
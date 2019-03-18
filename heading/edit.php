<?php
require $_SERVER['DOCUMENT_ROOT'] . "/header.php";
require "heading.php";

if ( isset( $_POST['delete_x'] ) )
{
    $result = heading::DeleteHeading ($_POST['heading_id'] );
    if ( $result == true )  load_page( "/heading/index.php" );
}

if ( isset( $_POST['update_heading'] ) )
{
    $result = heading::UpdateHeading ( $_POST['headingEdit'] );
    if ( $result == true )  load_page( "/heading/index.php" );
}


if( isset( $_POST['edit_x'] ) )
{   $obj= NEW heading;
    $headings = $obj->Headin();
    $record = $obj->readOneRecord ($_POST['heading_id']);

?>
<center><h1>Изменяем свойства рубрики</h1></center>

<form enctype='multipart/form-data' method='post' name='heading'>
    Родительская рубрика<br>
    <input type='hidden' name='headingEdit[heading_id]' value = '<? echo $record['heading_id'] ?>' >
     <select class='input' name='headingEdit[parent_id]' value='<? echo $record['parent_id'] ?>' >
            <option selected value='0'></option>
            <?php foreach($headings as $key => $heading): ?>
                 <option
                 <?php if($heading['heading_id'] == $record['parent_id'] ): ?>
                        selected
                 <?php endif;?>
                 value='<? echo $key ?>'><? echo $heading['name_heading'] ?></option>
            <?php endforeach;?>
                </select>
    <br><br>

    Название рубрики<br>
    <input type="text" name="headingEdit[name_heading]" size="100" value="<? echo $record['name_heading'] ?>" required ><br><br>
    <hr>

    <input class="btn btn-primary center-block" type='submit' name='update_heading' value='Сохранить' />

</form>


<?php
}

require $_SERVER['DOCUMENT_ROOT'] . "/footer.php";
?>
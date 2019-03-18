<?php
require $_SERVER['DOCUMENT_ROOT'] . "/header.php";
require "heading.php";
$obj = NEW heading();
$headings= $obj->Headin();

if( isset( $_POST['new_heading'] ) ) {

    $result = heading::AddHeading ( $_POST['headingNew'] );
    if ( $result == true )  load_page( "/heading/index.php" );
}
?>
<center><h1>Новая рубрика </h1></center>

<form enctype='multipart/form-data' method='post' name='heading'>
     Родительская рубрика<br>
     <select class='selects' name='headingNew[parent_id]' required >
                    <option selected value='0'></option>
                     <?php foreach($headings as $key => $heading): ?>
                         <option value='<? echo $key ?>'><? echo $heading['name_heading'] ?></option>
                     <?php endforeach;?>
                </select><br><br>

    Название рубрики<br>
    <input type="text" size="100"  name="headingNew[name_heading]" required ><br><br>

    <input class="btn btn-primary center-block" type='submit' name='new_heading' value='Добавить' />

</form>

<? require $_SERVER['DOCUMENT_ROOT'] . "/footer.php"; ?>
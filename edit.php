<?php
if ( isset( $_POST['load_book'] ) AND isset( $_POST['file'] ) ) {
    //  ф-я для загрузки книг пользователю на комп
    function file_force_download($file) {
        $file= $_SERVER['DOCUMENT_ROOT'] . "/books/". $file;
        if (file_exists($file)) {
            // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
            // если этого не сделать файл будет читаться в память полностью!
            if (ob_get_level()) {
                ob_end_clean();
            }
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            @ob_clean();
            @flush();
            readfile($file);
            $result= true;
            exit;
        } else { $result= false; }
    }

    file_force_download( $_POST['file'] );
}

require __DIR__ . "/header.php";
require __DIR__ . "/book.php";

if ( isset( $_POST['delete_x'] ) )
{
         $result = book::DeleteBook ($_POST['book_id'] );
         if ( $result == true )  load_page( "/index.php" );
}

if ( isset( $_POST['del_dop_image'] ) AND isset( $_POST['bookEdit']['dopimage'] ) ) {
         $result = book::DeleteDopImage ( $_POST['bookEdit']['dopimage'] );
         if ( $result == true )  load_page( "/edit.php?book_id={$_POST['bookEdit']['book_id']}&edit=true" );
}


if ( isset( $_POST['update_book'] ) )
{
         $result = book::UpdateBook ( $_POST['bookEdit'], $_POST['url'], $_FILES );
          if ( $result == true )  load_page( "/edit.php?book_id={$_POST['bookEdit']['book_id']}&edit=true" );
}


if( isset( $_POST['edit_x'] ) OR ( isset ($_GET['edit'])) ) {
    if ( !empty ($_POST['book_id']) OR !empty ($_GET['book_id']) ) {
        $book2 = NEW book;
// вызываем ф-ю считывания всех рубрик
        $headings = $book2::headingTable();

// вызываем ф-ю считывания всех издательств
        $publishings = $book2::publishingsTable();

// вызываем ф-ю считывания всех авторов
        $authors = $book2::authorTable();

if (!empty($_GET['book_id'])) {$book_id= $_GET['book_id'];}
    elseif (!empty($_POST['book_id'])) $book_id= $_POST['book_id'];

    $record = $book2->readOneRecord2( $book_id );
    $dopImages = $book2->dopImage( $book_id );

?>

        <div style="text-align: center;"><h1>Изменяем свойства книги</h1></div>

        <form enctype='multipart/form-data' method='post' name='book'>
            <input type='hidden' name='bookEdit[book_id]' value='<? echo $record['book_id'] ?>'>

            Название книги<br>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="text" name="bookEdit[namebook]" size="100" value="<? echo $record['namebook'] ?>" required><br>
            <hr>
            Рубрика в которой состоит книга<br>
            <select class='selects' name='bookEdit[heading_id]' required>
                <?php foreach ($headings as $key => $heading): ?>
                    <option
                        <?php if ($key == $record['heading_id']): ?>
                            selected
                        <?php endif; ?>
                            value='<? echo $key ?>'><? echo $heading['name_heading'] ?></option>
                <?php endforeach; ?>
            </select><br> <hr>

            Главная картинка<br>
            &nbsp;&nbsp;&nbsp;&nbsp;
            Сейчас у книги следующая главная картинка: <? echo $record['photo'] ?>. Если хотите заменить, выберите
            новую.
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="file" name="photo" multiple accept="image/*,image/jpeg" method="post" value>
            <br><hr>

            Дополнительные картинки (возможен выбор нескольких картинок)<br>            &nbsp;&nbsp;&nbsp;&nbsp;
            Сейчас у книги <strong><? echo COUNT($dopImages) ?></strong> дополнительных картинок. Если хотите добавить,
            выберите новую.
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="file" name="dopPhoto[]" multiple accept="image/*,image/jpeg" multiple method="post">

            <br>Выберите дополнительные картинки для удаления<br>
                <select class='selects' name='bookEdit[dopimage][]' size='3' multiple="multiple">
                    <?php foreach ($dopImages as $key => $image): ?>
                        <option value='<? echo $key ?>'><? echo "{$image['id']}.{$image['namefile_images']}"; ?></option>
                    <?php endforeach; ?><br>
                </select><input type='submit' name='del_dop_image' value='Удалить дополнительные картинки'/>
            <br>
            <hr>

            Дата издательства<br>            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="text" name="bookEdit[date_heading]" size="30" value="<? echo $record['date_heading'] ?>"
                   required><br><br>

            Издательство книги<br>
            <select class='selects' name='bookEdit[publishing_id]' value='<? echo $record['publishing_id'] ?>'>
                <option selected value='0'></option>
                <?php foreach ($publishings as $key => $publishing): ?>
                    <option
                        <?php if ($publishing['publishing_id'] == $record['publishing_id']): ?>
                            selected
                        <?php endif; ?>
                            value='<? echo $key ?>'><? echo $publishing['name_publishing'] ?></option>
                <?php endforeach; ?>
            </select><br><hr>

            Авторы книги (возможен выбор нескольких авторов)<br>
            <select class='selects' name='bookEdit[author_id][]' multiple="multiple" size='7'>
                <?php foreach ($authors as $key => $author): ?>
                    <option
                        <?php if (in_array($key, explode(",", $record['author_ids']))): ?>
                            selected
                        <?php endif; ?>
                            value='<? echo $key ?>'><? echo $author['name_author'] ?></option>
                <?php endforeach; ?>
            </select><br> В БД <? echo $record['author_ids'] ?><br> <hr>

            Путь к файлу.<br> Сейчас выбран следующий файл [/books/<? echo $record['file']; ?>]. <br>
            Если хотите залить новый файл то выберите его и его вариант загрузки: (1) локальный файл или (2)загрузка из интернета.
            <br>
            <input type="radio" name="bookEdit[typeLoadFile]" value="1"> (1) локальный файл
            <input type="file" name="file" accept="text/plain" method="post" >

            <hr><input type="radio" name="bookEdit[typeLoadFile]" value="2"> (2) внешний источник<br>
            <input type="url" name="url" size="100" placeholder="Введите сюда адрес загрузки документа" >

            <hr>
            <input class="btn btn-primary center-block"  type='submit' name='update_book' value='Сохранить'/>

        </form>

        <?php
    }
} else {
    //load_page( "/index.php" );
 }

require __DIR__ . "/footer.php";
?>
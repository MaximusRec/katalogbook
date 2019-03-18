<?php
require __DIR__ . "/header.php";
require __DIR__ . "/book.php";

$book = NEW book;
// вызываем ф-ю считывания всей рубрик
$headings = $book::headingTable();

// вызываем ф-ю считывания всей издательств
$publishings = $book::publishingsTable();

// вызываем ф-ю считывания всей авторов
$authors = $book::authorTable();

if( isset( $_POST['new_book'] ) ) {
   // Была нажата кнопка Добавить новую книгу
    $result = book::Addbook ( $_POST['bookNew'], $_POST['url'], $_FILES );
    if ( $result == true )  load_page( "/index.php" );
}
?>
    <center><h1>Добавить новую книгу</h1></center>

    <form enctype='multipart/form-data' method='post' name='book'>

        Название книги<br>
        <input type="text" name="bookNew[namebook]" size="100" required ><br>
        <hr>
        Рубрика в которой состоит книга<br>
        <select class='selects' name='bookNew[heading_id]' required>
            <?php foreach ($headings as $key => $heading): ?>
                <option value='<? echo $key ?>'><? echo $heading['name_heading'] ?></option>
            <?php endforeach; ?>
        </select><br> <hr>

        Главная картинка<br>
        <input type="file" name="photo" multiple accept="image/*,image/jpeg" method="post" value>
        <br><hr>

        Дополнительные картинки (возможен выбор нескольких картинок)<br>
        <input type="file" name="dopPhoto[]" multiple accept="image/*,image/jpeg" multiple method="post">
        <hr>

        Дата издательства<br>        &nbsp;&nbsp;&nbsp;&nbsp;
        <input type="text" name="bookNew[date_heading]" size="30" value="<? echo date('Y-m-d' ,time()) ?>" required><br><br>

        Издательство книги<br>
        <select class='selects' name='bookNew[publishing_id]' value='<? echo $record['publishing_id'] ?>' required>
            <?php foreach ($publishings as $key => $publishing): ?>
                <option value='<? echo $key ?>'><? echo $publishing['name_publishing'] ?></option>
            <?php endforeach; ?>
        </select><br><hr>

        Авторы книги (возможен выбор нескольких авторов)<br>
        <select class='selects' name='bookNew[author_id][]' multiple="multiple" size='7' required >
            <?php foreach ($authors as $key => $author): ?>
                <option value='<? echo $key ?>'><? echo $author['name_author'] ?></option>
            <?php endforeach; ?>
        </select><br> <hr>

        Если хотите залить новый файл то выберите его и его вариант загрузки: (1) локальный файл или (2)загрузка из интернета.<br>
        <input type="radio" name="bookNew[typeLoadFile]" value="1"> (1) локальный файл
        <input type="file" name="file" accept="text/plain" method="post" >
        <hr>
        <input type="radio" name="bookNew[typeLoadFile]" value="2"> (2) внешний источник<br>
        <input type="url" name="url" size="100" placeholder="Введите сюда адрес загрузки документа. Заканчиваться файл должен на txt, pdf, doc" >

        <hr>
        <input class="btn btn-primary center-block" type='submit' name='new_book' value='Добавить книгу' >

    </form>


<? require __DIR__ . "/footer.php"; ?>
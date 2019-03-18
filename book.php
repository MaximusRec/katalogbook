<?php
/**
 * Created by PhpStorm.
 * User: user
 */
require __DIR__ . "/app/apps.php";

class book extends baseClass
{
/**
 *  Работы с рубриками
 */
    public $pdo;

    public function __construct()
    {
        include __DIR__ . "/bd/pdoconnect.php";
        $this->pdo= $pdo;
        // устанавливаем значение таблицы, в которой хранятся данные по новостям
        $this->table = 't_book';
        $this->id_field = 'book_id';

    }


    /** переопределяем абстрактный метод печати уникальный для каждого класса
     *
     */
    public function viewAll()
    {   $Book= array(); $result = false;
        try {
            $sql_queryes = "SELECT b.`book_id`, b.`namebook`, b.`creared`, b.`heading_id`, h.`name_heading`, b.`photo`, b.`publishing_id`, b.`date_heading`, b.`file`, p.`name_publishing`,  ab.`name_author2`,  ab.`author_ids`
    FROM `t_book` b
	INNER JOIN `t_heading` h ON h.`heading_id` = b.`heading_id`
    INNER JOIN `t_publishing` p ON p.`publishing_id` = b.`publishing_id`     
   	INNER JOIN (
        SELECT ab.`book_id`,  GROUP_CONCAT(a.`name_author`) as `name_author2`, GROUP_CONCAT(a.`author_id`) as `author_ids`
        		FROM `t_author_book` ab
   			INNER JOIN `t_author` a ON a.`author_id` = ab.`author_id`
      	GROUP BY ab.`book_id`
    ) as ab  ON ab.`book_id` = b.`book_id` ;";

            $queryes = $this->pdo->prepare($sql_queryes);
            $results = $queryes->execute();
            while ($res= $queryes->fetch()) {
                $Book[$res['book_id']] = $res;
            }
        } catch (PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
            die();
            $result = false;
        }
        if ($results) {
            $result = true;
            $this->properties = $Book;
        } else {
            $result = false;
        }

        return $Book;
    }

    /** переопределяем абстрактный метод загрузки еденичной записи
     *
     */
    public function readOneRecord2( $id )
    {   require __DIR__ . "/bd/pdoconnect.php";
        $Book= array();
        try {
         $sql_queryes = "SELECT b.`book_id`, b.`namebook`, b.`creared`, b.`heading_id`, h.`name_heading`, b.`photo`, b.`publishing_id`, b.`date_heading`, b.`file`, p.`name_publishing`,  ab.`name_author2`,  ab.`author_ids`
                   FROM `t_book` b
	               INNER JOIN `t_heading` h ON h.`heading_id` = b.`heading_id`
                   INNER JOIN `t_publishing` p ON p.`publishing_id` = b.`publishing_id`     
   	               INNER JOIN (
                   SELECT ab.`book_id`,  GROUP_CONCAT(a.`name_author`) as `name_author2`, GROUP_CONCAT(a.`author_id`) as `author_ids`
                 		FROM `t_author_book` ab
   		            	INNER JOIN `t_author` a ON a.`author_id` = ab.`author_id`
      	          GROUP BY ab.`book_id`
                              ) as ab  ON ab.`book_id` = b.`book_id` WHERE b.`book_id` = :id ;";

            $queryes = $pdo->prepare( $sql_queryes );
            $results = $queryes->execute( array (':id'=> $id ) );
            $res= $queryes->fetch();
                $Book = $res;
        } catch (PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
            die();
            $result = false;
        }
        if ($results) {
            $result = true;
        } else {
            $result = false;
        }

        return $Book;
    }



    /** Получаем список дополнительных картинок определённой книги
     *
     */
    public function dopImage( $id )
    {   require __DIR__ . "/bd/pdoconnect.php";
        $dopImage= array();
        try {
         $sql_queryes = "SELECT * FROM `t_book_dop_images` WHERE `book_id` = :id ;";

            $queryes = $pdo->prepare( $sql_queryes );
            $results = $queryes->execute( array (':id'=> $id ) );
            while ($res= $queryes->fetch()) {
                if (!empty($res['id'])) $dopImage[$res['id']] = $res;
            }
        } catch (PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
            die();
            $result = false;
        }
        if ($results) {
            $result = true;
        } else {
            $result = false;
        }

        return $dopImage;
    }



    /**
     * Получаем список всех рубрик из таблицы t_book
     * @return $books - ассоциативный массив id рубрики = название рубрики
     */
    public function Books()
    {   $books= array();
        //echo "Выполняем Headin()";
        if ($this->properties)
        {
            foreach ( $this->properties as $value) {
                $books[$value['book_id']] = array ( 'book_id'=>$value['book_id'],
                                                    'namebook'=>$value['namebook'],
                                                    'heading_id'=>$value['heading_id'],
                                                    'photo'=>$value['photo'],
                                                    'date_heading'=>$value['date_heading'],
                                                    'publishing_id'=>$value['publishing_id'],
                                                    'file'=>$value['file'] );
            }
        } else { echo "<br>this->properties пуст";}

     return $books;
    }


    public function viewHtml()
    {
        $books = $table = '';

    }


    /** Генерация HTML кода в табличном виде списка книг
     * @return string
     *
     */
    public function viewTable()
    {   $books = ""; $table = "<form name='publ' action='new.php' method='post'><div style='text-align: center; font-size: 24px;'>Каталог книг</div>
                    <br><div style='text-align: center; '><input type='image' src='/images/add.png' name='new' alt='Новая'></div></form><br>";

        foreach ( $this->viewAll() as $key => $value ) {
            $table .= "
                    <tr><form name='publ' action='edit.php'  method='post'>
                    <input type='hidden' name='book_id' value='".$value['book_id']."'>
                    <td style='width: 10px; border-left: 1px solid black; '><input type='image' src='/images/edit.png' width='12' name='edit' alt='Редактировать'></td>
                    <td style='width: 10px; border-left: 1px solid black; '><input type='image' src='/images/delete.png' width='12' name='delete' alt='Удалить'></td>
                    <td style='border-left: 1px solid black; '>{$value['book_id']}</td>
                    <td style='border-left: 1px solid black; '>{$value['namebook']}</td>
                    <td style='border-left: 1px solid black; '>{$value['heading_id']}.{$value['name_heading']}</td>
                    <td style='border-left: 1px solid black; '>{$value['photo']}</td>
                    <td style='border-left: 1px solid black; '>{$value['publishing_id']}</td>
                    <td style='border-left: 1px solid black; '>".flip_dates ($value['date_heading'], 1)."</td>
                    <td style='border-left: 1px solid black; '>{$value['file']}</td>
                    <td style='border-left: 1px solid black; '>{$value['author_ids']}.{$value['name_author2']}</td>
                    </form></tr>";
        }

        $header = "<tr>
                    <th style='width: 10px; border-left: 1px solid black; '>E</th>
                    <th style='width: 10px; border-left: 1px solid black; '>D</th>
                    <th style='border-left: 1px solid black; '>ID книги<br>book_id</th>
                    <th style='border-left: 1px solid black; '>Название книги<br>namebook</th>
                    <th style='border-left: 1px solid black; '>Рубрика<br>heading_id</th>
                    <th style='border-left: 1px solid black; '>Фото<br>photo</th>
                    <th style='border-left: 1px solid black; '>Издательство<br>publishing_id</th>
                    <th style='border-left: 1px solid black; '>Дата издательства<br>date_heading</th>
                    <th style='border-left: 1px solid black; '>Имя файла<br>file</th>
                    <th style='border-left: 1px solid black; '>Ф.И.О. авторов<br>author_ids</th>
                </tr>";

        $books= "<table style='width:99%; border: 1px solid black; '>" . $header . $table . "</table>";
        unset ($value);  unset ($key);

        return $books;
    }



    /**
     * Добавление новую книгу
     * массив
     * @param $parent_id - родительский id
     * @param $name_book - название Рубрики
     * @return bool - возвращает результат, успешно или нет.
     */
    static public function AddBook ( $books, $url, $files )
    {   include __DIR__ . "/bd/pdoconnect.php";    $queryes = $results = false;    $fileds = $fileds= $values = "";

         if ( !empty ($books['heading_id']) AND !empty ($books['namebook'])  AND !empty ($books['date_heading']) ) {

            $data= array(
                        'namebook'=>$books['namebook'] ,
                        'date_heading'=>$books['date_heading'] ,
                        'heading_id'=>$books['heading_id'] ,
                        'publishing_id'=>$books['publishing_id']
            );

            if( !empty ($books['typeLoadFile']) ) {
                if (($books['typeLoadFile'] == 1)) {
                    if (!empty ($files['file']['name']) AND ($_FILES["file"]["error"] == UPLOAD_ERR_OK)) {
                        $extension = "";
                        $extension = parent::getExtension($files['file']['name']);
                        $tmp_name = $files['file']['tmp_name'];
                        $names1 = parent::getRandomFileName(__DIR__ . "/books/") . "." . $extension;
                        $names = __DIR__ . "/books/" . $names1;

                        $data['url'] = "";    $fileds .= ", `url`";    $values .= ", :url";

                        $data['file'] = $names1;    $fileds .= ", `file`";    $values .= ", :file";

                        $data['typeLoadFile'] = $books['typeLoadFile'];    $fileds .= ", `typeLoadFile`";    $values .= ", :typeLoadFile";

                        if (move_uploaded_file($tmp_name, $names)) {
                            echo "<span class='result success'>Файл локальной загрузки {$names} корректен и успешно попал на сервер как файл.</span>";
                            echo "</pre><hr style='border-top: 1px solid red;'>";
                        } else {
                            echo "<br><span class='result fail'>Ошибка, скорей всего очень длинное название файла typeLaodFile == 1 !</span><br>{$names}";
                        }
                    }

                } elseif (($books['typeLoadFile'] == 2)) {
                    //  Загружаем данный файл, сохраняем его в темп директорию и переносим в книги
                    if (preg_match("/[txt|pdf|doc]$/", $url)) {
                        if (!empty ($url)) {
                            $html = parent::putget_dataa($url);
                            if (!empty($html)) {
                                $fileNames = __DIR__ . "/books/temp.tmp";
                                file_put_contents($fileNames, $html);

                                if (file_exists($fileNames)) {
                                    $extension = parent::getExtension( $url );
                                    $names1 = parent::getRandomFileName(__DIR__ . "/books/") . "." . $extension;
                                    $names = __DIR__ . "/books/" . $names1;

                                    $data['url'] = $url;    $fileds .= ", `url`";     $values.= ", :url";

                                    $data['file'] = $names1;   $fileds .= ", `file`";    $values .= ", :file";

                                    $data['typeLoadFile'] = $books['typeLoadFile'];    $fileds .= ", `typeLoadFile`";    $values .= ", :typeLoadFile";

                                    if ( rename($fileNames, $names) ) {
                                        echo "<span class='result success'>Файл удал загрузки {$names} корректен и успешно попал на сервер как файл.</span>";
                                        echo "</pre><hr style='border-top: 1px solid red;'>";
                                    } else {
                                        echo "<br><span class='result fail'>Ошибка, скорей всего очень длинное название файла typeLaodFile == 2 !</span><br>{$names}";
                                    }
                                } else {  }
                            }
                        } else {
                            echo "<br>Пустой адрес файла URL [{$url}] ";
                        }
                    } else {
                        echo "<br>Неправильная ссылка, ссылка должна иметь в конце расширение TXT, PDF, DOC ";
                    }
                }
            } else {
                echo "<br>books['typeLoadFile'] пуста {{$books['typeLoadFile']}] ";
            }

            // Обрабатываем главное изображение
            if ( ( $files['photo']['error'][0] == 0 ) AND !empty ( $files['photo']['name'] ) )
            {
                $extension2="";     $extension2= parent::getExtension ( $files['photo']['name'] );
                $tmp_name = $files['photo']['tmp_name'];
                $names1 =  parent::getRandomFileName(  __DIR__ . "/images/images/" ) . "." . $extension2;
                $names=  __DIR__ . "/images/images/" . $names1 ;

                $fileds .= ", `photo`";      $values .= ", :photo";       $data['photo']= $names1;

            // копируем файл и добавляем в <br>";
                if ( move_uploaded_file( $tmp_name, $names ) ) {
                    echo "<span class='result success'>Файл главного фото {$names} корректен и успешно попал на сервер как файл photo.</span>";
                    echo "</pre><hr style='border-top: 1px solid red;'>";
                }	else {echo "<br><span class='result fail'>Ошибка, скорей всего очень длинное название файла photo !</span><br>{$names}";}
            }

          //    Записываем новую книгу в БД
            try {
                $sql_queryes = "INSERT INTO `t_book` ( `heading_id`, `publishing_id`, `namebook`, `date_heading` {$fileds} ) VALUES ( :heading_id, :publishing_id, :namebook, :date_heading {$values} )";
            //    echo "<hr>В итоге получаем такой запрос: [{$sql_queryes}]";   echo "<hr>В итоге получаем такие данные:"; debug ($data); echo "<hr>";

                $queryes = $pdo->prepare($sql_queryes);
                $results= $queryes->execute( $data );
            } catch (PDOException $e) {
                echo 'Подключение не удалось: ' . $e->getMessage();
                die();
                $result= false;
            }

            if ( $results ) {
                $result= true;
                $book_id = $pdo->lastInsertId();
         //  Добавляем в отдельную таблицу записи авторов, после того как узнали ИД новой записи.
                if ( !empty( $book_id ) AND !empty( $books['author_id'] ) ) {
                    book::AddAuthor ( $book_id, $books['author_id'] );
                }
        //  Обрабатываем в цикле дополнительные изображения
                if ( !empty ( $files['dopPhoto'] ))
                {
                    foreach ( $files['dopPhoto'] as $key=>$value ) {
                        foreach ( $value as $key2=>$val ) {
                            $files2[$key2][$key]= $val;
                        }
                    }
                    foreach ($files2 as $val) {
                        if ( !empty ($val['name']) ) book::AddDopImage ( $book_id, $val['name'], $val['tmp_name'] );
                    }
                }

            } else { $result= false; }
        } else { echo "<center>Не были переданы все переменные.</center>";
            echo "heading_id={$books['heading_id']}, publishing_id={$books['publishing_id']}, namebook={$books['namebook']}<br>";
            $result= false;
        }
        return $result;
    }


    /**
     * Обновление свойства книги
     * массив
     * @param $book_id - id рубрики
     * @param $parent_id - родительский id
     * @param $name_book - название Рубрики
     * @return bool - возвращает результат, успешно или нет.
     */
    static public function UpdateBook ( $books, $url, $files )
    {   include __DIR__ . "/bd/pdoconnect.php";   $queryes = $results = false;    $variable= "";
        if (!empty ($books['book_id']) AND !empty ($books['heading_id']) AND !empty ($books['namebook'])  AND !empty ($books['date_heading']) ) {

        $data= array(   'book_id'=>$books['book_id'] ,
                        'namebook'=>$books['namebook'] ,
                        'date_heading'=>$books['date_heading'] ,
                        'heading_id'=>$books['heading_id'] ,
                        'update_time'=> date('Y-m-d H:i:s' ,time()),
                        'publishing_id'=>$books['publishing_id']
        );

        if( !empty ($books['typeLoadFile']) ) {
            if (($books['typeLoadFile'] == 1)) {
                if (!empty ($files['file']['name']) AND ($_FILES["file"]["error"] == UPLOAD_ERR_OK)) {
                    $extension = "";
                    $extension = parent::getExtension($files['file']['name']);
                    $tmp_name = $files['file']['tmp_name'];
                    $names1 = parent::getRandomFileName(__DIR__ . "/books/") . "." . $extension;
                    $names = __DIR__ . "/books/" . $names1;

                    $data['url'] = "";
                    $variable .= ",  `url` = :url";
                    $data['file'] = $names1;
                    $variable .= ",  `file` = :file";
                    $data['typeLoadFile'] = $books['typeLoadFile'];
                    $variable .= ", `typeLoadFile` = :typeLoadFile";

                    if (move_uploaded_file($tmp_name, $names)) {
                        echo "<span class='result success'>Файл " . $names . " корректен и успешно попал на сервер как файл.</span>";
                        echo "</pre><hr style='border-top: 1px solid red;'>";
                    } else {
                        echo "<br><span class='result fail'>Ошибка, скорей всего очень длинное название файла typeLaodFile == 1 !</span><br>{$names}";
                    }
                }

            } elseif (($books['typeLoadFile'] == 2)) {
                //  Загружаем данный файл, сохраняем его в темп директорию и переносим в книги
                echo "<br>Адрес с которого необходимо скачивать [{$url}], и загружаем это в файл temp.tmp";
                if (preg_match("/[txt|pdf|doc]$/", $url)) {
                    if (!empty ($url)) {
                        $html = parent::putget_dataa($url);
                        if (!empty($html)) {
                            $fileNames = __DIR__ . "/books/temp.tmp";
                            file_put_contents($fileNames, $html);

                            if (file_exists($fileNames)) {
                                //                           echo "<hr>The file $fileNames exists";    http://bookkatalog/%D0%A2%D0%97_back-end.pdf

                                $extension = parent::getExtension( $url );
                                $names1 = parent::getRandomFileName(__DIR__ . "/books/") . "." . $extension;
                                $names = __DIR__ . "/books/" . $names1;

                                $data['url'] = $url;
                                $variable .= ",  `url` = :url";
                                $data['file'] = $names1;
                                $variable .= ",  `file` = :file";
                                $data['typeLoadFile'] = $books['typeLoadFile'];
                                $variable .= ", `typeLoadFile` = :typeLoadFile";

                                if ( rename($fileNames, $names) ) {
                                    echo "<span class='result success'>Файл " . $names . " корректен и успешно попал на сервер как файл.</span>";
                                    echo "</pre><hr style='border-top: 1px solid red;'>";
                                } else {
                                    echo "<br><span class='result fail'>Ошибка, скорей всего очень длинное название файла typeLaodFile == 2 !</span><br>{$names}";
                                }
                            } else {
                                //                           echo "<hr>The file $fileNames does not exist";
                            }
                        }
                    } else {
                        echo "<br>Пустой адрес файла URL [{$url}] ";
                    }
                } else {
                    echo "<br>Неправильная ссылка, ссылка должна иметь в конце расширение TXT, PDF, DOC ";
                }
            }
        } else {
            echo "<br>books['typeLoadFile'] пуста {{$books['typeLoadFile']}] ";
        }

         //     Обрабатываем в цикле дополнительные изображения
        if ( !empty ( $files['dopPhoto'] ))
            {
            foreach ( $files['dopPhoto'] as $key=>$value ) {
                foreach ( $value as $key2=>$val ) {
                    $files2[$key2][$key]= $val;
                     }
                }
               //debug ($files2);
         foreach ($files2 as $val) {
                   if ( !empty ($val['name']) ) book::AddDopImage ( $books['book_id'], $val['name'], $val['tmp_name'] );
                }
            }

        // Обрабатываем главное изображение
        if ( ( $files['photo']['error'] == 0 ) AND !empty ( $files['photo']['name'] ) )
            {
                      $extension2="";     $extension2= parent::getExtension ( $files['photo']['name'] );
                      $tmp_name = $files['photo']['tmp_name'];
                      $names1 =  parent::getRandomFileName(  __DIR__ . "/images/images/" ) . "." . $extension2;
                      $names=  __DIR__ . "/images/images/" . $names1 ;
                  // копируем файл и добавляем в <br>";
                      $variable .= ",  `photo` = :photo";
                       $data['photo']= $names1;

                if ( move_uploaded_file( $tmp_name, $names ) ) {
                    echo "<span class='result success'>Файл ".$names." корректен и успешно попал на сервер как файл photo.</span>";
                    echo "</pre><hr style='border-top: 1px solid red;'>";
                }	else {echo "<br><span class='result fail'>Ошибка, скорей всего очень длинное название файла photo !</span><br>{$names}";}
            }

           if (!empty( $books['author_id'] )) {
                book::AddAuthor ( $books['book_id'], $books['author_id'] );
           }

            try {
                $sql_queryes = "UPDATE `t_book` SET `heading_id` =  :heading_id , `publishing_id` =  :publishing_id , `namebook` = :namebook, `date_heading` = :date_heading, `update_time` = :update_time {$variable} WHERE `book_id` = :book_id LIMIT 1 ;";
                $queryes = $pdo->prepare($sql_queryes);

//                echo "<hr>В итоге получаем такой запрос: [{$sql_queryes}]";  echo "<hr>В итоге получаем такие данные:"; debug ($data); echo "<hr>";
                $results= $queryes->execute( $data );
            } catch (PDOException $e) {
                echo 'Подключение не удалось: ' . $e->getMessage();
                die();
                $result= false;
            }

         if ( $results ) { $result= true; } else { $result= false; }
        } else { echo "<center>Не были переданы все переменные.</center>";
                echo "book_id={$books['book_id']}, heading_id={$books['heading_id']}, publishing_id={$books['publishing_id']}, namebook={$books['namebook']}<br>";
                $result= false;
        }
        return $result;
    }


    /**
     * Удаление некоторые доп картинки по их номерам
     * @param $book_ids - id картинок массив
     * @return bool - возвращает результат, успешно или нет.
     */
    static public function DeleteDopImage ( $book_ids )
    {   include __DIR__ . "/bd/pdoconnect.php";
        if (!empty ($book_ids)) {
            $result = false;
            try {
                $in  = str_repeat('?,', count($book_ids) - 1) . '?';
                $sql_queryes = "DELETE FROM `t_book_dop_images` WHERE `id` IN ({$in}) ;";
                echo "<br>DELETE FROM `t_book_dop_images` WHERE `id` IN (".implode(",", $book_ids ) .") ;";
                $queryes = $pdo->prepare($sql_queryes);
                $results = $queryes->execute( $book_ids );
            } catch (PDOException $e) {
                echo 'Подключение не удалось: ' . $e->getMessage();
                die();
                $result = false;
            }
            if ($results) {
                $result = true;
            } else {
                $result = false;
            }
        }

        return $result;
    }


    /**
     * Добавить доп картинку
     * @param $book_ids - id картинок массив
     * @return bool - возвращает результат, успешно или нет.
     */
    static public function AddDopImage ( $book_id, $fileName, $sourse )
    {   include __DIR__ . "/bd/pdoconnect.php";

        if (!empty ($book_id)) {
        // Копируем файл
            $extension= parent::getExtension ( $fileName );
            $tmp_name = $sourse;
            $names1 =  parent::getRandomFileName( __DIR__ . "/images/dop_images/" ) . "." . $extension;
            $names=  __DIR__ . "/images/dop_images/" . $names1 ;

            if ( move_uploaded_file($tmp_name, $names ) ) {
                echo "<span class='result success'>Файл доп изображения {$names} корректен и успешно попал на сервер как файл.</span>";
                echo "</pre><hr style='border-top: 1px solid red;'>";
        //  Записываем в БД
            $result = false;
            try {
                $sql_queryes = "INSERT INTO `t_book_dop_images` ( `book_id`, `namefile_images` ) VALUES ( :book_id, :namefile_images ) ;";
//                echo "DELETE FROM `t_book_do_images` WHERE `book_id` IN (".implode(",", $book_ids ) .") ;";
                $queryes = $pdo->prepare($sql_queryes);
                $results = $queryes->execute( array ( ':book_id'=>$book_id, ':namefile_images'=>$names1 )  );
            } catch (PDOException $e) {
                echo 'Подключение не удалось: ' . $e->getMessage();
                die();
                $result = false;
            }
            if ($results) {
                $result = true;
            } else {
                $result = false;
            }
           }	else {echo "<br><span class='result fail'>Ошибка, скорей всего очень длинное название файла AddDopImage !</span><br>{$names}"; $result = false; }
        } else {echo "<br><span class='result fail'>Не поступил book_id [{$book_id}] !</span>";  $result = false; }

        return $result;
    }

    /** Метод ля переопределения авторов к книге
     * @param $book_id - ид книги
     * @param $author_ids - массив авторов
     * @return bool
     */
    static public function  AddAuthor ( $book_id, $author_ids )
    {      include __DIR__ . "/bd/pdoconnect.php";
        if ( !empty ($book_id) AND !empty ($author_ids) ) {
      //
            $results1= true;
            $results1= Book::DeleteAuthorBook ( $book_id );

        if ( $results1 )
            foreach ( $author_ids as $value )
            {
            try {
                $sql_queryes = "INSERT INTO `t_author_book` ( `book_id`, `author_id`) VALUES ( :book_id, :author_id ) ;";
               //echo "<br>INSERT INTO `t_author_book` ( `book_id`, `author_id`) VALUES ( '{$book_id}', '{$value}' ) ;";
                $queryes = $pdo->prepare($sql_queryes);
                $results2 = $queryes->execute( array ( ':book_id'=>$book_id, ':author_id'=>$value ) );
            } catch (PDOException $e) {
                  echo 'Подключение не удалось: ' . $e->getMessage();
                  die();
                     $result = false;
              }
            }
            if ($results2) {
                $result = true;
            } else {
                $result = false;
            }
          }
         return $result;
        }

       /** Удаляем привязку книги и авторов
        *
       */
      static public function DeleteAuthorBook ( $book_id )
      {   include __DIR__ . "/bd/pdoconnect.php";
             try {
                $sql_queryes = "DELETE FROM `t_author_book` WHERE `book_id` = :book_id ;";
                $queryes = $pdo->prepare($sql_queryes);
                $results1 = $queryes->execute( array ( ':book_id'=>$book_id ) );
            } catch (PDOException $e) {
                echo 'Подключение не удалось: ' . $e->getMessage();
                die();
                $result = false;
            }
          if ($results1) {
              $result = true;
          } else {
              $result = false;
          }
            return $result;
       }


    //  Удаляем книгу
    /**
     * Удаление книги
     * @param $book_id - id книги
     * @return bool - возвращает результат, успешно или нет.
     */
    static public function DeleteBook ( $book_id )
    {   include __DIR__ . "/bd/pdoconnect.php";
        if (!empty ( $book_id )) {

        //  Удаляем книгу
            try {
                $sql_queryes = "DELETE FROM `t_book` WHERE `book_id` = :book_id ;";
                $queryes = $pdo->prepare($sql_queryes);
                $results = $queryes->execute( array ( ":book_id"=> $book_id ) );
            } catch (PDOException $e) {
                echo 'Подключение не удалось: ' . $e->getMessage();
                die();
                $result = false;
            }


        //  Удаляем привязку книги и автора
            $results2= Book::DeleteAuthorBook ( $book_id );

        //  Удаляем дополнительные фото книги
            try {
                $sql_queryes = "DELETE FROM `t_book_dop_images` WHERE `book_id` = :book_id ;";
                $queryes = $pdo->prepare($sql_queryes);
                $results3 = $queryes->execute( array ( ":book_id"=> $book_id ) );
            } catch (PDOException $e) {
                echo 'Подключение не удалось: ' . $e->getMessage();
                die();
                $result = false;
            }

            if ( $results == true ) {
                $result = true;
                //  При успешном удалении из базы необходимо удалять файл текст книг + основная картинка

            } elseif ( $results3 == true) {
                $result = true;

                //  При успешном удалении из базы ,необходимо удалять файлы доп картинок

            } elseif (!empty ($results) AND !empty ($results2) AND !empty ($results3)) {
                $result = true;
            } else {
                $result = false;
            }
        } else { echo "<center>Не были переданы все переменные book_id.</center>"; $result= false;}
        return $result;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: user
 */
require $_SERVER['DOCUMENT_ROOT'] . "/app/apps.php";

class author extends baseClass
{
/**
 *  Работы с рубриками
 */
    public $pdo;

    public function __construct()
    {
        include $_SERVER['DOCUMENT_ROOT'] . "/bd/pdoconnect.php";
        $this->pdo= $pdo;
        // устанавливаем значение таблицы, в которой хранятся данные по новостям
        $this->table = 't_author';
        $this->id_field = 'author_id';

     // вызываем ф-ю считывания всей таблицы
        $this->readTable();
    }


    /** переопределяем абстрактный метод печати уникальный для каждого класса
     *
     */
    public function viewAll()
    {   $table = "";
        if (!empty($this->author()))
        foreach ($this->author() as $value){
            $table .= "<tr><form name='publ' action='edit.php'  method='post'>
                     <input type='hidden' name='author_id' value = '{$value['author_id']}' >
                    <td style='text-align: center; border-left: 1px solid black; '><input type='image' src='/images/edit.png' width='12' name='edit' alt='Редактировать'></td>
                    <td style='text-align: center; border-left: 1px solid black; '><input type='image' src='/images/delete.png' width='12' name='delete' alt='Удалить'></td>
                    <td style='text-align: center; border-left: 1px solid black; '>{$value['author_id']}</td>
                    <td style='text-align: center; border-left: 1px solid black; '>{$value['name_author']}</td>
                    </form></tr>";
        }

        $header = "<tr>
                    <th style='width: 10px; border-left: 1px solid black; '>E</th>
                    <th style='width: 10px; border-left: 1px solid black; '>D</th>
                    <th style='border-left: 1px solid black; '>ID автора<br>author_id</th>
                    <th style='border-left: 1px solid black; '>Ф.И.О. автора<br>name_author</th>
                </tr>";

        $author= "<table style='width:99%; border: 1px solid black; '>" . $header . $table . "</table>";
        unset ($value);
        return $author;
    }

    /**
     * Получаем список всех авторов в массив из таблицы t_author
     * @return $author - ассоциативный массив id автора = данные по автору
     */
    public function author()
    {
        if ($this->properties)
        {
            foreach ( $this->properties as $value) {
                $author[$value['author_id']] = array ( 'author_id'=>$value['author_id'],
                                                       'name_author'=>$value['name_author'] );
            }
        } else { //echo "<br>this->properties пуст";
            $author =false;
        }

     return $author;
    }


    /**
     * Добавление новый автор
     * массив
     * @param name_author - название автора
     * @return bool - возвращает результат, успешно или нет.
     */
    static public function Addauthor ( $author )
    {   include $_SERVER['DOCUMENT_ROOT'] . "/bd/pdoconnect.php";
        if (!empty ($author['name_author']) ) {
            try {
                $sql_queryes = "INSERT INTO `t_author` (`name_author` ) VALUES ( :name_author ) ;";
                $queryes = $pdo->prepare($sql_queryes);
                $results = $queryes->execute(
                    array( ":name_author" => $author['name_author'] ));
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

        } else { echo "<center>Не были переданы все переменные.</center>";
                echo "name_author={$author['name_author']}<br>";
                $result= false;
        }
        return $result;
    }

    /**
     * Обновление свойств автора
     * массив
     * @param $author_id - id автора
     * @param name_author - название автора
     * @return bool - возвращает результат, успешно или нет.
     */
    static public function UpdateAuthor ( $author )
    {   include $_SERVER['DOCUMENT_ROOT'] . "/bd/pdoconnect.php";
         if (!empty ($author['author_id']) AND !empty ($author['name_author']) ) {

            try {
                $sql_queryes = "UPDATE `t_author` SET `name_author` = :name_author WHERE `author_id` = :author_id LIMIT 1 ;";
                $queryes = $pdo->prepare($sql_queryes);
                $results= $queryes->execute( array( ":author_id" => $author['author_id'],
                                                    ":name_author" => $author['name_author'] ) );
            } catch (PDOException $e) {
                echo 'Подключение не удалось: ' . $e->getMessage();
                die();
                $result= false;
            }

         if ( $results ) { $result= true; } else { $result= false; }
        } else { echo "<center>Не были переданы все переменные.</center>";
                echo "author_id={$author['author_id']}, name_author={$author['name_author']}<br>";
                $result= false;
        }
        return $result;
    }


    /**
     * Удаление автора. Производится из таблицы справочника + таблица соответствий книга = автор
     * @param $author_id -  id автора
     * @return bool - возвращает результат, успешно или нет.
     */
    static public function DeleteAuthor ( $author_id )
    {   include $_SERVER['DOCUMENT_ROOT'] . "/bd/pdoconnect.php";
        if (!empty ( $author_id )) {
            //  Удаляем автора из таблицы справочника
            try {
                $sql_queryes = "DELETE FROM `t_author` WHERE `author_id` = :author_id ;";
                $queryes = $pdo->prepare($sql_queryes);
                $results = $queryes->execute( array ( ":author_id"=> $author_id ) );
            } catch (PDOException $e) {
                echo 'Подключение не удалось: ' . $e->getMessage();
                die();
                $result = false;
            }
            unset($sql_queryes);unset($queryes);

            //  Удаляем привязку автора к книге
            try {
                $sql_queryes = "DELETE FROM `t_author_book` WHERE `author_id` = :author_id ;";
                $queryes = $pdo->prepare($sql_queryes);
                $results2 = $queryes->execute( array ( ":author_id"=> $author_id ) );
            } catch (PDOException $e) {
                echo 'Подключение не удалось: ' . $e->getMessage();
                die();
                $result = false;
            }

            if (!empty ($results) AND !empty ($results2)) {
                $result = true;
            } else {
                $result = false;
            }
        } else { echo "<center>Не были переданы все переменные heading_id.</center>"; $result= false;}
        return $result;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: user
 */
require $_SERVER['DOCUMENT_ROOT'] . "/app/apps.php";

class publishing extends baseClass
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
        $this->table = 't_publishing';
        $this->id_field = 'publishing_id';

     // вызываем ф-ю считывания всей таблицы
        $this->readTable();
    }


    /** переопределяем абстрактный метод вывода уникальный для каждого класса
     *
     */
    public function viewAll()
    {   $table = "";

        foreach ($this->Publishing() as $value){
            $table .= "<tr><form name='publ' action='edit.php'  method='post'>
                     <input type='hidden' name='publishing_id' value = '{$value['publishing_id']}' >
                    <td style='text-align: center; border-left: 1px solid black; '><input type='image' src='/images/edit.png' width='12' name='edit' alt='Редактировать'></td>
                    <td style='text-align: center; border-left: 1px solid black; '><input type='image' src='/images/delete.png' width='12' name='delete' alt='Удалить'></td>
                    <td style='text-align: center; border-left: 1px solid black; '>{$value['publishing_id']}</td>
                    <td style='text-align: center; border-left: 1px solid black; '>{$value['name_publishing']}</td>
                    <td style='text-align: center; border-left: 1px solid black; '>{$value['addres_publishing']}</td>
                    <td style='text-align: center; border-left: 1px solid black; '>{$value['tel_publishing']}</td>
                    </form></tr>";
        }

        $header = "<tr>
                    <th style='width: 10px; border-left: 1px solid black; '>E</th>
                    <th style='width: 10px;'>D</th>
                    <th style='border-left: 1px solid black; '>ID издательства<br>publishing_id</th>
                    <th style='border-left: 1px solid black; '>Название издательства<br>name_publishing</th>
                    <th style='border-left: 1px solid black; '>Адрес издательства<br>addres_publishing</th>
                    <th style='border-left: 1px solid black; '>Телефон издательства<br>tel_publishing</th>
                </tr>";

        $Publishing= "<table style='width:99%; border: 1px solid black; '>" . $header . $table . "</table>";
        unset ($value);
        return $Publishing;
    }

    /**
     * Получаем список всех рубрик из таблицы t_publishing
     * @return $headings - ассоциативный массив id издательства = все данные по издательству
     */
    public function Publishing()
    {
        if ($this->properties)
        {
            foreach ( $this->properties as $value) {
                $publishing[$value['publishing_id']] = array ( 'publishing_id'=>$value['publishing_id'],
                                                            'name_publishing'=>$value['name_publishing'],
                                                            'addres_publishing'=>$value['addres_publishing'] ,
                                                            'tel_publishing'=>$value['tel_publishing'] );
            }
        } else { echo "<br>this->properties пуст";}

     return $publishing;
    }


    /**
     * Добавление нового издательства
     * массив
     * @param name_publishing - имя издательства
     * @param addres_publishing - адрес издательства
     * @param tel_publishing - телефон издательства
     * @return bool - возвращает результат, успешно или нет.
     */
    static public function AddPublishing ( $publishing )
    {   include $_SERVER['DOCUMENT_ROOT'] . "/bd/pdoconnect.php";
        if (!empty ($publishing['name_publishing']) ) {
            try {
                $sql_queryes = "INSERT INTO `t_publishing` (`name_publishing`, `addres_publishing` , `tel_publishing` ) VALUES ( :name_publishing, :addres_publishing , :tel_publishing ) ;";
                $queryes = $pdo->prepare($sql_queryes);
                $results = $queryes->execute(
                    array(":name_publishing" => $publishing['name_publishing'],
                             ":addres_publishing" => $publishing['addres_publishing'],
                             ":tel_publishing" => $publishing['tel_publishing'] ));
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
                echo "name_publishing={$publishing['name_publishing']}, addres_publishing={$publishing['addres_publishing']}, tel_publishing={$publishing['tel_publishing']}<br>";
                $result= false;
        }
        return $result;
    }


    /**
     * Обновление свойств издательства
     * массив
     * @param publishing_id - id издательства
     * @param name_publishing - имя издательства
     * @param addres_publishing - адрес издательства
     * @param tel_publishing - телефон издательства
     * @return bool - возвращает результат, успешно или нет.
     */
    static public function UpdatePublishing ( $publishing )
    {   include $_SERVER['DOCUMENT_ROOT'] . "/bd/pdoconnect.php";
         if (!empty ($publishing['publishing_id']) AND !empty ($publishing['name_publishing']) ) {

            try {
                $sql_queryes = "UPDATE `t_publishing` SET `name_publishing` = :name_publishing, `addres_publishing` = :addres_publishing, `tel_publishing` = :tel_publishing WHERE `publishing_id` = :publishing_id LIMIT 1 ;";
                $queryes = $pdo->prepare($sql_queryes);
                $results= $queryes->execute( array( ":publishing_id" => $publishing['publishing_id'],
                                                    ":name_publishing" => $publishing['name_publishing'],
                                                    ":addres_publishing" => $publishing['addres_publishing'] ,
                                                    ":tel_publishing" => $publishing['tel_publishing'] ) );
            } catch (PDOException $e) {
                echo 'Подключение не удалось: ' . $e->getMessage();
                die();
                $result= false;
            }

         if ( $results ) { $result= true; } else { $result= false; }
        } else { echo "<center>Не были переданы все переменные.</center>";
                echo "publishing_id={$publishing['publishing_id']}, name_publishing={$publishing['name_publishing']}, addres_publishing={$publishing['addres_publishing']}, tel_publishing={$publishing['tel_publishing']}<br>";
                $result= false;
        }
        return $result;
    }


    /**
     * Удаление издательства
     * @param publishing_id - id издательства
     * @return bool - возвращает результат, успешно или нет.
     */
    static public function DeletePublishing ( $publishing_id )
    {   include $_SERVER['DOCUMENT_ROOT'] . "/bd/pdoconnect.php";
        if (!empty ( $publishing_id )) {
            //  Удаляем издателя из БД
            try {
                $sql_queryes = "DELETE FROM `t_publishing` WHERE `publishing_id` = :publishing_id ;";
                $queryes = $pdo->prepare($sql_queryes);
                $results = $queryes->execute( array ( ":publishing_id"=> $publishing_id ) );
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
        } else { echo "<center>Не были переданы все переменные heading_id.</center>"; $result= false;}
        return $result;
    }

}
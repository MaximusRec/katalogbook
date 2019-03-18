<?php
/**
 * Created by PhpStorm.
 * User: user
 */
require_once $_SERVER['DOCUMENT_ROOT'] . "/app/apps.php";

class heading extends baseClass
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
        $this->table = 't_heading';
        $this->id_field = 'heading_id';

     // вызываем ф-ю считывания всей таблицы
        $this->readTable();
    }


    /** переопределяем абстрактный метод печати уникальный для каждого класса
     *
     */
    public function viewAll()
    {
        $Headin= $this->getMenuHtml( $this->viewHeadinTree(), 0 );
        return $Headin;
    }

    /**
     * Получаем список всех рубрик из таблицы t_heading
     * @return $headings - ассоциативный массив id рубрики = название рубрики
     */
    public function Headin()
    {
        if ($this->properties)
        {
            foreach ( $this->properties as $value) {
                $headings[$value['heading_id']] = array ( 'heading_id'=>$value['heading_id'], 'parent_id'=>$value['parent_id'], 'name_heading'=>$value['name_heading'] );
            }
        } else {    $headings= false;

        }

     return $headings;
    }


    //  получаем массив древовидной структуры рубрик.
    public function viewHeadinTree ()
    {
        $tree=[];
        $data= $this->Headin();

        foreach ($data as $id=>&$node) {
             if (!$node['parent_id'])
                 $tree[$id] = &$node;
              else
                 $data[$node['parent_id']]['childs'][$node['heading_id']] = &$node;
        }
    return $tree;
    }

    // Получаем номера рубрики и потомков
    public function viewHeadinTree_childs ( $parent_id )
    {
        $data= static::Headin();
        $parent_ids[]= $parent_id;

        foreach ($data as $id=>$node) {
            if (!empty($node['parent_id']) AND in_array( $node['parent_id'] , $parent_ids ) )
                $parent_ids[]= $node['heading_id'];
        }
        return $parent_ids;
    }


    /**
     * Формируем список для вывода
     * @param $tree - Массив многомерный древовидный
     * @param $counter - необходим доп параметр для типа маркера  списка
     * @return string HTML
     */
    public function getMenuHtml($tree, $counter ){
        $str = '';
        foreach ($tree as $category) {
            $str .= $this->catToTemplate($category, $counter);
        }
        return $str="<ul>$str</ul>";
    }


    /**
     * Формируем список для вывода, доп ф-я, рекурсивная
     * @param $tree - Массив многомерный древовидный
     * @param $counter - необходим доп параметр для типа маркера  списка
     * @return string HTML
     */
    public function catToTemplate($category, $counter ){
        $str = ''; if (empty ($counter) ) $counter = 0;

        if ( $counter % 3 == 0) $listStyleType= "lower-alpha";            //Строчные латинские буквы (a, b, c, d,...).
        elseif ( $counter % 3 == 1) $listStyleType= "lower-roman";        //Римские числа в нижнем регистре (i, ii, iii, iv, v,...).
        elseif ( $counter % 3 == 2) $listStyleType= "decimal";            //Арабские числа (1, 2, 3, 4,...).

        $str .= '<form name="li" action="edit.php"  method="post"><li style=" list-style-type: '. $listStyleType . '; "> ('.$category['heading_id'].') ';
        $str .= $category['name_heading'] .
                '  <input type="hidden" name="heading_id" value="' . $category['heading_id'] . '">' .
                '  <input type="image" src="../images/edit.png" width="12" name="edit" alt="Редактировать">' .
                '  <input type="image" src="../images/delete.png" width="12" name="delete" alt="Удалить">';
        $str .= '</form></li>';

        if ( !empty( $category['childs'] ) )
        {   $counter++;
            $str .= '<ul>';
            $str .= $this->getMenuHtml($category['childs'], $counter );
            $str .= '</ul>';
        } else {   $i=0; }
        return $str;
    }

    /**
     * Добавление новой категории
     * массив
     * @param $parent_id - родительский id
     * @param $name_heading - название Рубрики
     * @return bool - возвращает результат, успешно или нет.
     */
    static public function AddHeading ( $headings )
    {   include $_SERVER['DOCUMENT_ROOT'] . "/bd/pdoconnect.php";
        if ( !empty ($headings['name_heading']) ) {

            echo "parent_id={$headings['parent_id']}, name_heading={$headings['name_heading']}<br>";
            try {
                $sql_queryes = "INSERT INTO `t_heading` (`parent_id`, `name_heading` ) VALUES ( :parent_id, :name_heading ) ;";
                $queryes = $pdo->prepare($sql_queryes);
                $results= $queryes->execute( array( ":parent_id" => $headings['parent_id'], ":name_heading" => $headings['name_heading']) );
            } catch (PDOException $e) {
                echo 'Подключение не удалось: ' . $e->getMessage();
                die();
                $result= false;
            }
         if ( $results ) { $result= true; } else { $result= false; }

        } else { echo "<center>Не были переданы все переменные.</center>";
                echo "parent_id={$headings['parent_id']}, name_heading={$headings['name_heading']}<br>";
                $result= false;
        }
        return $result;
    }


    /**
     * Обновление свойств рубрики
     * массив
     * @param $heading_id - id рубрики
     * @param $parent_id - родительский id
     * @param $name_heading - название Рубрики
     * @return bool - возвращает результат, успешно или нет.
     */
    static public function UpdateHeading ( $headings )
    {   include $_SERVER['DOCUMENT_ROOT'] . "/bd/pdoconnect.php";
        if (!empty ($headings['parent_id']) AND !empty ($headings['parent_id']) AND !empty ($headings['name_heading']) ) {

            try {
                $sql_queryes = "UPDATE `t_heading` SET `parent_id` =  :parent_id , `name_heading` = :name_heading WHERE `heading_id` = :heading_id LIMIT 1 ;";
                $queryes = $pdo->prepare($sql_queryes);
                $results= $queryes->execute( array( ":heading_id" => $headings['heading_id'],
                                                    ":parent_id" => $headings['parent_id'],
                                                    ":name_heading" => $headings['name_heading'] ) );
            } catch (PDOException $e) {
                echo 'Подключение не удалось: ' . $e->getMessage();
                die();
                $result= false;
            }

         if ( $results ) { $result= true; } else { $result= false; }
        } else { echo "<center>Не были переданы все переменные.</center>";
                echo "heading_id={$headings['heading_id']}, parent_id={$headings['parent_id']}, name_heading={$headings['name_heading']}<br>";
                $result= false;
        }
        return $result;
    }


    /**
     * Удаление рубрики
     * @param $heading_id - id рубрики
     * @return bool - возвращает результат, успешно или нет.
     */
    static public function DeleteHeading ( $heading_id )
    {   include $_SERVER['DOCUMENT_ROOT'] . "/bd/pdoconnect.php";
        if (!empty ( $heading_id )) {
        //  Получаем список потомков + родитель
            $obj = NEW heading();
            $id= $obj->viewHeadinTree_childs ( $heading_id );
        if ( !empty ($id) ) {
            //  Удаляем рубрику и её детей из БД
            try {
                $in = str_repeat('?,', count($id) - 1) . '?';
                $sql_queryes = "DELETE FROM `t_heading` WHERE `heading_id` IN ({$in}) ;";
                echo "DELETE FROM `t_heading` WHERE `heading_id` IN (" . implode(", ", $id) . ") ;";
                $queryes = $pdo->prepare($sql_queryes);
                $results = $queryes->execute($id);
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
        } else { echo "<center>Не были переданы все переменные heading_id.</center>"; $result= false;}
        return $result;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.03.2019
 * Time: 23:03
 */

function debug ( $value )
{
    echo "<pre>"; print_r ( $value );echo "</pre>";
}

/** Перехоод на нужную страницу
 * @param $page - адрес страницы перехода
 *
 */
function load_page( $page ) {
    ?>
    <script type="text/javascript">
        var path = '<?php echo $page;?>';
        location.target='viewframe';
        location.href=path;
    </script><?
}

/** Переворачиваем дату в обратном порядке + разделители
 * @param - доп параметр
 * srtings - дата время
 */

function flip_dates ($srtings, $param) {	//	xxxx-xx-xx -> xx-xx-xxxx
    $dates= array(); $day_week= array(1=>'Пн',2=>'Вт',3=>'Ср',4=>'Чт',5=>'Пт',6=>'Сб',0=>'Вс');	$data= "";
    if (preg_match('/([\d]{4})[-.]{1}([\d]{2})[-.]{1}([\d]{2})/', $srtings, $srting_match))
    {$dates["0"]= str_pad($srting_match[3], 2, "0", STR_PAD_LEFT)."-".str_pad($srting_match[2], 2, "0", STR_PAD_LEFT)."-".$srting_match[1];
        $dates["1"]= str_pad($srting_match[3], 2, "0", STR_PAD_LEFT).".".str_pad($srting_match[2], 2, "0", STR_PAD_LEFT).".".$srting_match[1];

        if (preg_match('/([\d]{2}:[\d]{2}:[\d]{2})/Siu', $srtings, $string_time))	{
            $dates["2"]= $string_time[1];
            $data= $string_time[1];
        }
        $date1= str_pad($srting_match[3], 2, "0", STR_PAD_LEFT).".".str_pad($srting_match[2], 2, "0", STR_PAD_LEFT).".".str_pad($srting_match[1], 4, "0", STR_PAD_LEFT);
        $dates["3"]= $date1." ".$data;
        $dates["4"]= date("w",strtotime($srtings));
    }
    else {
        if (preg_match('/([\d]{2})[-.]{1}([\d]{2})[-.]{1}([\d]{4})/Siu', $srtings, $srting_match))
        { $dates["0"]= $srting_match[3]."-".str_pad($srting_match[2], 2, "0", STR_PAD_LEFT)."-".str_pad($srting_match[1], 2, "0", STR_PAD_LEFT);
            $dates["1"]= $srting_match[3].".".str_pad($srting_match[2], 2, "0", STR_PAD_LEFT).".".str_pad($srting_match[1], 2, "0", STR_PAD_LEFT);
            if (preg_match('/([\d]{2}:[\d]{2}:[\d]{2})/', $srtings, $string_time))
                $dates["2"]= $string_time[1];	else $dates["2"]= "00:00:00";
            $dates["3"]= $srting_match[3].".".
                str_pad($srting_match[2], 2, "0", STR_PAD_LEFT).".".
                str_pad($srting_match[1], 2, "0", STR_PAD_LEFT)." ".
                $dates["2"];
            $dates["4"]= date("w",strtotime($srting_match[3]."-".str_pad($srting_match[2], 2, "0", STR_PAD_LEFT)."-".str_pad($srting_match[1], 2, "0", STR_PAD_LEFT)));
        }
    }

    switch ($param)	{
        case 0: return @$dates["0"];break;
        case 1: return @$dates["1"];break;
        case 2: return @$dates["2"];break;
        case 3: return @$dates["3"];break;
        case 4: return $day_week[$dates["4"]];break;
    }
}



abstract class baseClass
{

    protected $table;   // таблица, в которой хранятся данные по элементу
    protected $id_field;    // свойство id поля элементу
    protected $quontity;
    protected $page;

    // свойства элемента нам неизвестны
    protected $properties = array();

    // конструктор
    public function __construct($id)
    {
        include $_SERVER['DOCUMENT_ROOT'] . "../bd/pdoconnect.php";
        $this->pdo = $pdo;
    }

// метод, одинаковый для любого таблицы, возвращает значение одной запись в БД
    public function readTable()
    {
        if (!empty ($_POST['page'])) $this->page = $_POST['page']; else $this->page = 0;
        if (!empty ($_POST['quontity'])) $this->quontity = $_POST['quontity']; else $this->quontity = 25;

        // получаем выборку из таблицы заданного кол-ва записей
        $sql_queryes = "SELECT * FROM `{$this->table}` LIMIT :page, :quontity ;";
        try {
            $queryes = $this->pdo->prepare($sql_queryes);
            $queryes->execute(array(':page' => $this->page, ':quontity' => $this->quontity));
            while ($result = $queryes->fetch()) {
                $this->properties[] = $result;
            }
        } catch (PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
            die();
        }
    }

    // метод, одинаковый для любого таблицы, возвращает значение одной запись в БД
    public function readOneRecord($id)
    {
        if (!empty($id)) {
            // получаем еденичные данные
            $sql_queryes = 'SELECT * FROM `' . $this->table . '` WHERE `' . $this->id_field . '`=:id LIMIT 1';
            try {
                $queryes = $this->pdo->prepare($sql_queryes);
                $queryes->execute(array(':id' => $id));
                $this->properties = $queryes->fetch();

            } catch (PDOException $e) {
                echo 'Подключение не удалось: ' . $e->getMessage();
                die();
            }
        }
        return $this->properties;
    }

// метод, одинаковый для любого таблицы, возвращает значение свойства
    public function get_property($name)
    {
        if (isset($this->properties[$name]))
            return $this->properties[$name];

        return false;
    }

// метод, одинаковый для любого типа публикаций, устанавливает значение свойства
    public function set_property($name, $value)
    {
        if (!isset($this->properties[$name]))
            return false;

        $this->properties[$name] = $value;

        return $value;
    }

// а этот метод должен напечатать список/набор элемент
    abstract public function viewAll();


// метод для получения всех рубрик
    static public function headingTable()
    {
        include $_SERVER['DOCUMENT_ROOT'] . "/bd/pdoconnect.php";
        $sql_queryes = "SELECT * FROM `t_heading` ;";
        try {
            $queryes = $pdo->prepare($sql_queryes);
            $queryes->execute();
            while ($result = $queryes->fetch()) {
                $heading[$result['heading_id']] = $result;
            }
        } catch (PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
            die();
        }
        return $heading;
    }


    // метод для получения HTML дерева рубрик
    static public function headingView($data)
    {
        $tree = [];
        foreach ($data as $id => &$node) {
            if (!$node['parent_id'])
                $tree[$id] = &$node;
            else
                $data[$node['parent_id']]['childs'][$node['heading_id']] = &$node;
        }

        function getMenuHtml($tree, $counter)
        {
            $str = '';
            // debug( $tree );
            foreach ($tree as $category) {
                $str .= catToTemplate($category, $counter);
            }
            return $str = "<ul style='line-height: 1; margin-left: -30px;' >$str</ul>";
        }

        function catToTemplate($category, $counter)
        {
            $str = '';
            if (empty ($counter)) $counter = 0;

            if ($counter % 3 == 0) $listStyleType = "lower-alpha";            //Строчные латинские буквы (a, b, c, d,...).
            elseif ($counter % 3 == 1) $listStyleType = "lower-roman";        //Римские числа в нижнем регистре (i, ii, iii, iv, v,...).
            elseif ($counter % 3 == 2) $listStyleType = "decimal";            //Арабские числа (1, 2, 3, 4,...).

            $str .= '<form name="li" action="edit.php"  method="post"><li style=" list-style-type: ' . $listStyleType . '; ">';

            $str .= "<a href='/index.php?r={$category['heading_id']}'>" . $category['name_heading'] . "</a>";

            $str .= '</form></li>';
            if (!empty($category['childs'])) {
                $counter++;
                $str .= '<ul>';
                $str .= getMenuHtml($category['childs'], $counter);
                $str .= '</ul>';
            } else {
                $i = 0;
            }
            return $str;
        }

        return getMenuHtml($tree, 0);
    }


// метод для получения всех авторов
    static public function authorTable()
    {
        include $_SERVER['DOCUMENT_ROOT'] . "/bd/pdoconnect.php";
        $sql_queryes = "SELECT * FROM `t_author` ;";
        try {
            $queryes = $pdo->prepare( $sql_queryes );
            $queryes->execute();
            while ($result = $queryes->fetch()) {
                $authors[$result['author_id']] = $result;
            }
        } catch (PDOException $e) {
            echo 'Подключение не удалось 233: ' . $e->getMessage();
            die();
        }
        return $authors;
    }


// метод для получения всех издательств
    static public function publishingsTable()
    {
        include $_SERVER['DOCUMENT_ROOT'] . "/bd/pdoconnect.php";
        $sql_queryes = "SELECT * FROM `t_publishing` ;";
        try {
            $queryes = $pdo->prepare($sql_queryes);
            $queryes->execute();
            while ($result = $queryes->fetch()) {
                $publishings[$result['publishing_id']] = $result;
            }
        } catch (PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
            die();
        }
        return $publishings;
    }

// метод для получения всех доп изображений
    static public function dopImageBook( $book_id )
    {   include $_SERVER['DOCUMENT_ROOT'] . "/bd/pdoconnect.php";
        $sql_queryes = "SELECT `namefile_images` FROM `t_book_dop_images` WHERE `book_id` = :book_id ;";
        try {
            $queryes = $pdo->prepare($sql_queryes);
            $queryes->execute( array ( ":book_id"=>$book_id ) );
            while ($result = $queryes->fetch()) {
                $dopImage[] = $result['namefile_images'];
            }
        } catch (PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
            die();
        }
        if ( $queryes->rowCount() > 0 ) {$dopImages= $dopImage;} else {$dopImages= false;}
        return $dopImages;
    }

    /** Метод генерации случайного имени файла.
     * @param $path
     * @param string $extension
     * @return string
     */
    public static function getRandomFileName($path, $extension = '')
    {
        $extension = $extension ? '.' . $extension : '';
        $path = $path ? $path . '/' : '';

        do {
            $name = md5(microtime() . rand(0, 9999));
            $file = $path . $name . $extension;
        } while (file_exists($file));

        return $name;
    }

    /** Получить только расширение
     * @param $filename - полный или счастичный путь файла
     * @return mixed
     */
    public static function getExtension($filename)
    {
        $path_info = pathinfo($filename);
        return $path_info['extension'];
    }

    /** Получить только имя файла
     * @param $filename - полный путь
     * @return mixed
     *
     */
    public static function getFileName($filename)
    {
        $path_info = pathinfo($filename);
        return $path_info['filename'];
    }

    /** Метод загрузки внешнего файла
     * @param $url - адрес загружаемого файла
     * @return mixed
     */
    static public function putget_dataa($url)
    {
        $ch = curl_init();
        $timeout = 20;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

}







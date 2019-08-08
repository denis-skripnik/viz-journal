<?php
// Функция основного файла.
function homePage() {
    require_once 'db.php';
    $db = dbConnect();
    
    $page = ($_GET['page'] ?? $_GET['page'] ?? '');
if ($page == 'lactors') {
$pagename = 'Преподаватели';

} else if ($page == 'disciples') {
$pagename = 'Ученики';
} else if ($page == 'lessons') {
$pagename = 'Предметы';
} else if ($page == 'lesson_topics') {
$pagename = 'Темы предметов';
} else if ($page == 'assessments') {
$pagename = 'Оценки';
}
return ($pagename ?? $pagename ?? "");
}

function dropTeacher($teacher = '') {

    require_once 'db.php';
    $db = dbConnect();

    $lessons = $db->query('select * from `lessons`');

    foreach ($lessons as $key => $lesson)
    {
        $new_teachers = array();
        $lactors = explode(',', $lesson["lactors"]);

        foreach ($lactors as $key => $lactor) 
        {
            if (trim($lactor) != trim($teacher))
            {                
                $new_teachers[] = $lactor;
            }
        }
        
        $new_teachers = trim(implode(',', $new_teachers));

       
        $db->query('update `lessons` set `lactors` = "' . $new_teachers . '" where `id` = "' . $lesson["id"] . '"');        
    }

    $db->query('delete from `lactors` where `login` = "' . $teacher . '"');
}

function dropDisciples($disciple = '') {

    require_once 'db.php';
    $db = dbConnect();

    $assessments = $db->query('select * from `assessments`');   

    foreach ($assessments as $key => $assessment)
    {
        if (trim($assessment["disciple"]) == $disciple)
        {
            $db->query('delete from `assessments` where `disciple` = "' . $disciple . '"');
        }        
    }
        
    $db->query('delete from `disciples` where `login` = "' . $disciple . '"');
}

function droLessons($lesson = '') {

    require_once 'db.php';
    $db = dbConnect();

    $lesson_topics = $db->query('select * from `lesson_topics`');   

    foreach ($lesson_topics as $key => $lesson_topic)
    {        
        if (trim(mb_strtolower($lesson_topic["lesson"])) == trim(mb_strtolower($lesson)))
        {
            $db->query('delete from `lesson_topics` where `lesson` = "' . $lesson . '"');
        }        
    }
        
    $db->query('delete from `lessons` where `name` = "' . $lesson . '"');
}

function dataTable($page = '', $role = 0, $user = '') {
    
    require_once 'db.php';

    $table = '';

    $db = dbConnect();

    switch ($page)
    {
        case 'lactors':
            $res  = $db->query('select * from `lactors`');            

            $table = '<table border="1" style="border:1px solid #000;">';
            
            foreach ($res as $key => $value) 
            {
                $delete_button = ($role) ? '<td style="border:1px solid #000;"><a href="/index.php?page=lactors&action=droplactor&lactor=' . $value["login"] . '">Удалить</a></td>' : false;
                $table .= '<tr><td style="border:1px solid #000;">' . $value['login'] . '</td>' . $delete_button . '</tr>';        
            }

            $table .= '</table>';
        break;

        case 'disciples':
            $res  = $db->query('select * from `disciples`');

            $table = '<table border="1" style="border:1px solid #000;">';
            
            foreach ($res as $key => $value) 
            {
                $delete_button = ($role) ? '<td style="border:1px solid #000;"><a href="/index.php?page=disciples&action=dropdisciple&disciple=' . $value["login"] . '">Удалить</a></td>' : false;
                $table .= '<tr><td style="border:1px solid #000;">' . $value['login'] . '</td>' . $delete_button . '</tr>';        
            }

            $table .= '</table>';
        break;

        case 'lessons':

            $res  = $db->query('select * from `lessons`');
            
            $delete_button = ($role) ? '<td style="border:1px solid #000;"><a href="#">Удалить</a></td>' : false;

            $table = '<table border="1" style="border:1px solid #000;">';
            
            foreach ($res as $key => $value) 
            {
                $delete_button = ($role) ? '<td style="border:1px solid #000;"><a onClick="return confirm(\'Удалить урок и все его разделы?\')" href="/index.php?page=lessons&action=droplesson&lesson=' . $value["name"] . '">Удалить</a></td>' : false;

                $table .= '<tr><td style="border:1px solid #000;">' . $value['name'] . '</td><td style="border:1px solid #000;">' . $value['lactors'] . '</td>' . $delete_button . '</tr>';        
            }

            $table .= '</table>';
        break;

        case 'assessments':

            if ($role) {
                $res  = $db->query('select * from `assessments`');
            } else {
                $res  = $db->query('select * from `assessments` where `disciple` = "' . $user . '"');
            }
            
            $table = '<table border="1" style="border:1px solid #000;">';
            
           foreach ($res as $key => $value) 
           {
               $table .= '<tr><td style="border:1px solid #000;">' . $value['date'] . '</td><td style="border:1px solid #000;">' . $value['lesson'] . '</td><td style="border:1px solid #000;">' . $value['disciple'] . '</td><td style="border:1px solid #000;">' . $value['assessment'] . '</td>' . ($delete_button ?? $delete_button ?? "") . '</tr>';        
           }

           $table .= '</table>';

        break;
        
        case 'lesson_topics':

            $res  = $db->query('select * from `lesson_topics`');
            
            $table = '<table border="1" style="border:1px solid #000;">';
            
            foreach ($res as $key => $value) 
            {
                $table .= '<tr><td style="border:1px solid #000;">' . $value['date'] . '</td><td style="border:1px solid #000;">' . $value['lesson'] . '</td><td style="border:1px solid #000;">' . $value['topics'] . '</td></tr>';        
            }

            $table .= '</table>';
        break;
        
        default:
    }
      
    return $table;
}

function checkRole($login = false, $role = 0) {

    // 0 - ученик
    // 1 - преподаватель
    
    $json_conf = file_get_contents( '../config.json' );
    $config = json_decode($json_conf, JSON_OBJECT_AS_ARRAY);

    require_once 'db.php';

    $db = dbConnect();

    $sql = 'select * from `lactors` where `login` = "' . $login . '"';    

    $res = $db->query($sql);

    if (isset($res->rowCount) || trim($login) == $config["metodist"]) $role = 1;

    return $role;
    }



// Функция для страницы добавления данных
function addPage() {
    $data = $_GET['data'];
    $page_data = [];
    if ($data == 'lactors') {
        $page_data['title'] = 'Добавление преподавателя';
        $page_data['content'] = file_get_contents('add/lactor.html');
    } else if ($data == 'disciples') {
        $page_data['title'] = 'Добавление ученика';
        $page_data['content'] = file_get_contents('add/disciple.html');
    } else if ($data == 'lessons') {
        $page_data['title'] = 'Добавление предмета';
        $page_data['content'] = file_get_contents('add/lesson.html');
    } else if ($data == 'lesson_topics') {
        $page_data['title'] = 'Добавление темы предмета';
        $page_data['content'] = file_get_contents('add/lesson_topics.html');
    } else if ($data == 'assessments') {
        $page_data['title'] = 'Добавление оценки';
        $page_data['content'] = file_get_contents('add/assessment.html');
    }
    return $page_data;
}
?>
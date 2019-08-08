<?php
require 'functions.php';
$user 		= $_GET['user'];
$page 		= $_GET['page'];
$action 	= (isset($_GET['action'])) ? $_GET['action'] : false;
$lactor 	= (isset($_GET['lactor'])) ? $_GET['lactor'] : false;
$disciple 	= (isset($_GET['disciple'])) ? $_GET['disciple'] : false;
$lesson 	= (isset($_GET['lesson'])) ? $_GET['lesson'] : false;

$role  = checkRole($user);

switch ($page) {
	case 'lactors':

		switch ($action) {
			case 'droplactor':
				dropTeacher($lactor);
			break;
			
			default:				
			break;
		}
		echo dataTable($page, $role, $user);	
	break;

	case 'disciples':
		switch ($action) {		
			case 'dropdisciple':
				dropDisciples($disciple);
			break;
			
			default:				
			break;
		}	
		echo dataTable($page, $role, $user);		
	break;

	case 'lessons':
		switch ($action) {		
			case 'droplesson':
				droLessons($lesson);
			break;
			
			default:				
			break;
		}	
		echo dataTable($page, $role, $user);		
	break;

	case 'lesson_topics':
		echo dataTable($page, $role, $user);
	break;

	case 'assessments':
		echo dataTable($page, $role, $user);
	break;

	default: 
		echo 'sdf';
}
?>
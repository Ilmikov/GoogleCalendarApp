<?php
require_once __DIR__ . '/vendor/autoload.php';
// Include database configuration file 
require_once 'config.php';
// Подключаем библиотеку для работы с API Google
require_once 'auth.php';
// Include Google calendar api handler class 
require_once __DIR__ . '\vendor\google\apiclient-services\autoload.php';
//define('STDIN',fopen("php://stdin","r"));
require_once 'comands.php';

use Google\Client;
use Google\Service\Calendar;


$summary = 'Google I/O 2015';
$location =  'Perm';
$description = 'A chance to hear more about Googles developer products.';
$startdateTime = '2022-09-05T09:00:00+05:00';
$timeZone = 'Asia/Yekaterinburg';
$enddateTime = '2022-09-05T17:00:00+05:00';
$calendarId = 'primary';
$neactorID = "biba";
$attendees = array(
	array('email' => 'iluha.mikov@gmail.com'),
	//array('email' => 'posyagin.anton@gmail.com'),
	//array('email' => 'ilja.mickov@gmail.com'),
);

$startdateTime2 = '2022-09-29T09:00:00+05:00';
$enddateTime2 = '2022-09-29T17:00:00+05:00';

$client = getClient();

if (isset($_GET['key'])) {
	if ($_GET['key'] == 'add') {

		$eventID = add_event($client, $calendarId, $summary, $description, $location, $startdateTime, $timeZone, $enddateTime, $attendees, $neactorID);
		echo $eventID;
	}

	if ($_GET['key'] == 'del') {

		del_event($client, $calendarId, $neactorID);
		//echo $eventID;

	}

	if ($_GET['key'] == 'up') {

		update_event($client, $calendarId, $summary, $description, $location, $startdateTime2, $timeZone, $enddateTime2, $attendees, $neactorID);
	}
}


//del_event($client, $calendarId, 'i6f9uo0bb7o2qtaq93flb069qk');

//$_SESSION['$eventID'] = $eventID;
/*
if (isset($_SESSION['$eventID'])){
	del_event($client, $calendarId, $eventID);
	echo 'event del';
}
//echo $eventID;
*/

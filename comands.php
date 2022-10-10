<?php
// Подключаем библиотеку для работы с API Google
require_once 'vendor/autoload.php';
// Include database configuration file 
//require_once 'config.php';

require_once __DIR__ . '\vendor\google\apiclient-services\autoload.php';

require_once 'config.php';

use Google\Client;

use Google\Service\Calendar;

function add_event($client, $calendar_id, $summary, $description, $location, $startDateTime, $timeZone, $endDateTime, $attendees, $neactor_id)
{

  $optParams = array(
    'optParams' => array(
      'sendUpdates' => 'all',
    ),
  );

  $service = new Google_Service_Calendar($client);

  $event = new Google_Service_Calendar_Event(array(

    'summary' => $summary,
    'location' => $location,
    'description' => $description,
    'start' => array(
      'dateTime' => $startDateTime,
      'timeZone' => $timeZone,
    ),
    'end' => array(
      'dateTime' => $endDateTime,
      'timeZone' => $timeZone,
    ),
    'recurrence' => array(
      'RRULE:FREQ=DAILY;COUNT=1'
    ),
    'attendees' => $attendees/*array(
      array('email' => 'iluha.mikov@gmail.com'),
      array('email' => 'vikakry01@gmail.com'),
    )*/,
    'reminders' => array(
      'useDefault' => FALSE,
      'overrides' => array(
        array('method' => 'email', 'minutes' => 100 * 24 * 60),
        array('method' => 'popup', 'minutes' => 10),
      ),
    ),
  ));

  $event = $service->events->insert($calendar_id, $event, $optParams);
  $eventID = $event->id;

  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
  // Create database connection 
  $db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

  // Check connection 
  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }

  $sqlQ = "INSERT INTO neactor_google_events (google_calendar_event_id, neactor_id, summary,description,location,datatime_start,datatime_end,attendees) VALUES (?,?,?,?,?,?,?,?)";
  $stmt = $db->prepare($sqlQ);
  $stmt->bind_param("ssssssss", $db_google_calendar_event_id, $db_neactor_id, $db_summary, $db_description, $db_location, $db_datatime_start, $db_datatime_end, $db_attendees);
  $db_google_calendar_event_id = $eventID;
  $db_neactor_id = $neactor_id;
  $db_summary = $summary;
  $db_description = $description;
  $db_location = $location;
  $db_datatime_start = $startDateTime;
  $db_datatime_end = $endDateTime;

  $attendees_string = serialize($attendees);
  $db_attendees = $attendees_string;

  $insert = $stmt->execute();


  return $eventID;
}

function del_event($client, $calendar_id, $neactor_id)
{

  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
  // Create database connection 
  $db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

  // Check connection 
  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }

  $service = new Google_Service_Calendar($client);
  // Fetch event details from database 
  $sqlQ = "SELECT * FROM neactor_google_events WHERE neactor_id = '$neactor_id'";
  $stmt = $db->prepare($sqlQ);
  //$stmt->bind_param("s", $neactorID); 
  //$db_event_id = $event_id; 
  $stmt->execute();
  $result = $stmt->get_result();
  $eventData = $result->fetch_assoc();
  $event_id = $eventData['google_calendar_event_id'];

  if (!empty($event_id)) {
    $service->events->delete($calendar_id, $event_id);
    $sqlQ = "DELETE FROM neactor_google_events WHERE google_calendar_event_id = '$event_id'";
    $stmt = $db->prepare($sqlQ);
    $stmt->execute();
  } else {
    echo "Нет совпадений";
  }
}


function update_event($client, $calendar_id, $summary, $description, $location, $startDateTime, $timeZone, $endDateTime, $attendees, $neactor_id)
{
  $service = new Google_Service_Calendar($client);

  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
  // Create database connection 
  $db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

  // Check connection 
  if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
  }


  $sqlQ = "SELECT * FROM neactor_google_events WHERE neactor_id = '$neactor_id'";
  $stmt = $db->prepare($sqlQ);
  $stmt->execute();
  $result = $stmt->get_result();
  $eventData = $result->fetch_assoc();
  $event_id = $eventData['google_calendar_event_id'];

  $event = $service->events->get($calendar_id, $event_id);
  $event->setSummary($summary);
  $event->setDescription($description);
  $event->setLocation($location);

  $start = new Google_Service_Calendar_EventDateTime();
  $start->setDateTime($startDateTime);
  $start->setTimeZone($timeZone);
  $event->setStart($start);

  $end = new Google_Service_Calendar_EventDateTime();
  $end->setDateTime($endDateTime);
  $end->setTimeZone($timeZone);
  $event->setEnd($end);

  $event->setAttendees($attendees);

  $updatedEvent = $service->events->update('primary', $event->getId(), $event);


  $sqlQ = "UPDATE neactor_google_events SET `summary` = '$summary', `description` = '$description', `location` = '$location', `datatime_start` = '$startDateTime', `datatime_end` = '$endDateTime' WHERE neactor_id = '$neactor_id';";
  echo $sqlQ;
  $stmt = $db->prepare($sqlQ);
  $stmt->execute();


  // Print the updated date.
  echo $updatedEvent->getUpdated();
}

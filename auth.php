<?php 
require_once __DIR__ . '/vendor/autoload.php';
// Include database configuration file 
//require_once 'config.php';
// Include Google calendar api handler class 
require_once __DIR__.'\vendor\google\apiclient-services\autoload.php';
//define('STDIN',fopen("php://stdin","r"));
//require_once 'comands.php';

use Google\Client;
use Google\Service\Calendar;

/**
 * Returns an authorized API client.
 * @return Client the authorized client object
 */

 define( 'SCOPES', implode( ' ', [
	Google_Service_Calendar::CALENDAR,
	Google_Service_Calendar::CALENDAR_EVENTS,
] ) );
function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Test');
    $client->setScopes(SCOPES);
    $client->setAuthConfig(__DIR__.'/credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = 'token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            // Выводим на экран ссылку для открытия окна диалога авторизации
            echo '<a href="' .  $client->createAuthUrl() . '">Авторизация через Google</a>';
            //printf("Open the following link in your browser:\n%s\n", $authUrl);
            //print 'Enter verification code: ';
            $authCode = $_GET['code'];
            // Exchange authorization code for an access token.
            if(isset($authCode)){
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);
            }
            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        
    }
    return $client;
}

 
?>
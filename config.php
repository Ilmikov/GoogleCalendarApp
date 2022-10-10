<?php 
 
// Database configuration    
define('DB_HOST', 'localhost'); 
define('DB_USERNAME', 'root'); 
define('DB_PASSWORD', ''); 
define('DB_NAME', 'events'); 
 
// Google API configuration 
define('GOOGLE_CLIENT_ID', '59177154696-mcatcg549e1i7vfvag9ejvvggi685j5h.apps.googleusercontent.com'); 
define('GOOGLE_CLIENT_SECRET', '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'); 
define('GOOGLE_OAUTH_SCOPE', 'https://www.googleapis.com/auth/calendar'); 
define('REDIRECT_URI', 'http://localhost/GoogleCalendarApp/Calendar.php'); 
define('CALENDAR_ID', 'primary');
define('TIME_ZONE', 'Asia/Ekaterinburg');


// Start session 
if(!session_id()) session_start(); 
 
// Google OAuth URL 
$googleOauthURL = 'https://accounts.google.com/o/oauth2/auth?scope=' . urlencode(GOOGLE_OAUTH_SCOPE) . '&redirect_uri=' . REDIRECT_URI . '&response_type=code&client_id=' . GOOGLE_CLIENT_ID . '&access_type=online';

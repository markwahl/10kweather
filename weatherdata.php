<?php

// GET LOCATION

if ($_GET['address'] != "") {
	$address = urlencode($_GET['address']);
	$address_json = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=$address&key=AIzaSyDvFkjTCs4WSoKZwjY5qJ6sWRpNjBWqiLk");
	$address_obj = json_decode($address_json);
	$fullLocation = str_replace(', USA','',$address_obj->results[0]->formatted_address);
	$commaCount = substr_count($fullLocation, ', ');
	if ($commaCount > 1) { $fullLocation = explode(', ',$fullLocation,2); $fullLocation = $fullLocation[1]; }
	$lat = $address_obj->results[0]->geometry->location->lat;
	$lon = $address_obj->results[0]->geometry->location->lng;
	$currentLocLink = "<a href='index.php' class='currentLink'>Return to Current Location</a>";
} else {
	$ip = $_SERVER['REMOTE_ADDR'];
	if ($ip == '172.148.32.1') { $ip = '68.100.63.75'; }
	$geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$ip"));
	$city = $geo["geoplugin_city"];
	$state = $geo["geoplugin_region"];
	$fullLocation = $city.', '.$state;
	$lat = $geo["geoplugin_latitude"];
	$lon = $geo["geoplugin_longitude"];
}


// GET TIMEZONE

$json = file_get_contents("https://maps.googleapis.com/maps/api/timezone/json?location=$lat,$lon&timestamp=1458000000&key=AIzaSyAdmWkyqsySSXrCCvP-4_7MR8XWvC5qFoY");
$obj = json_decode($json);
$user_timezone = $obj->timeZoneId;
date_default_timezone_set($user_timezone);

// GET Weather.gov 7-Day DATA

ini_set('user_agent', '10KWeather/1.0.0 (Web Application, 10k.grid.dev)');
$wgov_json = file_get_contents("http://forecast.weather.gov/MapClick.php?lat=$lat&lon=$lon&unit=0&lg=english&FcstType=json");
$wgov_obj = json_decode($wgov_json);

// TODAY WEATHER

$current_temp = $wgov_obj->currentobservation->Temp;
$current_desc = $wgov_obj->currentobservation->Weather;
$current_desc = trim($current_desc);
$current_forecast = $wgov_obj->data->text[0];
$temp_label1 = $wgov_obj->time->tempLabel[0];
if ($temp_label1 == 'Low') {
	$temp_low = $wgov_obj->data->temperature[0];
	$temp_hi = $wgov_obj->data->temperature[1];
} else {
	$temp_hi = $wgov_obj->data->temperature[0];
	$temp_low = $wgov_obj->data->temperature[1];
}

// SET ICONS

$clear = array("Sunny","Mostly Sunny","Clear","Mostly Clear","Hot");
$wind = array("Blowing Dust","Blowing Sand","Windy");
$ice = array("Patchy Ice Crystals","Areas Ice Crystals","Ice Crystals","Ice Fog","Patchy Freezing Fog","Areas Freezing Fog","Freezing Fog","Freezing Spray","Patchy Frost","Areas Frost","Frost");
$partlysunny = array("Partly Sunny","Mostly Cloudy","Partly Cloudy","Increasing Clouds","Decreasing Clouds","Becoming Cloudy","Clearing","Gradual Clearing","Clearing Late","Becoming Sunny","A Few Clouds");
$fog = array("Dense Fog","Fog","Fog/Mist");
$partlyfog = array("Patchy Fog","Areas Fog","Patchy Haze","Areas Haze","Haze","Patchy Ice Fog","Areas Ice Fog","Patchy Ash","Areas Ash");
$smoke = array("Patchy Smoke","Areas Smoke","Smoke","Volcanic Ash");
$cloudy = array("Cloudy","Cold","Overcast");
$chancerain = array("Light Rain","Slight Chance Sleet","Chance Sleet","Slight Chance Rain Showers","Chance Rain Showers","Slight Chance Rain","Chance Rain","Slight Chance Drizzle","Chance Drizzle","Drizzle Likely","Drizzle","Slight Chance Rain/Snow","Chance Rain/Snow","Slight Chance Freezing Rain","Chance Freezing Rain","Slight Chance Freezing Drizzle","Chance Freezing Drizzle","Slight Chance Rain/Freezing Rain","Chance Rain/Freezing Rain","Slight Chance Rain/Sleet","Chance Rain/Sleet","Light Drizzle Fog/Mist","Light Drizzle","Slight Chance Showers");
$rain = array("Sleet Likely","Sleet","Rain Showers Likely","Rain Showers","Rain Likely","Rain","Heavy Rain","Rain/Snow Likely","Rain/Snow","Freezing Rain Likely","Freezing Rain","Freezing Drizzle Likely","Freezing Drizzle","Rain/Freezing Rain Likely","Rain/Freezing Rain","Rain/Sleet Likely","Rain/Sleet");
$blustery = array("Blustery","Breezy");
$chancesnow = array("Slight Chance Snow","Chance Snow","Slight Chance Wintry Mix","Chance Wintry Mix","Slight Chance Wintry Mix","Chance Wintry Mix","Slight Chance Snow/Sleet","Chance Snow/Sleet");
$flurries = array("Slight Chance Snow Showers","Chance Snow Showers","Snow Showers Likely","Snow Showers","Slight Chance Flurries","Chance Flurries","Flurries Likely","Flurries");
$snow = array("Blowing Snow","Snow Likely","Snow","Blizzard","Wintry Mix Likely","Wintry Mix","Wintry Mix Likely","Wintry Mix","Snow/Sleet Likely","Snow/Sleet");
$chancethunderstorm = array("Isolated Thunderstorms","Slight Chance Thunderstorms","Chance Thunderstorms","Thunderstorm in Vicinity Light Rain","Thunderstorm in Vicinity");
$thunderstorm = array("Thunderstorms Likely","Thunderstorms","Severe Tstms","Water Spouts");

if (in_array($current_desc, $clear)) { $current_icon = "clear"; }
elseif (in_array($current_desc, $wind)) { $current_icon = "wind"; }
elseif (in_array($current_desc, $ice)) { $current_icon = "ice"; }
elseif (in_array($current_desc, $partlysunny)) { $current_icon = "partlysunny"; }
elseif (in_array($current_desc, $partlyfog)) { $current_icon = "partlyfog"; }
elseif (in_array($current_desc, $smoke)) { $current_icon = "smoke"; }
elseif (in_array($current_desc, $fog)) { $current_icon = "fog"; }
elseif (in_array($current_desc, $partlyfog)) { $current_icon = "partlyfog"; }
elseif (in_array($current_desc, $cloudy)) { $current_icon = "cloudy"; }
elseif (in_array($current_desc, $chancerain)) { $current_icon = "chancerain"; }
elseif (in_array($current_desc, $rain)) { $current_icon = "rain"; }
elseif (in_array($current_desc, $blustery)) { $current_icon = "blustery"; }
elseif (in_array($current_desc, $chancesnow)) { $current_icon = "chancesnow"; }
elseif (in_array($current_desc, $flurries)) { $current_icon = "flurries"; }
elseif (in_array($current_desc, $snow)) { $current_icon = "snow"; }
elseif (in_array($current_desc, $chancethunderstorm)) { $current_icon = "chancethunderstorm"; }
elseif (in_array($current_desc, $thunderstorm)) { $current_icon = "thunderstorm"; }
else { $current_icon = "uncertain"; }

// TOMORROW WEATHER

if ($wgov_obj->time->tempLabel[0] == "Low") { 
	$tomorrow_temp_hi = $wgov_obj->data->temperature[1];
	$tomorrow_temp_low = $wgov_obj->data->temperature[2];
	$tomorrow_weather_short = $wgov_obj->data->weather[1];
	$tomorrow_weather = $wgov_obj->data->text[1];
} else {
	$tomorrow_temp_hi = $wgov_obj->data->temperature[2];
	$tomorrow_temp_low = $wgov_obj->data->temperature[3];
	$tomorrow_weather_short = $wgov_obj->data->weather[3];
	$tomorrow_weather = $wgov_obj->data->text[3];
}

if (in_array($tomorrow_weather_short, $clear)) { $tomorrow_icon = "clear"; }
elseif (in_array($tomorrow_weather_short, $wind)) { $tomorrow_icon = "wind"; }
elseif (in_array($tomorrow_weather_short, $ice)) { $tomorrow_icon = "ice"; }
elseif (in_array($tomorrow_weather_short, $partlysunny)) { $tomorrow_icon = "partlysunny"; }
elseif (in_array($tomorrow_weather_short, $partlyfog)) { $tomorrow_icon = "partlyfog"; }
elseif (in_array($tomorrow_weather_short, $smoke)) { $tomorrow_icon = "smoke"; }
elseif (in_array($tomorrow_weather_short, $fog)) { $tomorrow_icon = "fog"; }
elseif (in_array($tomorrow_weather_short, $partlyfog)) { $tomorrow_icon = "partlyfog"; }
elseif (in_array($tomorrow_weather_short, $cloudy)) { $tomorrow_icon = "cloudy"; }
elseif (in_array($tomorrow_weather_short, $chancerain)) { $tomorrow_icon = "chancerain"; }
elseif (in_array($tomorrow_weather_short, $rain)) { $tomorrow_icon = "rain"; }
elseif (in_array($tomorrow_weather_short, $blustery)) { $tomorrow_icon = "blustery"; }
elseif (in_array($tomorrow_weather_short, $chancesnow)) { $tomorrow_icon = "chancesnow"; }
elseif (in_array($tomorrow_weather_short, $flurries)) { $tomorrow_icon = "flurries"; }
elseif (in_array($tomorrow_weather_short, $snow)) { $tomorrow_icon = "snow"; }
elseif (in_array($tomorrow_weather_short, $chancethunderstorm)) { $tomorrow_icon = "chancethunderstorm"; }
elseif (in_array($tomorrow_weather_short, $thunderstorm)) { $tomorrow_icon = "thunderstorm"; }
else { $tomorrow_icon = "uncertain"; }


// MULTIDAY WEATHER

$multi_period = $wgov_obj->time->startPeriodName;
$multi_date = $wgov_obj->time->startValidTime;
$multi_tempLabel = $wgov_obj->time->tempLabel;
$multi_temperature = $wgov_obj->data->temperature;
$multi_pop = $wgov_obj->data->pop;
$multi_weather = $wgov_obj->data->weather;


// CREATE MULTIDAY TABLE

$a = 0;
foreach ($multi_period as &$period) { 
	$multiday .= '<div class="col-100 multiday '.$multi_tempLabel[$a].'"><div class="period">'; 
	$multiday .= $period;
	$multiday .=  ', ';
	$multiday .= date("M j", strtotime($multi_date[$a])); 
	$multiday .= '</div><div class="temp">'; 
	$multiday .= $multi_temperature[$a];
	$multiday .= '&deg;f</div><div class="pop">'; 
	if ($multi_pop[$a] == '') { $multiday .= '0'; } 
	else { $multiday .= $multi_pop[$a]; }
	$multiday .= '%</div><div class="weather">'; 
	$multiday .= $multi_weather[$a];
	$multiday .= '</div></div>';

	$a++;}

?>
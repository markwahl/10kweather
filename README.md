# 10kweather

## 10K Weather Website Project

This project displays current weather information and forecasts for a user's location and/or a submitted address value, and does so with a download size of less than 10Kb. The majority of the interactive elements are driven by CSS alone. Data is obtained from a number of open data API sources, parsed on the server-side in PHP, and rendered as HTML. Developed for the 10K Apart contest, September 2016.

## Installation

1. Copy the files into a web-accessible directory.
2. Access in a browser.

Note: This web app attempts to use the IP address of a given user's connection to identify location. When working in a local dev environment, the geolocator locator is liable not to work well with a local IP address. You can add a known public IP address as a fallback to use under these circumstances.

## Requirements

* PHP 5.6
* Access to the internet (none of the data will be available otherwise)

## Author

Mark Wahl

## Acknowledgements

A number of open source APIs are utilized in this project. These include:

* Google Maps Time Zone API (https://developers.google.com/maps/documentation/timezone/intro -- requires an API key)
* Google Maps Geolocation API (https://developers.google.com/maps/documentation/geolocation/intro -- requires an API key)
* National Weather Service Weather.gov API (http://www.weather.gov/ -- requires no key, but does require a user-agent to be set with the request)
* GeoPlugin IP Address Geolocation API (http://www.geoplugin.com/)

In addition, other resources include:

* The weather condition icons used in this project were created by Alessio Atzeni and sourced from Meteocons (http://www.alessioatzeni.com/meteocons/). 
* The CSS slider mechanism to move between weather views originated from an example from Divya Manian at http://nimbupani.com/making-pure-css3-demos-better.html.

NOTE: This product includes GeoLite data created by MaxMind, available from http://www.maxmind.com.

## License

This project is licensed under the MIT License - see the LICENSE.md file for details.

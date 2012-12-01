furnace-geo
===========

A Furnace module that provides simple integration of geolocation services

Overview
--------

This module provides drop-in support for utilizing the MaxMind and GeoNames databases to perform
geolocation on an IP address. MaxMind provides the primary geolocation information, with GeoNames
optionally providing higher-resolution information in the event that the address is located within
the United States. 

Usage
-----

Use of this module requires that you have a MaxMind API key (and GeoNames username if you want the
higher-resolution cross-street information for U.S.-based addresses). Simply put these keys in the
module config file. The module contains a built-in route '/info/`$lat`/`$lon`' that will return JSON
information pertaining to the provided lat/lon arguments. If you prefer to leverage the functionality
of the underlying class directly, here's an example of how to do it:

```
$geoip = new \Geo\classes\MaxMindGeoIP();
$data  = $geoip->locate($_SERVER['REMOTE_ADDR']);
```

After this, `$data` will contain an associative array of information returned from the services.
<?php
namespace Geo\classes;

use furnace\core\Config;

/**
 * Wraps the MaxMind GeoIP 'City' Webservice API with additional
 * information optionally gathered from the GeoNames web service.
 *
 * Usage:
 * 
 *  $geoip = new \Geo\classes\MaxMindGeoIP();
 *  $data  = $geoip->locate($_SERVER['REMOTE_ADDR']);
 *
 *  For extra information from GeoNames (specifically, the nearest
 *  street intersection for U.S. based IP addresses), also provide
 *  your GeoNames username in the module configuration file.
 *   
 * @author andrew
 *
 */
class MaxMindGeoIP {
    
    protected $MaxMindLicenseKey  = '';
    protected $GeoNamesLicenseKey = '';
    
    public function __construct() {
        $this->MaxMindLicenseKey  = Config::Get('Geo.service.maxmind.key');
        $this->GeoNamesLicenseKey = Config::Get('Geo.service.geonames.key');
    }
    
    /**
     * Geolocate an IP Address using the MaxMind and Geonames databases
     *
     * Attempts to discover as much geographical information as possible about 
     * the given IP address by combining results from the MaxMind and Geonames
     * databases. First, MaxMind is queried to obtain Lat/Lon and country 
     * information. If the IP address is determined to be within the United States,
     * a further query is issued to GeoNames to obtain information about the 
     * nearest cross streets. 
     *
     * @param    $ip  string  The IP address to geolocate
     * @returns  mixed        An associative array of geolocation information
     */
    public function locate( $ip ) {
        // Obtain the geolocation data for the IP Address
        $url  = "http://geoip3.maxmind.com/f?l={$this->MaxMindLicenseKey}&i={$ip}";
        $data = file_get_contents($url);
        $data = str_getcsv($data);
        
        // Build an associative array from the result
        $arr = array(
            'ip' => $ip,
            'country' => $data[0],
            'region'  => $data[1],
            'city'    => $data[2],
            'USZipCode' => $data[3],
            'latitude' => $data[4],
            'longitude'=> $data[5],
            'USMetroCode'=> $data[6],
            'USAreaCode' => $data[7],
            'isp' => $data[8],
            'organization' => $data[9]  
        );
        
        // Obtain additional detail, if available based on country code:
        if ($this->GeoNamesLicenseKey != '' && $arr['country'] == 'US') {
            
            // Obtain the nearest street intersection from GeoNames
            $url  = "http://api.geonames.org/findNearestIntersectionJSON"
	            . "?lat={$arr['latitude']}&lng={$arr['longitude']}&username={$this->GeoNamesLicenseKey}";
            $data = json_decode(file_get_contents($url));            
            $arr['ixStreet1'] = $data->intersection->street1;
            $arr['ixStreet2'] = $data->intersection->street2;
            $arr['ixDistance']= $data->intersection->distance;
        }

        // Return the array
        return $arr;
    }

    /**
     * Obtain GeoNames information on the given Lat/Lon pair
     *
     * Query the GeoNames database for information on landmarks and placenames
     * in the proximity of the provided latitude and longitude values. This 
     * function makes use of both the 'findNearby' and 'findNearestIntersection'
     * GeoNames APIs.
     * 
     * @param   $lat  string  The latitude value to use when searching
     * @param   $lon  string  The longitude value to use when searching
     * @returns mixed         An associative array of results from GeoNames
     *
    public function info( $lat, $lon ) {
        $url  = "http://api.geonames.org/findNearbyJSON"
	      . "?lat={$lat}&lng={$lon}&username={$this->GeoNamesLicenseKey}";
	$data = json_decode(file_get_contents($url)); 
	$url  = "http://api.geonames.org/findNearestIntersectionJSON"
              . "?lat={$lat}&lng={$lon}&username={$this->GeoNamesLicenseKey}";
	$ixdata = json_decode(file_get_contents($url));
        return array($data,$ixdata);
    }
}

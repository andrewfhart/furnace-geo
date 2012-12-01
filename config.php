<?php
/**
 ** Furnace - Geo
 ** =========================================================================
 **
 ** Simple integration of third-party geolocation services
 **
 ** @author Andrew F. Hart <andrew@datafluency.com>
 */

use furnace\core\Config;
use furnace\connections\Connections;
use furnace\routing\Router;

/* --------------------------------------------------------------------------
 * Third Party Web Service Settings
 * -------------------------------------------------------------------------*/
 Config::Set('Geo.service.maxmind.key'       , '---MaxMind API Key Here---');
 Config::Set('Geo.service.geonames.key'      , '---Geonames API Key Here---');


/* --------------------------------------------------------------------------
 * Module Settings (These only need editing in unusual circumstances)
 * -------------------------------------------------------------------------*/
 Config::Set('Geo.module.path'               , dirname(__FILE__));
 Config::Set('Geo.module.name'               , basename(dirname(__FILE__)));
 Config::Set('Geo.module.controllers.default','default'); 
 
/* --------------------------------------------------------------------------
 * Module URL Settings
 * -------------------------------------------------------------------------*/
 Config::Set('Geo.module.url'                , '/geo');

/* -------------------------------------------------------------------------
 * Database Settings
 * -------------------------------------------------------------------------*/
 Config::Set('Auth.database.conn'             , 'default');
 
/* -------------------------------------------------------------------------
 * Route Settings
 * -------------------------------------------------------------------------*/
  
  // The routes below apply the default Furnace routing behavior to this
  // module. You should only need to directly modify these routes if you
  // require customized routing behavior. In all other cases, simply
  // adjusting the configuration settings above will update
  // these routes correctly.
  //
  Router::ModuleConnect( Config::Get('Geo.module.url') . "/:controller/:handler"
  			 , array("module"     => Config::Get('Geo.module.name')));

  Router::ModuleConnect( Config::Get('Geo.module.url') . "/:handler"
  			 , array("module"     => Config::Get('Geo.module.name')
			       , "controller" => Config::Get('Geo.module.controllers.default')));

  // Required for module config files
  Router::ApplyModuleRoutes();

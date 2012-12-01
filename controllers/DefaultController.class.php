<?php
use furnace\core\Config;
use furnace\core\Furnace;
use furnace\controller\Controller;

use Geo\classes\MaxMindGeoIP;

class DefaultController extends Controller {

    public function index() {
        $this->set('ip',$_SERVER['REMOTE_ADDR']);
    }

    public function info($lat,$lon,$format = 'raw') {
        $g = new MaxMindGeoIP();
	$data = $g->info($lat,$lon,$format);

	if ($format == "text") {
            $dist  = $data[1]->intersection->distance;
            $st1   = $data[1]->intersection->street1;
            $st2   = $data[1]->intersection->street2;
            $place = $data[1]->intersection->placename;
            $zip   = $data[1]->intersection->postalcode;
            $txt = "Approximately {$dist} miles from the intersection of '{$st1}' and '{$st2}' ({$place}, {$zip})";
            echo $txt;
        } else {
           echo json_encode($data);
        }
        exit();
    }
}


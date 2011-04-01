<?php
/**
 * Location Test
 *
 * @package cruvee
 * @author Kyle Robinson Young <kyle at dontkry.com>
 */
App::import('Model', array('ConnectionManager', 'Cruvee.Location'));
App::import('Core', array('HttpSocket'));
App::import('Helper');
Mock::generate('HttpSocket');
class LocationTest extends CakeTestCase {

/**
 * name
 */
	public $name = 'Location';

/**
 * Model
 * @var object
 */
	public $Model = null;

/**
 * Ds
 * @var object
 */
	public $Ds = null;

/**
 * ds_name
 * @var string
 */
	public $ds_name = false;

/**
 * start
 */
	public function start() {
		if ($this->ds_name === false) {
			$this->ds_name = 'cruvee_temp';
			$this->Ds =& ConnectionManager::create($this->ds_name, array(
				'datasource' => 'cruvee.cruvee',
				'app_id' => 'test',
				'secret' => '1234',
				'cache' => false,
			));
		} else {
			$this->Ds =& ConnectionManager::getDataSource($this->ds_name);
		}
		$this->Model =& new $this->name(array(
			'alias' => $this->name,
			'ds' => $this->ds_name,
		));
	}

/**
 * testRead
 */
	public function testRead() {
		$fake = <<<END
{"page":1,"rpp":1,"total":7895,"nextUrl":"http://apiv1.cruvee.com/search/locations/all?rpp=1&page=2","results":[{
    "JSONLink": "http://apiv1.cruvee.com/locations/00014202.js",
    "artExhibit": null,
    "barrelTasting": null,
    "brands": [
        {
            "JSONLink": "http://apiv1.cruvee.com/brands/00014201.js",
            "directoryPageLink": "http://directory.cruvee.com/wineries/Abbott-Winery/00014201",
            "feedLink": "http://apiv1.cruvee.com/feeds/brands/00014201",
            "name": "Abbott Winery",
            "shortLink": "http://cruvee.com/b/s",
            "ynId": "urn:ynbid:00014201"
        }
    ],
    "busPolicy": null,
    "caves": null,
    "city": "Middletown",
    "coordinates": {
        "geoAltitude": null,
        "geoLatitude": 38.779718,
        "geoLongitude": -122.642917
    },
    "countryCode": "USA",
    "defaultLocation": true,
    "dogFriendly": null,
    "entityType": "WINERY_ADDRESS",
    "familyFriendly": null,
    "fax": null,
    "feedLink": "http://apiv1.cruvee.com/feeds/locations/00014202",
    "foursquareVenueURL": null,
    "gardens": null,
    "gowallaSpotURL": null,
    "groupSizeRequiringReservation": null,
    "historicSite": null,
    "hours": "",
    "lastUpdateDate": 1269154800000,
    "limoPolicy": null,
    "line1": "15951 Spruce Grove Rd.",
    "line2": null,
    "meetingSpace": null,
    "name": "Tasting",
    "phone": null,
    "phoneTollFree": null,
    "picnicPolicy": null,
    "postalCode": "95461",
    "region": null,
    "setting": null,
    "state": "CA",
    "tastingCharge": {
        "currencyAmount": null,
        "currencyCode": null
    },
    "tastingChargePolicy": null,
    "tastingPolicy": null,
    "tours": null,
    "type": "TASTING",
    "weddingSpace": null,
    "winery": {
        "JSONLink": "http://apiv1.cruvee.com/wineries/000142.js",
        "feedLink": "http://apiv1.cruvee.com/feeds/wineries/000142",
        "name": "Abbott Winery",
        "ynId": "urn:ynpid:000142"
    },
    "yelpBusinessURL": null,
    "ynId": "urn:ynlid:00014202"
}]}
END;
		$this->Ds->http =& new MockHttpSocket();
		$this->Ds->http->setReturnValue('get', $fake);
		$this->Ds->http->response['raw']['status-line'] = 'HTTP/1.1 200 OK';
		$expected = json_decode($fake, true);
		$expected = Set::extract('/'.$this->Model->alias, array($this->Model->alias => $expected['results']));

		// GET COUNT
		$count = $this->Model->find('count');
		$this->assertEqual($count, 7895);

		// FIND ALL
		$res = $this->Model->find('all', array(
			'limit' => 1,
		));
		$this->assertEqual($res, $expected);

		// FIND ONE
		$res = $this->Model->find('first');
		$this->assertEqual($res, current($expected));
	}

/**
 * end
 */
	public function end() {
		unset($this->Ds);
		unset($this->Model);
		Cache::clear(false, 'cruvee');
	}

}
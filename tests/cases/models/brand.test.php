<?php
/**
 * Brand Test
 *
 * @package cruvee
 * @author Kyle Robinson Young <kyle at dontkry.com>
 */
App::import('Model', array('ConnectionManager', 'Cruvee.Brand'));
App::import('Core', array('HttpSocket'));
App::import('Helper');
Mock::generate('HttpSocket');
class BrandTest extends CakeTestCase {

/**
 * name
 */
	public $name = 'Brand';

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
	public $ds_name = 'cruvee';

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
{"page":1,"rpp":1,"total":7243,"nextUrl":"http://apiv1.cruvee.com/search/brands/all?rpp=1&page=2","results":[{
    "JSONLink": "http://apiv1.cruvee.com/brands/00014201.js",
    "annualCaseProduction": null,
    "blogURL": null,
    "defaultBrand": true,
    "defaultRegion": null,
    "directoryPageLink": "http://directory.cruvee.com/wineries/Abbott-Winery/00014201",
    "entityType": "WINERY_BRAND",
    "facebookPageURL": null,
    "feedLink": "http://apiv1.cruvee.com/feeds/brands/00014201",
    "homeURL": "http://www.abbottwinery.com",
    "infoEmailAddress": "joabbott@abbottwinery.com",
    "lastUpdateDate": 1248135699000,
    "logoImageURL": null,
    "name": "Abbott Winery",
    "outOfBusiness": false,
    "outOfBusinessDate": null,
    "relatedBrands": [
    ],
    "shortLink": "http://cruvee.com/b/s",
    "storefrontURL": null,
    "twitterHandle": null,
    "wineryJSONLink": "http://apiv1.cruvee.com/wineries/000142.js",
    "yearEstablished": null,
    "ynId": "urn:ynbid:00014201"
}]}
END;

		$this->Ds->http =& new MockHttpSocket();
		$this->Ds->http->setReturnValue('get', $fake);
		$this->Ds->http->response['status']['code'] = 200;
		$expected = Set::reverse(json_decode($fake));
		$expected = Set::extract('/'.$this->Model->alias, array($this->Model->alias => $expected['results']));

		// GET COUNT
		$count = $this->Model->find('count');
		$this->assertEqual($count, 7243);

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
 * testByVariety
 */
	public function testByVariety() {
		$fake = <<<END
{"page":1,"rpp":10,"total":1,"results":[{
    "JSONLink": "http://apiv1.cruvee.com/brands/00861001.js",
    "annualCaseProduction": 60000,
    "blogURL": null,
    "defaultBrand": true,
    "defaultRegion": {
        "JSONLink": "http://apiv1.cruvee.com/regions/8400048.js",
        "feedLink": "http://apiv1.cruvee.com/feeds/regions/8400048",
        "name": "Livermore Valley",
        "ynId": "urn:ynrid:8400048"
    },
    "directoryPageLink": "http://directory.cruvee.com/wineries/Tamas-Estates/00861001",
    "entityType": "WINERY_BRAND",
    "facebookPageURL": "http://www.facebook.com/pages/TAMAS-Estates/124749287544037",
    "feedLink": "http://apiv1.cruvee.com/feeds/brands/00861001",
    "homeURL": "http://www.tamasestates.com/",
    "infoEmailAddress": "tamasestates@tamasestates.com",
    "lastUpdateDate": 1299529599000,
    "logoImageURL": null,
    "name": "Tam&aacute;s Estates",
    "outOfBusiness": false,
    "outOfBusinessDate": null,
    "relatedBrands": [
    ],
    "shortLink": "http://cruvee.com/b/6qd",
    "storefrontURL": "http://shop.tamasestates.com/",
    "twitterHandle": "tamasestates",
    "wineryJSONLink": "http://apiv1.cruvee.com/wineries/008610.js",
    "yearEstablished": 1984,
    "ynId": "urn:ynbid:00861001"
}]}
END;
		$this->Ds->http =& new MockHttpSocket();
		$this->Ds->http->setReturnValue('get', $fake);
		$this->Ds->http->response['status']['code'] = 200;
		$expected = Set::reverse(json_decode($fake));
		$expected = Set::extract('/'.$this->Model->alias, array($this->Model->alias => $expected['results']));

		$res = $this->Model->byVariety('red');
		$this->assertEqual($res, $expected);
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
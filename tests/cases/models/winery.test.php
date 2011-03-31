<?php
/**
 * Winery Test
 *
 * @package cruvee
 * @author Kyle Robinson Young <kyle at dontkry.com>
 */
App::import('Model', array('ConnectionManager', 'Cruvee.Winery'));
App::import('Core', array('HttpSocket'));
App::import('Helper');
Mock::generate('HttpSocket');
class WineryTest extends CakeTestCase {

/**
 * name
 */
	public $name = 'Winery';

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
		$this->Ds =& ConnectionManager::getDataSource($this->ds_name);
		if (!$this->Ds) {
			$this->Ds =& ConnectionManager::create($this->ds_name, array(
				'datasource' => 'cruvee.cruvee',
				'app_id' => 'test',
				'secret' => '1234',
				'cache' => false,
			));
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
		try {

			// PREPARE FAKE RESPONSE
			$fake = <<<END
{"page":1,"rpp":1,"total":6948,"nextUrl":"http://apiv1.cruvee.com/search/wineries/all?rpp=1&page=2","results":[{
    "JSONLink": "http://apiv1.cruvee.com/wineries/000142.js",
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
    "feedLink": "http://apiv1.cruvee.com/feeds/wineries/000142",
    "lastUpdateDate": 1248135699000,
    "locations": [
        {
            "JSONLink": "http://apiv1.cruvee.com/locations/00014202.js",
            "feedLink": "http://apiv1.cruvee.com/feeds/locations/00014202",
            "name": "Tasting",
            "type": "TASTING",
            "ynId": "urn:ynlid:00014202"
        }
    ],
    "name": "Abbott Winery",
    "ynId": "urn:ynpid:000142"
}]}
END;
			$this->Ds->http =& new MockHttpSocket();
			$this->Ds->http->setReturnValue('get', $fake);
			$this->Ds->http->response['raw']['status-line'] = 'HTTP/1.1 200 OK';
			$expected = Set::reverse(json_decode($fake));
			$expected = Set::extract('/'.$this->Model->alias, array($this->Model->alias => $expected['results']));

			// GET COUNT
			$count = $this->Model->find('count');
			$this->assertEqual($count, 6948);

			// FIND ALL
			$res = $this->Model->find('all', array(
				'limit' => 1,
			));
			$this->assertEqual($res, $expected);

		} catch (Exception $e) {
			//debug($e->getMessage());
		}
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
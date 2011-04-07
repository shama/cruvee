<?php
/**
 * Region Test
 *
 * @package cruvee
 * @author Kyle Robinson Young <kyle at dontkry.com>
 */
App::import('Model', array('ConnectionManager', 'Cruvee.Region'));
App::import('Core', array('HttpSocket'));
App::import('Helper');
Mock::generate('HttpSocket');
class RegionTest extends CakeTestCase {

/**
 * name
 */
	public $name = 'Region';

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
{"page":1,"rpp":1,"total":1544,"nextUrl":"http://apiv1.cruvee.com/search/regions/all?rpp=1&page=2","results":[{
    "JSONLink": "http://apiv1.cruvee.com/regions/0360001.js",
    "children": [
    ],
    "designation": null,
    "designationDate": null,
    "entityType": "REGION",
    "feedLink": "http://apiv1.cruvee.com/feeds/regions/0360001",
    "lastUpdateDate": 1267430400000,
    "lineage": [
        {
            "JSONLink": "http://apiv1.cruvee.com/regions/0360105.js",
            "feedLink": "http://apiv1.cruvee.com/feeds/regions/0360105",
            "name": "Australia",
            "ynId": "urn:ynrid:0360105"
        }
    ],
    "name": "Australian Capital Territory",
    "overlapping": [
    ],
    "parent": {
        "JSONLink": "http://apiv1.cruvee.com/regions/0360105.js",
        "feedLink": "http://apiv1.cruvee.com/feeds/regions/0360105",
        "name": "Australia",
        "ynId": "urn:ynrid:0360105"
    },
    "ynId": "urn:ynrid:0360001"
}]}
END;
		$this->Ds->http =& new MockHttpSocket();
		$this->Ds->http->setReturnValue('get', $fake);
		$this->Ds->http->response['status']['code'] = 200;
		$expected = json_decode($fake, true);
		$expected = Set::extract('/'.$this->Model->alias, array($this->Model->alias => $expected['results']));

		// GET COUNT
		$count = $this->Model->find('count');
		$this->assertEqual($count, 1544);

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
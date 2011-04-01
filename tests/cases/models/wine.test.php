<?php
/**
 * Wine Test
 *
 * @package cruvee
 * @author Kyle Robinson Young <kyle at dontkry.com>
 */
App::import('Model', array('ConnectionManager', 'Cruvee.Wine'));
App::import('Core', array('HttpSocket'));
App::import('Helper');
Mock::generate('HttpSocket');
class WineTest extends CakeTestCase {

/**
 * name
 */
	public $name = 'Wine';

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
 * Set to live DS name for live testing.
 * @var string
 */
	public $ds_name = false; // cruvee

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
		try {
			$fake = <<<END
{"page":1,"rpp":1,"total":10782,"nextUrl":"http://apiv1.cruvee.com/search/wines/all?rpp=1&page=2","results":[{
    "JSONLink": "http://apiv1.cruvee.com/wines/124000200077801200600100.js",
    "brand": {
        "JSONLink": "http://apiv1.cruvee.com/brands/00077801.js",
        "directoryPageLink": "http://directory.cruvee.com/wineries/Black-Cloud/00077801",
        "feedLink": "http://apiv1.cruvee.com/feeds/brands/00077801",
        "name": "Black Cloud",
        "shortLink": "http://cruvee.com/b/44b",
        "ynId": "urn:ynbid:00077801"
    },
    "declaredVariety": {
        "JSONLink": "http://apiv1.cruvee.com/varieties/00114.js",
        "name": "Pinot Noir",
        "ynId": "urn:ynvid:00114"
    },
    "declaredVineyard": null,
    "directoryPageLink": "http://directory.cruvee.com/wines/2006-Black-Cloud-Pinot-Noir-Black-Cloud-Okanagan-Valley/124000200077801200600100",
    "entityType": "WINE",
    "labelImageFrontURL": "http://assets.cruvee.com/wines/482e70e96bf74ef2a76d52dd6f00df9f_t.jpg",
    "lastUpdateDate": 1277510409000,
    "legalDesignation": null,
    "marketingDesignation": "Black Cloud",
    "name": "2006 Black Cloud Pinot Noir Black Cloud Okanagan Valley",
    "products": [
        {
            "JSONLink": "http://apiv1.cruvee.com/products/124000200077801200600100036.js",
            "buyURL": null,
            "closure": "SYNTHETIC",
            "containerSize": "750mL",
            "containerType": "BOTTLE_750ML",
            "detailURL": null,
            "directoryPageLink": "http://directory.cruvee.com/wines/2006-Black-Cloud-Pinot-Noir-Black-Cloud-Okanagan-Valley/124000200077801200600100",
            "entityType": "WINE_PRODUCT",
            "labelImageFrontURL": "http://assets.cruvee.com/wines/482e70e96bf74ef2a76d52dd6f00df9f_t.jpg",
            "name": "2006 Black Cloud Pinot Noir Black Cloud Okanagan Valley, 750mL",
            "soldOut": false,
            "suggestedRetailPrice":             {
                "currencyAmount": 19.99,
                "currencyCode": "CAD"
            },
            "wineJSONLink": "http://apiv1.cruvee.com/wines/124000200077801200600100.js",
            "ynId": "urn:ynid:124000200077801200600100036"
        }
    ],
    "qualityDesignation": null,
    "region": {
        "JSONLink": "http://apiv1.cruvee.com/regions/1240002.js",
        "feedLink": "http://apiv1.cruvee.com/feeds/regions/1240002",
        "lineage": [
            {
                "JSONLink": "http://apiv1.cruvee.com/regions/1240012.js",
                "feedLink": "http://apiv1.cruvee.com/feeds/regions/1240012",
                "name": "Canada",
                "ynId": "urn:ynrid:1240012"
            },
            {
                "JSONLink": "http://apiv1.cruvee.com/regions/1240005.js",
                "feedLink": "http://apiv1.cruvee.com/feeds/regions/1240005",
                "name": "British Columbia",
                "ynId": "urn:ynrid:1240005"
            }
        ],
        "name": "Okanagan Valley",
        "ynId": "urn:ynrid:1240002"
    },
    "shortLink": "http://cruvee.com/w/2",
    "vintage": "2006",
    "wineType": "RED",
    "ynId": "urn:ynwid:124000200077801200600100"
}]}
END;
			$this->Ds->http =& new MockHttpSocket();
			$this->Ds->http->setReturnValue('get', $fake);
			$this->Ds->http->response['status']['code'] = 200;
			$expected = json_decode($fake, true);
			$expected = Set::extract('/'.$this->Model->alias, array($this->Model->alias => $expected['results']));

			// GET COUNT
			$count = $this->Model->find('count');
			$this->assertEqual($count, 10782);

			// FIND ALL
			$res = $this->Model->find('all', array(
				'limit' => 1,
			));
			$this->assertEqual($res, $expected);

			// FIND ONE
			$res = $this->Model->find('first');
			$this->assertEqual($res, current($expected));

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
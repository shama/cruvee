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
	public $ds_name = 'cruvee'; // cruvee_temp

/**
 * start
 */
	public function start() {
		/*$this->Ds =& ConnectionManager::create($this->ds_name, array(
			'datasource' => 'cruvee.cruvee',
			'app_id' => 'test',
			'secrect' => '1234',
		));*/
		/*if ($this->Ds == null) {
			$this->Ds =& ConnectionManager::getDataSource($this->ds_name);
		}*/
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

			// GET COUNT
			//$count = $this->Model->find('count');
			//$this->assertEqual($count, 6948);

			// FIND ALL
			$res = $this->Model->find('all');
			debug($res);

		} catch (Exception $e) {
			debug($e->getMessage());
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
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

			// FIND ALL
			//$res = $this->Model->find('all');

			// FIND ONE
			//$res = $this->Model->find('first');

			// FIND BY NAME
			$res = $this->Model->search('Black Cloud');

			debug($res);

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
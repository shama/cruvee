<?php
/**
 * Social Test
 *
 * @package cruvee
 * @author Kyle Robinson Young <kyle at dontkry.com>
 */
App::import('Model', array('ConnectionManager', 'Cruvee.Social'));
App::import('Core', array('HttpSocket'));
App::import('Helper');
Mock::generate('HttpSocket');
class SocialTest extends CakeTestCase {

/**
 * name
 */
	public $name = 'Social';

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
	public $ds_name = 'cruvee'; // cruvee

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

			$res = $this->Model->search('Robledo');
			//debug($res);

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
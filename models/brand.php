<?php
/**
 * Brand Model
 *
 * @package cruvee
 * @author Kyle Robinson Young <kyle at dontkry.com>
 */
class Brand extends CruveeAppModel {
	public $name = 'Brand';

/**
 * wineTypes
 * @var array
 */
	public $wineTypes = array(
		'red', 'white', 'rose',
		'sparkling_white', 'sparkling_red',
		'port', 'dessert', 'fruit_berry',
		'mead', 'other'
	);

/**
 * byVariety
 * @param string $wineType
 */
	function byVariety($wineType=null) {
		$this->method = 'brands/byvariety';
		return $this->search($wineType);
	}
}
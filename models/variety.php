<?php
/**
 * Variety Model
 *
 * @package cruvee
 * @author Kyle Robinson Young <kyle at dontkry.com>
 */
class Variety extends CruveeAppModel {
	public $name = 'Variety';

/**
 * commonalities
 * @var array
 */
	public $commonalities = array(
		'common', 'uncommon', 'very_common', 'most_common',
	);
}
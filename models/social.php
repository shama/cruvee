<?php
class Social extends CruveeAppModel {
	public $name = 'Social';
	public $method = 'social';

/**
 * totals
 * @var array
 */
	public $totals = array(
		'blogMentions' => 0,
		'microBlogMentions' => 0,
		'forumMentions' => 0,
		'tastingNoteMentions' => 0,
		'mediaMentions' => 0,
		'checkIns' => 0,
	);

/**
 * afterFind
 * Set totals of previous query.
 *
 * @param array $results
 * @param boolean $primary
 */
	function afterFind($results=null, $primary=null) {
		parent::afterFind($results, $primary);
		$res = $this->response();
		$res = json_decode($res['body'], true);
		$this->totals = array_intersect_key($res, $this->totals);
	}
}
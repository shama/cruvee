<?php
/**
 * Cruvee Source
 * DataSource for the Cruvee API
 *
 * Copyright (C) 2011 Kyle Robinson Young
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 *
 * @author Kyle Robinson Young <kyle at dontkry.com>
 * @copyright 2011 Kyle Robinson Young
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @version 0.1
 *
 */
class CruveeSource extends DataSource {

/**
 * description
 * @var string
 */
	public $description = 'Cruvee DataSource';

/**
 * config
 * @var array
 */
	public $config = array(
		'app_id' => '',
		'secret' => '',
		'api_url' => 'http://apiv1.cruvee.com',
		/**
		 * cache
		 * true = use plugin cache
		 * false = disable cache
		 * 'cache-name' = cache config to use
		 */
		'cache' => true,
	);

/**
 * http
 * @var object
 */
	public $http = null;

/**
 * url
 * @var string
 */
	public $url = null;

/**
 * __construct
 * @param array $config
 */
	public function __construct($config) {
		$this->init($config);
		parent::__construct($config);
	}

/**
 * init
 * Inits the socket, url and cache.
 *
 * @param array $config
 * @return bool
 */
	public function init($config=null) {
		$this->config = array_merge($this->config, (array)$config);
		if (!class_exists('HttpSocket')) {
			App::import('Core', array('HttpSocket'));
		}
		$this->http = new HttpSocket();
		$this->url = $this->config['api_url'];
		if ($this->config['cache'] === true) {
			Cache::config('cruvee', array('engine'=> 'File', 'prefix' => 'cruvee_'));
			$this->config['cache'] = 'cruvee';
		}
		return true;
	}

/**
 * read
 * Handles list and get sub methods.
 *
 * @access public
 * @param object $model
 * @param array $data
 * @return array
 *
 * TODO: Implement order by for varieties API.
 */
	public function read(&$model, $data=array()) {
		$method = (isset($model->method)) ? $model->method : Inflector::pluralize(Inflector::underscore($model->alias));
		$uri = '/search/'.$method;
		$options = array_merge(array(
			'fmt' => 'json',
		), (array)$data['conditions']);
		if (empty($options['q'])) {
			$uri .= '/all';
		}
		if (isset($data['limit'])) {
			$options['rpp'] = $data['limit'];
		}
		if (isset($data['page'])) {
			$options['page'] = $data['page'];
		}
		$params = '?';
		foreach ($options as $key => $val) {
			if (is_array($val)) {
				$val = implode(',', $val);
			}
			$params .= $key.'='.$val.'&';
		}
		$params = substr($params, 0, -1);
		$hash = hash('md4', $this->url.$uri.$params);
		if (($res = Cache::read($hash, $this->config['cache'])) === false || $this->config['cache'] === false) {
			$res = $this->http->get($this->url.$uri, $params, $this->__getAuthArray($uri));
			debug($res);
			if (strpos($this->http->response['raw']['status-line'], '200') === false) {
				throw new Exception(__d('cruvee', $this->http->response['raw']['status-line'], true));
				return array();
			}
			$res = Set::reverse(json_decode($res));
			if ($this->config['cache'] !== false) {
				if (isset($model->cache)) {
					Cache::set($model->cache);
				}
				Cache::write($hash, $res, $this->config['cache']);
			}
		}
		if ($res === false) {
			return array();
		}
		if ($data['fields'] == 'count') {
			$res = array(array(array('count' => $res['total'])));
		} else {
			if ($method == 'social') {
				$res = $res['items'];
			} else {
				$res = $res['results'];
			}
			$res = Set::extract('/'.$model->alias, array($model->alias => $res));
		}
		return $res;
	}

/**
 * query
 * Give outside access to things in datasource.
 *
 * @param string $query
 * @param array $data
 * @param object $model
 * @return mixed
 */
	public function query($query=null, $data=null, &$model=null) {
		if (strpos(strtolower($query), 'findby') === 0) {
			$field = Inflector::underscore(preg_replace('/^findBy/i', '', $query));
			if ($field == 'id') {
				$field = $model->primaryKey;
			}
			return $model->find('first', array(
				'conditions' => array(
					$field => current($data),
				),
			));
		}
		if (strtolower($query) == 'search') {
			return $model->find('all', array(
				'conditions' => array('q' => current($data)),
			));
		}
		if (strtolower($query) == 'request') {
			return $this->http->request;
		}
		if (strtolower($query) == 'response') {
			return $this->http->response;
		}
		throw new Exception(__d('cruvee', 'Sorry, that find method is not supported.', true));
	}

/**
 * listSources
 * @return boolean
 */
	public function listSources() {
		return false;
	}

/**
 * describe
 *
 * @param object $model
 * @return array
 */
	public function describe(&$model) {
		if (isset($model->schema)) {
			return $model->schema;
		} else {
			return array('id' => array());
		}
	}

/**
* calculate
* Just return $func to give read() the field 'count'
*
* @param Model $model
* @param mixed $func
* @param array $params
* @return array
* @access public
*/
	public function calculate(&$model, $func, $params=array()) {
		return $func;
	}

/**
 * __getAuthArray
 * @return array
 */
	private function __getAuthArray($uri=null) {
		$time = time()*1000;
		$sig = $this->config['app_id'] . "\n";
		$sig .= 'GET' . "\n";
		$sig .= $this->config['secret'] . "\n";
		$sig .= $time . "\n";
		$sig .= $uri . "\n";
		$sig = md5(strtolower($sig));
		return array(
			'header' => array(
				'Authorization' => 'Cruvee appId="'.$this->config['app_id'].'", sig="'.$sig.'", timestamp="'.$time.'", uri="'.$uri.'"',
			),
			'auth' => false, // NO BASIC AUTH!
		);
	}
}
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
		try {
			$fake = <<<END
{
   "id":"http://apiv1.cruvee.com/search/social?fmt=json&q=Robledo&page=1",
   "title":"Cruvee Social Media Matches",
   "page":1,
   "rpp":10,
   "total":615,
   "blogMentions":74,
   "microBlogMentions":481,
   "forumMentions":38,
   "tastingNoteMentions":0,
   "mediaMentions":22,
   "checkIns":0,
   "items":[
      {
         "id":"cruvee-evt-9308059",
         "postedTime":"2010-12-18T16:29:33+0000",
         "title":null,
         "category":"MEDIA",
         "verb":[
            "http://activitystrea.ms/schema/1.0/post"
         ],
         "content":"This Week: Robledo Family Winery...",
         "actor":{
            "id":"cruvee-profile-null",
            "name":"kqedondemand",
            "url":"http://gdata.youtube.com/feeds/base/users/kqedondemand"
         },
         "serviceProvider":{
            "permalinkUrl":"http://www.youtube.com",
            "name":"youtube",
            "type":"youtube",
            "icon":"http://cruvee.com/images/social/youtube.png"
         }
      },
      {
         "id":"cruvee-evt-13053965",
         "postedTime":"2011-03-31T19:02:50+0000",
         "title":null,
         "category":"MICROBLOG",
         "verb":[
            "http://activitystrea.ms/schema/1.0/post"
         ],
         "content":"Looke for Robledo Family Winery at the SF Vintners' Market on April 9th & 10th.  http://www.sfvintnersmarket.com/about.html...",
         "actor":{
            "id":"cruvee-profile-null",
            "name":"RobledoWinery",
            "url":"http://twitter.com/RobledoWinery",
            "icon":"http://a1.twimg.com/profile_images/293154329/Picture_365_1__normal.jpg"
         },
         "serviceProvider":{
            "permalinkUrl":"http://twitter.com",
            "name":"twitter",
            "type":"twitter",
            "icon":"http://cruvee.com/images/social/twitter.png"
         }
      },
      {
         "id":"cruvee-evt-7114436",
         "postedTime":"2010-10-18T20:17:02+0000",
         "title":null,
         "category":"MICROBLOG",
         "verb":[
            "http://activitystrea.ms/schema/1.0/post"
         ],
         "content":"Article featuring Robledo Family Winery...",
         "actor":{
            "id":"cruvee-profile-null",
            "name":"Sonoma Valley Grapes and Wine",
            "url":"http://www.facebook.com/profile.php?id=86928173280",
            "icon":"http://graph.facebook.com/86928173280/picture"
         },
         "serviceProvider":{
            "permalinkUrl":"http://www.facebook.com",
            "name":"Facebook",
            "type":"facebook",
            "icon":"http://cruvee.com/images/social/facebook.png"
         }
      },
      {
         "id":"cruvee-evt-2607220",
         "postedTime":"2010-03-01T20:13:17+0000",
         "title":null,
         "category":"MICROBLOG",
         "verb":[
            "http://activitystrea.ms/schema/1.0/post"
         ],
         "content":"Foggy morning in Sonoma at Robledo Winery!!",
         "actor":{
            "id":"cruvee-profile-null",
            "name":"mrupert84",
            "url":"http://twitter.com/mrupert84",
            "icon":"http://s3.amazonaws.com/twitter_production/profile_images/288893276/dancermatt_normal.jpg"
         },
         "serviceProvider":{
            "permalinkUrl":"http://twitter.com",
            "name":"twitter",
            "type":"twitter",
            "icon":"http://cruvee.com/images/social/twitter.png"
         }
      },
      {
         "id":"cruvee-evt-437731",
         "postedTime":"2009-04-20T19:37:14+0000",
         "title":null,
         "category":"MICROBLOG",
         "verb":[
            "http://activitystrea.ms/schema/1.0/post"
         ],
         "content":"ROBLEDO FAMILY WINERY. YA HERTS...",
         "actor":{
            "id":"cruvee-profile-null",
            "name":"Flacobay",
            "url":"http://twitter.com/Flacobay",
            "icon":"http://s3.amazonaws.com/twitter_production/profile_images/98800404/christo_normal.jpg"
         },
         "serviceProvider":{
            "permalinkUrl":"http://twitter.com",
            "name":"twitter",
            "type":"twitter",
            "icon":"http://cruvee.com/images/social/twitter.png"
         }
      },
      {
         "id":"cruvee-evt-12825717",
         "postedTime":"2011-03-26T14:14:03+0000",
         "title":null,
         "category":"MICROBLOG",
         "verb":[
            "http://activitystrea.ms/schema/1.0/post"
         ],
         "content":"Robledo Family Winery (Carneros region) is the first winery established in US by a Mexican migrant vineyard worker, Reynaldo Robledo.",
         "actor":{
            "id":"cruvee-profile-null",
            "name":"cheers2winecom",
            "url":"http://twitter.com/cheers2winecom",
            "icon":"http://a1.twimg.com/profile_images/652990509/wine_toast_normal.jpg"
         },
         "serviceProvider":{
            "permalinkUrl":"http://twitter.com",
            "name":"twitter",
            "type":"twitter",
            "icon":"http://cruvee.com/images/social/twitter.png"
         }
      },
      {
         "id":"cruvee-evt-12531043",
         "postedTime":"2011-03-19T20:11:21+0000",
         "title":null,
         "category":"MEDIA",
         "verb":[
            "http://activitystrea.ms/schema/1.0/post"
         ],
         "content":"Real American Stories Robledo Family Winery...",
         "actor":{
            "id":"cruvee-profile-null",
            "name":"joelsecondlanguage",
            "url":"http://gdata.youtube.com/feeds/base/users/joelsecondlanguage"
         },
         "serviceProvider":{
            "permalinkUrl":"http://www.youtube.com",
            "name":"youtube",
            "type":"youtube",
            "icon":"http://cruvee.com/images/social/youtube.png"
         }
      },
      {
         "id":"cruvee-evt-1374294",
         "postedTime":"2009-07-23T16:39:26+0000",
         "title":null,
         "category":"MICROBLOG",
         "verb":[
            "http://activitystrea.ms/schema/1.0/post"
         ],
         "content":"...http://twitpic.com/bc78p - Jayme Rubke, Francisco Robledo and Everardo Robledo of Robledo Family Winery aboard the Cuauhtémoc Navel Ship ...",
         "actor":{
            "id":"cruvee-profile-null",
            "name":"RobledoWinery",
            "url":"http://twitter.com/RobledoWinery",
            "icon":"http://s3.amazonaws.com/twitter_production/profile_images/291600170/pic_2008_20family_20santa_20cruz_20041_1__normal.jpg"
         },
         "serviceProvider":{
            "permalinkUrl":"http://twitter.com",
            "name":"twitter",
            "type":"twitter",
            "icon":"http://cruvee.com/images/social/twitter.png"
         }
      },
      {
         "id":"cruvee-evt-2659882",
         "postedTime":"2010-03-10T01:00:49+0000",
         "title":null,
         "category":"MICROBLOG",
         "verb":[
            "http://activitystrea.ms/schema/1.0/post"
         ],
         "content":"Relaxing with a glass of Robledo Sauv Blanc...",
         "actor":{
            "id":"cruvee-profile-null",
            "name":"familyfoodie",
            "url":"http://twitter.com/familyfoodie",
            "icon":"http://a1.twimg.com/profile_images/654405538/chef_hat_normal.jpg"
         },
         "serviceProvider":{
            "permalinkUrl":"http://twitter.com",
            "name":"twitter",
            "type":"twitter",
            "icon":"http://cruvee.com/images/social/twitter.png"
         }
      },
      {
         "id":"cruvee-evt-1925253",
         "postedTime":"2009-10-31T16:59:59+0000",
         "title":null,
         "category":"MICROBLOG",
         "verb":[
            "http://activitystrea.ms/schema/1.0/post"
         ],
         "content":"Happy Halloween from Robledo Family Winery!!!!!",
         "actor":{
            "id":"cruvee-profile-null",
            "name":"RobledoWinery",
            "url":"http://twitter.com/RobledoWinery",
            "icon":"http://s3.amazonaws.com/twitter_production/profile_images/291600170/pic_2008_20family_20santa_20cruz_20041_1__normal.jpg"
         },
         "serviceProvider":{
            "permalinkUrl":"http://twitter.com",
            "name":"twitter",
            "type":"twitter",
            "icon":"http://cruvee.com/images/social/twitter.png"
         }
      }
   ]
}
END;

			$this->Ds->http =& new MockHttpSocket();
			$this->Ds->http->setReturnValue('get', $fake);
			$this->Ds->http->response['raw']['status-line'] = 'HTTP/1.1 200 OK';
			$expected = json_decode($fake, true);
			$expected = Set::extract('/'.$this->Model->alias, array($this->Model->alias => $expected['items']));

			// TODO: JSON ISNT ENCODING WITH ABOVE RESULTS, FIND OUT WHY
			$res = $this->Model->search('Robledo');
			$this->assertEqual($res, $expected); // <- WILL FAIL

		} catch (Exception $e) {
			debug($e->getMessage());
			debug(json_last_error());
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
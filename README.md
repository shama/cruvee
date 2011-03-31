# CruveeCake: A Cruvee API Wrapper for CakePHP

Cruvee is an open-source approach to quality wine data provided by a flexible and powerful family of APIs.

[http://developer.cruvee.com/](http://developer.cruvee.com/)

## Requirements

PHP5+, CakePHP 1.3+, Cruvee API Credentials

## Installing

Extract the contents of this repo into *app/plugins/cruvee/* or use [git clone](http://www.kernel.org/pub/software/scm/git/docs/git-clone.html) or [git submodule](http://www.kernel.org/pub/software/scm/git/docs/git-submodule.html) in your plugins folder:

	git clone git://github.com/shama/cruvee.git cruvee

Copy the following lines into *app/config/database.php* and add your app_id and secret:

	var $cruvee = array(
		'datasource' => 'cruvee.cruvee',
		'app_id' => 'APP-ID-HERE',
		'secret' => 'SECRET-HERE',
	);

## Usage

#### CHOOSING A MODEL

In your controller, use the API just like you would any other model. Here we are going to find wineries:

    var $uses = array('Cruvee.Winery');

#### FIND ALL

To grab all records use (Cruvee limits results to 50 per page):

    $wineries = $this->Winery->find('all');

#### FIND ONE

    $winery = $this->Winery->find('first');

#### SEARCHING

    $winery = $this->Winery->search('Portalupi');

OR

    $winery = $this->Winery->find('all', array(
        'conditions' => array(
            'q' => 'Portalupi',
        ),
    ));

#### FIND AND LIMIT RESULTS (YES IT PAGINATES!)

    $winery = $this->Winery->find('all', array(
        'limit' => 15, // MAX IS 50
        'page' => 2,
    ));

#### ERROR HANDLING

It is highly recommended that you put a try catch statement around your code. Any errors returned from the API or this plugin will throw an error for you to deal with. Here is an example:

    try {
        $wineries = $this->Winery->find('all');
    } catch (Exception $e) {
        debug($e->getMessage());
    }

#### EXTENDING THE MODELS

Please avoid modifying the plugin source code (unless you are contributing!). Here is how to extend and modify the models:

Create your own model in *app/models/* lets call it *my_winery.php*

    App::import('Model', 'Cruvee.Winery');
    class MyWinery extends Winery {
        public $name = 'MyWinery';
    }

Now you are free to extend the model however you like without editing the plugin!

#### OTHER METHODS

Take a look into each *app/plugins/cruvee/models/* to see other methods this plugin supports. For examples look in *app/plugins/cruvee/tests/cases/models/*.

## Issues

Please report any issues you have with the plugin to the [issue tracker](http://github.com/shama/cruvee/issues) on github.

## License

CruveeCake is offered under an [MIT license](http://www.opensource.org/licenses/mit-license.php).

## Copyright

2011 Kyle Robinson Young, [dontkry.com](http://dontkry.com) in association with Everflight, [everflight.com](http://everflight.com}
If you found this release useful please let the author know! Follow on [Twitter](http://twitter.com/kyletyoung)

## Roadmap and Known Issues

* Finish building/testing brand, location, region and variety
* Add access to Social API totals
* Varieties API doesn't return JSON results yet 

## Changelog

### 0.1

* Implemented Social API
* Added models for each api call
* Built cruvee datasource to handle most of the API
* Setup basic plugin

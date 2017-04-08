### Introduction ###

H2O template engine for CodeIgniter 3. This solution based on modified version of H2O v0.3 and Hydrant template class for the Codeigniter 2.

### H2O template ###

H2O is markup language for PHP that has taken a lot of inspiration from Django.

**Features**

* Readable and human-friendly syntax.
* Easy to use and maintain
* Encourages reuse in templates by allowing template inclusion and inheritance.
* Highly extensible through filters, tags, and template extensions.
* Includes a rich set of filters and tags for string formatting, HTML helpers and internationalization support.

### Installation ###

Put everything into *application* folder. Change anything in h2o config file. Use *autoload* or load before use h2o config and library.

### Usage ###

```
#!php
<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller {

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

		// load h2o config
		$this->load->config('h2o');

		// load h2o library
		$this->load->library('h2olib');
	}

	/**
	 * Default index
	 */
	public function index() 
	{
		// page related data
		$data = [
			'title' => 'Page title',
			'content' => 'Page content',
		];

		// render page with h2o
		$this->h2olib->render('index.html', $data);
	}

}
```
index.html:
```
#!html

<body>
    <head><title>{{ title }}</title></head>
    <body>
        {{ content }}
    </body>
</body>
```



### Links ###

* https://github.com/bcit-ci/CodeIgniter
* https://github.com/stickgrinder/hydrant
* https://github.com/speedmax/h2o-php
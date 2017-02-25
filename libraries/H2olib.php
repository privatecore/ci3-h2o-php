<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * H2O is markup language for PHP that has taken a lot of inspiration from Django.
 * This solution based on modified version of H2O v0.3 and Hydrant template class
 * for the Codeigniter 2.
 *
 * https://github.com/stickgrinder/hydrant
 * https://github.com/speedmax/h2o-php
 */
class H2olib {

	/**
	 * CI framework instance
	 *
	 * @var object
	 */
	private $CI;

	/**
	 * H2O template config options
	 *
	 * @var mixed
	 */
	private $_config;

	/**
	 * Object constructor
	 */
	public function __construct()
	{
		$this->CI = &get_instance();

		// load configuration file with section option
		$this->CI->load->config('h2o', TRUE);

		// initialize config options
		$this->_init_config();

		require_once APPPATH . "third_party/h2o-php/h2o.php";
	}

	/**
	 * Initialize H2O configuration exposed in config file
	 */
	private function _init_config()
	{
		$config = $this->CI->config->item('h2o');

		foreach ($config as $key => $value)
		{
			if (substr($key, 0, 4) == 'env_')
			{
				// normalize environment values to match
				// to please H2O tastes
				$config[strtoupper(substr($key, 4))] = $value;
				unset($config[$key]);
			}
		}

		// Set user defined config
		$this->_config = $config;
	}

	/**
	 * Render page with H2O template engine
	 *
	 * @param  mixed   $view
	 * @param  array   $data
	 * @param  boolean $return
	 * @return mixed
	 */
	public function render($view = NULL, $data = [], $return = FALSE)
	{
		try
		{
			$this->h2o = new h2o($view, $this->_config);
		}
		catch (Exception $e)
		{
			log_message('error', 'H2O Exception: '.$e->getMessage());
			show_error('H2O Template encountered an error initializing H2O parser: '.$e->getMessage());
		}

		if ($return == FALSE)
		{
			$this->CI->output->append_output($this->h2o->render($data));
			return TRUE;
		}

		return $this->h2o->render($data);
	}

}


/**
 * CodeIgniter Cache integration class for H2O
 *
 * This class is used by default at initialization and (as for version 1.0) can
 * not be overriden. Since one of the most important CI goals is to render
 * deploy easy and hassle-free, CI caching layer is the best choice not to mangle
 * with files and directories permissions.
 */
class H2o_CI_Cache {

	private $ttl = 3600;
	private $prefix = 'h2o_';
	private $CI; /** Reference to CI framework */

	public function __construct($options = [])
	{
		$this->CI = &get_instance();
		$this->CI->load->driver('cache', ['adapter' => 'file']);

		if (isset($options['cache_ttl']))
		{
			$this->ttl = $options['cache_ttl'];
		}
		if (isset($options['cache_prefix']))
		{
			$this->prefix = $options['cache_prefix'];
		}
	}

	public function read($filename)
	{
		return $this->CI->cache->get($this->prefix.$filename);
	}

	public function write($filename, $object)
	{
		return $this->CI->cache->save($this->prefix.$filename, $object, $this->ttl);
	}

	public function flush()
	{
		return $this->CI->cache->clean();
	}

}

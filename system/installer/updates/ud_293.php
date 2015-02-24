<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		EllisLab Dev Team
 * @copyright	Copyright (c) 2003 - 2014, EllisLab, Inc.
 * @license		http://ellislab.com/expressionengine/user-guide/license.html
 * @link		http://ellislab.com
 * @since		Version 2.9.3
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * ExpressionEngine Update Class
 *
 * @package		ExpressionEngine
 * @subpackage	Core
 * @category	Core
 * @author		EllisLab Dev Team
 * @link		http://ellislab.com
 */
class Updater {

	var $version_suffix = '';

	/**
	 * Do Update
	 *
	 * @return TRUE
	 */
	public function do_update()
	{
		ee()->load->dbforge();

		$steps = new ProgressIterator(
			array(
				'_extract_cache_driver_config',
				'_recompile_template_routes'
			)
		);

		foreach ($steps as $k => $v)
		{
			$this->$v();
		}

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Fixes an issue where the caching driver setting could not be set via
	 * the control panel because the caching driver is loaded before the
	 * database is loaded in order to enable database caching. So we need
	 * to extract the setting from the database and store it in config.php
	 * so the setting is not reliant on the database.
	 */
	private function _extract_cache_driver_config()
	{
		// Get cache driver from config.php if it exists
		// (DB prefs aren't loaded yet)
		$cache_driver = ee()->config->item('cache_driver');

		$sites = ee()->db->select('site_id, site_system_preferences')
			->get('sites')
			->result_array();

		foreach ($sites as $site)
		{
			$prefs = unserialize(base64_decode($site['site_system_preferences']));

			// Don't run the update query if we don't have to
			$update = FALSE;

			// Remove cache_driver from site system preferences array
			if (isset($prefs['cache_driver']))
			{
				if ($cache_driver === FALSE)
				{
					$cache_driver = $prefs['cache_driver'];
				}

				unset($prefs['cache_driver']);

				$update = TRUE;
			}

			if ($update)
			{
				ee()->db->update(
					'sites',
					array('site_system_preferences' => base64_encode(serialize($prefs))),
					array('site_id' => $site['site_id'])
				);
			}
		}

		// If there still isn't a cache driver setting, set it to 'file'
		if ($cache_driver === FALSE)
		{
			$cache_driver = 'file';
		}

		// Add cache_driver back to site preferences, but this time
		// it will end up in config.php because cache_driver is no
		// longer in divination
		if ( ! empty($cache_driver))
		{
			ee()->config->update_site_prefs(array(
				'cache_driver' => $cache_driver
			), 'all');
		}
	}

	// -------------------------------------------------------------------------

	/**
	 * Load all routes and resave to get rid of md5 hashes
	 *
	 * @access private
	 * @return void
	 */
	private function _recompile_template_routes()
	{
		ee()->load->model('template_model');
		ee()->lang->load('template_router', ee()->lang->user_lang, FALSE, TRUE, EE_APPPATH);
		require_once EE_APPPATH . 'libraries/template_router/Route.php';

		ee()->db->select('template_routes.template_id, route_required, route');
		ee()->db->from('templates');
		ee()->db->join('template_routes', 'templates.template_id = template_routes.template_id');
		ee()->db->where('route_parsed is not null');
		$query = ee()->db->get();

		foreach ($query->result() as $template)
		{
			$ee_route = new EE_Route($template->route, $template->route_required == 'y');
			$compiled = $ee_route->compile();
			$data = array('route_parsed' => $compiled);
			ee()->template_model->update_template_route($template->template_id, $data);
		}
	}
}
/* END CLASS */

/* End of file ud_293.php */
/* Location: ./system/expressionengine/installer/updates/ud_293.php */

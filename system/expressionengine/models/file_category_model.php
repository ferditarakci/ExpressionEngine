<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license		http://expressionengine.com/user_guide/license.html
 * @link		http://expressionengine.com
 * @since		Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * ExpressionEngine File Category Model
 *
 * @package		ExpressionEngine
 * @subpackage	Core
 * @category	Model
 * @author		ExpressionEngine Dev Team
 * @link		http://expressionengine.com
 */
class File_category_model extends CI_Model
{
	const TABLE_NAME = 'file_categories';

	/**
	 * Set the file category 
	 *
	 * @param int|string $file_id The id of the file from exp_files
	 * @param int|string $cat_id The id of the category from exp_categories
	 * @param int|string $sort The sort value, 1 being the top and higher values ranking lower
	 * @param string $is_cover Either 'n' or 'y'
	 * @return boolean TRUE if setting the category was successful, FALSE otherwise
	 */
	function set_category($file_id, $cat_id, $sort = NULL, $is_cover = NULL)
	{
		// Make sure sort is numeric and is not negative
		if (isset($sort) AND $this->_is_valid_int($sort))
		{
			$this->db->set('sort', $sort);	
		}

		// Make sure is_cover is either n or y, though it should be y
		if (isset($is_cover) AND ($is_cover === 'n' OR $is_cover === 'y'))
		{
			$this->db->set('is_cover', $is_cover);
		}

		if (
			// Make sure the IDs are valid integers
			! $this->_is_valid_int($file_id) OR ! $this->_is_valid_int($cat_id) OR

			// Make sure both exist in the database
			! $this->_file_exists($file_id) OR ! $this->_category_exists($cat_id)
		)
		{
			return FALSE;	
		}

		$this->db->insert(self::TABLE_NAME, array(
			'file_id' => $file_id,
			'cat_id'  => $cat_id
		));

		return TRUE;
	}	

	// -----------------------------------------------------------------------

	/**
	 * Make sure the parameter passed is a valid non-zero integer
	 *
	 * @param mixed $id Item to check integer validity
	 * @return boolean TRUE if it's an integer or string integer, FALSE otherwise
	 */
	private function _is_valid_int($id)
	{
		return (is_numeric($id) AND intval($id) >= 0) ? TRUE : FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Checks to see if the file exists in the database
	 *
	 * @param string|int $file_id ID of the file to check for
	 * @return boolean TRUE if the file exists, FALSE otherwise
	 */
	private function _file_exists($file_id)
	{
		$this->db->where('file_id', $file_id);
		return ($this->db->count_all_results('files') > 0) ? TRUE : FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Checks to see if the category exists in the database
	 *
	 * @param string|int $cat_id ID of the category to check for
	 * @return boolean TRUE if the category exists, FALSE otherwise
	 */
	private function _category_exists($cat_id)
	{
		$this->db->where('cat_id', $cat_id);
		return ($this->db->count_all_results('categories') > 0) ? TRUE : FALSE;
	}

}

/* End of file file_category_model.php */
/* Location: ./system/expressionengine/models/file_category_model.php */


<?php
/**
 * @copyright	Copyright (C) 2006-2013 JoomLeague.net. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

// import Joomla table library
jimport('joomla.database.table');

// Include library dependencies
jimport('joomla.filter.input');

/**
* Season Table class
*
* @package		Joomleague
* @since 0.1
*/
class sportsmanagementTableTeam extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
		parent::__construct('#__'.COM_SPORTSMANAGEMENT_TABLE.'_team', 'id', $db);
	}

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 * @since 1.0
	 */
	function check()
	{
		if (empty($this->name)) {
			$this->setError(JText::_('NAME REQUIRED'));
			return false;
		}
		
		// add default middle size name
		if (empty($this->middle_name)) {
			$parts = explode(" ", $this->name);
			$this->middle_name = substr($parts[0], 0, 20);
		}
	
		// add default short size name
		if (empty($this->short_name)) {
			$parts = explode(" ", $this->name);
			$this->short_name = substr($parts[0], 0, 2);
		}
	
		// setting alias
		if ( empty( $this->alias ) )
		{
			$this->alias = JFilterOutput::stringURLSafe( $this->name );
		}
		else {
			$this->alias = JFilterOutput::stringURLSafe( $this->alias ); // make sure the user didn't modify it to something illegal...
		}
		
		return true;
	}
	
	/**
	 * Overloaded bind function
	 *
	 * @param       array           named array
	 * @return      null|string     null is operation was satisfactory, otherwise returns an error
	 * @see JTable:bind
	 * @since 1.5
	 */
	function bind($array, $ignore = '')
	{
		if (key_exists( 'extended', $array ) && is_array( $array['extended'] ))
		{
			$registry = new JRegistry();
			$registry->loadArray($array['extended']);
			$array['extended'] = (string) $registry;
		}
		if (key_exists( 'extendeduser', $array ) && is_array( $array['extendeduser'] ))
		{
			$registry = new JRegistry();
			$registry->loadArray($array['extendeduser']);
			$array['extendeduser'] = (string) $registry;
		}
		return parent::bind($array, $ignore);
	}
    
    /**
	 * Overloaded load function
	 *
	 * @param       int $pk primary key
	 * @param       boolean $reset reset data
	 * @return      boolean
	 * @see JTable:load
	 */
	public function load($pk = null, $reset = true) 
	{
		if (parent::load($pk, $reset)) 
		{
			// Convert the params field to a registry.
			$params = new JRegistry;
			$params->loadJSON($this->extended);
			//$params->toArray($this->extended);
            $this->extended = $params->toArray($this->extended);
            
			return true;
			
		}
		else
		{
			return false;
		}
	}
	
	
}
?>

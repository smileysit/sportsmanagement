<?php
/** SportsManagement ein Programm zur Verwaltung für alle Sportarten
* @version         1.0.05
* @file                agegroup.php
* @author                diddipoeler, stony, svdoldie und donclumsy (diddipoeler@arcor.de)
* @copyright        Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
* @license                This file is part of SportsManagement.
*
* SportsManagement is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* SportsManagement is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with SportsManagement.  If not, see <http://www.gnu.org/licenses/>.
*
* Diese Datei ist Teil von SportsManagement.
*
* SportsManagement ist Freie Software: Sie können es unter den Bedingungen
* der GNU General Public License, wie von der Free Software Foundation,
* Version 3 der Lizenz oder (nach Ihrer Wahl) jeder späteren
* veröffentlichten Version, weiterverbreiten und/oder modifizieren.
*
* SportsManagement wird in der Hoffnung, dass es nützlich sein wird, aber
* OHNE JEDE GEWÄHELEISTUNG, bereitgestellt; sogar ohne die implizite
* Gewährleistung der MARKTFÄHIGKEIT oder EIGNUNG FÜR EINEN BESTIMMTEN ZWECK.
* Siehe die GNU General Public License für weitere Details.
*
* Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
* Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>.
*
* Note : All ini files need to be saved as UTF-8 without BOM
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
//jimport('joomla.application.component.modellist');



/**
 * sportsmanagementModelLeagues
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementModelLeagues extends JSMModelList
{
	var $_identifier = "leagues";
	
    /**
     * sportsmanagementModelLeagues::__construct()
     * 
     * @param mixed $config
     * @return void
     */
    public function __construct($config = array())
        {   
                $config['filter_fields'] = array(
                        'obj.name',
                        'obj.alias',
                        'obj.short_name',
                        'obj.country',
                        'st.name',
                        'obj.id',
                        'obj.ordering',
                        'ag.name',
                        'fed.name'
                        );
                //$config['dbo'] = sportsmanagementHelper::getDBConnection();  
                parent::__construct($config);
                //$getDBConnection = sportsmanagementHelper::getDBConnection();
                parent::setDbo($this->jsmdb);
        }
        
    /**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
//		// Reference global application object
//        $app = JFactory::getApplication();
//        // JInput object
//        $jinput = $app->input;
//        $option = $jinput->getCmd('option');
        // Initialise variables.
        
        $this->jsmapp->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' context -> '.$this->context.''),'');

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '', 'string');
		$this->setState('filter.state', $published);
        $temp_user_request = $this->getUserStateFromRequest($this->context.'.filter.search_nation', 'filter_search_nation', '');
		$this->setState('filter.search_nation', $temp_user_request);
        $temp_user_request = $this->getUserStateFromRequest($this->context.'.filter.search_agegroup', 'filter_search_agegroup', '');
		$this->setState('filter.search_agegroup', $temp_user_request);
        $temp_user_request = $this->getUserStateFromRequest($this->context.'.filter.search_association', 'filter_search_association', '');
		$this->setState('filter.search_association', $temp_user_request);

$temp_user_request = $this->getUserStateFromRequest($this->context.'.filter.federation', 'filter_federation', '');
$this->setState('filter.federation', $temp_user_request);  
		
        $value = JRequest::getUInt('limitstart', 0);
		$this->setState('list.start', $value);

//		$image_folder = $this->getUserStateFromRequest($this->context.'.filter.image_folder', 'filter_image_folder', '');
//		$this->setState('filter.image_folder', $image_folder);
        
        //$app->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' image_folder<br><pre>'.print_r($image_folder,true).'</pre>'),'');


//		// Load the parameters.
//		$params = JComponentHelper::getParams('com_sportsmanagement');
//		$this->setState('params', $params);

		// List state information.
		parent::populateState('obj.name', 'asc');
	}
    
	/**
	 * sportsmanagementModelLeagues::getListQuery()
	 * 
	 * @return
	 */
	protected function getListQuery()
	{
//		// Reference global application object
//        $app = JFactory::getApplication();
//        // JInput object
//        $jinput = $app->input;
//        $option = $jinput->getCmd('option');
//        
//        // Create a new query object.		
//		$db = JFactory::getDbo();
//		$query = $db->getQuery(true);
		$this->jsmquery->clear();
        // Select some fields
		$this->jsmquery->select('obj.name,obj.short_name,obj.alias,obj.associations,obj.country,obj.ordering,obj.id,obj.picture,obj.checked_out,obj.checked_out_time,obj.agegroup_id');
        
        $this->jsmquery->select('obj.modified,obj.modified_by');
        $this->jsmquery->select('st.name AS sportstype');
		// From table
		$this->jsmquery->from('#__sportsmanagement_league as obj');
        $this->jsmquery->join('LEFT', '#__sportsmanagement_sports_type AS st ON st.id = obj.sports_type_id');
        // Join over the users for the checked out user.
		$this->jsmquery->select('uc.name AS editor');
		$this->jsmquery->join('LEFT', '#__users AS uc ON uc.id = obj.checked_out');
        
        $this->jsmquery->select('ag.name AS agegroup');
        $this->jsmquery->join('LEFT', '#__sportsmanagement_agegroup AS ag ON ag.id = obj.agegroup_id');
        
        $this->jsmquery->select('fed.name AS fedname');
        $this->jsmquery->join('LEFT', '#__sportsmanagement_associations AS fed ON fed.id = obj.associations');
        
        if ($this->getState('filter.search'))
		{
        $this->jsmquery->where('LOWER(obj.name) LIKE '.$this->jsmdb->Quote('%'.$this->getState('filter.search').'%'));
        }
        
        if ($this->getState('filter.search_nation'))
		{
        $this->jsmquery->where('obj.country LIKE '.$this->jsmdb->Quote(''.$this->getState('filter.search_nation').'') );
        }
        
        if ($this->getState('filter.search_association'))
		{
        $this->jsmquery->where('obj.associations = '.$this->getState('filter.search_association') );
        }
        if ($this->getState('filter.federation'))
		{
        $this->jsmquery->where('obj.associations = '.$this->getState('filter.federation') );
        }
        if ($this->getState('filter.search_agegroup'))
		{
        $this->jsmquery->where('obj.agegroup_id = ' . $this->getState('filter.search_agegroup'));
        }

        $this->jsmquery->order($this->jsmdb->escape($this->getState('list.ordering', 'obj.name')).' '.
                $this->jsmdb->escape($this->getState('list.direction', 'ASC')));
                
                
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($query->dump(),true).'</pre>'),'Notice');        
 
		if ( COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO )
        {
        $my_text = ' <br><pre>'.print_r($this->jsmquery->dump(),true).'</pre>';    
        sportsmanagementHelper::setDebugInfoText(__METHOD__,__FUNCTION__,__CLASS__,__LINE__,$my_text); 
        }
        
        return $this->jsmquery;
	}

  



    
    /**
     * Method to return a leagues array (id,name)
     *
     * @access	public
     * @return	array seasons
     * @since	1.5.0a
     */
    //public static function getLeagues()
    function getLeagues()
    {
        // Reference global application object
//        $app = JFactory::getApplication();
//        // JInput object
//        $jinput = $app->input;
//        $option = $jinput->getCmd('option');
        $search_nation = '';
        
        //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($app,true).'</pre>'),'Notice');
        
        if ( $this->jsmapp->isAdmin() )
        {
        $search_nation	= $this->getState('filter.search_nation');
        //$search_nation	= self::getState('filter.search_nation');
        }
//        // Get a db connection.
//        $db = sportsmanagementHelper::getDBConnection();
//        // Create a new query object.
//        $query = $db->getQuery(true);
        $this->jsmquery->select('id,name');
        $this->jsmquery->from('#__sportsmanagement_league');
        
        if ($search_nation)
		{
        $this->jsmquery->where('country LIKE '.$this->jsmdb->Quote(''.$search_nation.''));
        }
        
        $this->jsmquery->order('name ASC');

        $this->jsmdb->setQuery($this->jsmquery);
        if (!$result = $this->jsmdb->loadObjectList())
        {
            sportsmanagementModeldatabasetool::writeErrorLog(get_class($this), __FUNCTION__, __FILE__, $this->jsmdb->getErrorMsg(), __LINE__);
            //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' getErrorMsg<br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');
            return array();
        }
        foreach ($result as $league)
        {
            $league->name = JText::_($league->name);
        }
        return $result;
    }

	
}
?>

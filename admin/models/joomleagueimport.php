<?php
/** SportsManagement ein Programm zur Verwaltung f�r alle Sportarten
* @version         1.0.05
* @file                agegroup.php
* @author                diddipoeler, stony, svdoldie und donclumsy (diddipoeler@arcor.de)
* @copyright        Copyright: � 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
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
* SportsManagement ist Freie Software: Sie k�nnen es unter den Bedingungen
* der GNU General Public License, wie von der Free Software Foundation,
* Version 3 der Lizenz oder (nach Ihrer Wahl) jeder sp�teren
* ver�ffentlichten Version, weiterverbreiten und/oder modifizieren.
*
* SportsManagement wird in der Hoffnung, dass es n�tzlich sein wird, aber
* OHNE JEDE GEW�HELEISTUNG, bereitgestellt; sogar ohne die implizite
* Gew�hrleistung der MARKTF�HIGKEIT oder EIGNUNG F�R EINEN BESTIMMTEN ZWECK.
* Siehe die GNU General Public License f�r weitere Details.
*
* Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
* Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>.
*
* Note : All ini files need to be saved as UTF-8 without BOM
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');
jimport('joomla.application.component.modellist');

$maxImportTime = 1920;
if ((int)ini_get('max_execution_time') < $maxImportTime){@set_time_limit($maxImportTime);}

/**
 * sportsmanagementModeljoomleagueimport
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementModeljoomleagueimport extends JModelList
{




/**
 * sportsmanagementModeljoomleagueimport::getImportPositions()
 * 
 * @param string $component
 * @return
 */
function getImportPositions($component = 'joomleague',$which_table='project_position')
{
    $app = JFactory::getApplication();
    $db = JFactory::getDbo(); 
    $option = JRequest::getCmd('option');
    
    // Select some fields
            $query = $db->getQuery(true);
            $query->clear();
		    $query->select('pos.*,pos.id as value, pos.name as text');
// From table
		    $query->from('#__'.$component.'_position as pos');
            
switch ($component)
{
    case 'joomleague':
    $query->join('INNER', '#__'.$component.'_'.$which_table.' AS pp ON pp.position_id = pos.id');
    //$query->join('INNER', '#__'.$component.'_person AS pp ON pp.position_id = pos.id');
    $query->group('pos.id');
    break;
}
            
    
    
    $db->setQuery($query);
    $result = $db->loadObjectList();
    return $result;
}


/**
 * sportsmanagementModeljoomleagueimport::newstructur()
 * 
 * @param mixed $step
 * @param integer $count
 * @return void
 */
function newstructur($step,$count=5)
{
    $app = JFactory::getApplication();
    $db = JFactory::getDbo(); 
    $option = JRequest::getCmd('option');
    $starttime = microtime(); 
        
    $season_id = $app->getUserState( "$option.season_id", '0' );
    $jl_table = $app->getUserState( "$option.jl_table", '' );
    $jsm_table = $app->getUserState( "$option.jsm_table", '' );
    
    // felder f�r den import auslesen
    $jl_fields = $db->getTableFields($jl_table);
    $jsm_fields = $db->getTableFields($jsm_table);
    
    if ( preg_match("/project_team/i", $jsm_table) )
    {

            // Select some fields
            $query = $db->getQuery(true);
            $query->clear();
		    $query->select('pt.*');
            //$query->select('p.season_id');
            // From joomleague table
		    $query->from($jl_table.' AS pt');
            $query->join('INNER','#__sportsmanagement_project AS p ON p.id = pt.project_id');
            $query->where('pt.import = 0');
            
            if ( $season_id )
            {
                $query->select('p.season_id');
                $query->where('p.season_id = '.$season_id);
            }
            
            $db->setQuery($query,$step,$count);
            
            if ( COM_SPORTSMANAGEMENT_SHOW_QUERY_DEBUG_INFO )
        {
            $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' query<br><pre>'.print_r($query->dump(),true).'</pre>'),'Notice');
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' Ausfuehrungszeit query<br><pre>'.print_r(sportsmanagementModeldatabasetool::getQueryTime($starttime, microtime()),true).'</pre>'),'Notice');
        }
        
            $result = $db->loadObjectList();
            
            foreach ( $result as $row )
            {
                // Create and populate an object.
                $temp = new stdClass();
                $temp->season_id = $row->season_id;
                $temp->team_id = $row->team_id;
                // Insert the object into table.
                $result = JFactory::getDbo()->insertObject('#__sportsmanagement_season_team_id', $temp);
                if ( $result )
                {
                    $new_id = $db->insertid();
                }
                else
                {
                    // Select some fields
                    $query = $db->getQuery(true);
                    $query->clear();
		            $query->select('id');
                    // From table
                    $query->from('#__sportsmanagement_season_team_id');
                    $query->where('season_id = '.$row->season_id);
                    $query->where('team_id = '.$row->team_id);
                    $db->setQuery($query);
                    $new_id = $db->loadResult();
                }
                
                // Create an object for the record we are going to joomleague update.
                $object = new stdClass();
                // Must be a valid primary key value.
                $object->id = $row->id;
                $object->import = 1;
                // Update their details in the users table using id as the primary key.
                $result = JFactory::getDbo()->updateObject($jl_table, $object, 'id'); 
                
                // Create an object for the record we are going to joomleague update.
                $object = new stdClass();
                $jsm_field_array = $jsm_fields[$jsm_table];
                //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'jsm_field_array<br><pre>'.print_r($jsm_field_array,true).'</pre>'),'');
                foreach ( $jl_fields[$jl_table] as $key2 => $value2 )
                {
                //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'key<br><pre>'.print_r($key,true).'</pre>'),'');
                //$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'value<br><pre>'.print_r($value,true).'</pre>'),'');
                if (array_key_exists($key2, $jsm_field_array)) 
                {
                    $object->$key2 = $row->$key2;
                }
                }
                // jetzt die neue team_id
                $object->team_id = $new_id;
                // Insert the object into table.
                $result2 = JFactory::getDbo()->insertObject($jsm_table, $object);
                
                if ( $result2 )
                {
                    // alles in ordnung
                }
                else
                {
                    // eintrag schon vorhanden, ein update
                    $tblProjectteam = JTable::getInstance('Projectteam', 'sportsmanagementtable');
                    $tblProjectteam->load($row->id);
                    
                    if ( empty($tblProjectteam->team_id) )
                    {
                        $tblProjectteam->team_id = $new_id;
                        if (!$tblProjectteam->store())
				        {
				        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'<br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');
				        }
                    }
                    /*
                    // eintrag schon vorhanden, ein update
                    // Create an object for the record we are going to update.
                    $object = new stdClass();
                    // Must be a valid primary key value.
                    $object->id = $row->id;
                    $object->team_id = $new_id;
                    // Update their details in the users table using id as the primary key.
                    $result = JFactory::getDbo()->updateObject($jsm_table, $object, 'id');
                    */
                }
                
                
                
                
                
            }
            
            }
            elseif ( preg_match("/team_player/i", $jsm_table) )
    {
        
        
        
        $query = $db->getQuery(true);
        $query->clear();
        $query->select('tp.*,st.team_id');
        $query->from($jl_table.' AS tp');
        $query->join('INNER','#__sportsmanagement_project_team AS pt ON pt.id = tp.projectteam_id');
        $query->join('INNER','#__sportsmanagement_project AS p ON p.id = pt.project_id');
        $query->join('INNER','#__sportsmanagement_season_team_id as st ON st.id = pt.team_id ');
        $query->where('tp.import = 0');
        
        if ( $season_id )
            {
                $query->select('p.season_id');
                $query->where('p.season_id = '.$season_id);
            }
            
            $db->setQuery($query,$step,$count);
            
            if ( COM_SPORTSMANAGEMENT_SHOW_QUERY_DEBUG_INFO )
        {
            $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' query<br><pre>'.print_r($query->dump(),true).'</pre>'),'Notice');
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' Ausfuehrungszeit query<br><pre>'.print_r(sportsmanagementModeldatabasetool::getQueryTime($starttime, microtime()),true).'</pre>'),'Notice');
        }
        
            $result = $db->loadObjectList();
        
        foreach ( $result as $row )
            {
                // als erstes wird der spieler der saison zugeordnet
                // Create and populate an object.
                $temp = new stdClass();
                $temp->person_id = $row->person_id;
                $temp->season_id = $row->season_id;
                $temp->team_id = $row->team_id;
                $temp->picture = $row->picture;
                $temp->persontype = 1;
                // Insert the object into the user profile table.
                $result = JFactory::getDbo()->insertObject('#__sportsmanagement_season_person_id', $temp);
                
                // ist der spieler schon in der season team person tabelle ?
                // Select some fields
                $query = $db->getQuery(true);
                $query->clear();
		        $query->select('id');
                // From table
                $query->from('#__sportsmanagement_season_team_person_id');
                $query->where('person_id = '.$row->person_id);
                $query->where('season_id = '.$row->season_id);
                $query->where('team_id = '.$row->team_id);
                $db->setQuery($query);
                $new_id = $db->loadResult();
                
                if ( !$new_id )
                {
                    // Create and populate an object.
                    $temp = new stdClass();
                    $temp->season_id = $row->season_id;
                    $temp->team_id = $row->team_id;
                    $temp->person_id = $row->person_id;
                    $temp->picture = $row->picture;
                    $temp->persontype = 1;
                    $temp->active = 1;
                    $temp->published = 1;
                    // Insert the object into the user profile table.
                    $result = JFactory::getDbo()->insertObject('#__sportsmanagement_season_team_person_id', $temp);
                    if ( $result )
                    {
                        $new_id = $db->insertid();
                    }
                    else
                    {
                        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'<br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');
                    }
                
                }
                
                // Create an object for the record we are going to joomleague update.
                $object = new stdClass();
                // Must be a valid primary key value.
                $object->id = $row->id;
                $object->import = $new_id;
                // Update their details in the users table using id as the primary key.
                $result_update = JFactory::getDbo()->updateObject('#__joomleague_team_player', $object, 'id'); 
                
                if ( !$result_update )
                    {
                        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'<br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');
                    }
                    else
                    {
                        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' update team_player id: '.$row->id.' mit season_team_person id: '.$new_id),'');
                    }
            
            }
    }
    elseif ( preg_match("/team_staff/i", $jsm_table) )
    {
    $query = $db->getQuery(true);
        $query->clear();
        $query->select('tp.*,st.team_id');
        $query->from($jl_table.' AS tp');
        $query->join('INNER','#__sportsmanagement_project_team AS pt ON pt.id = tp.projectteam_id');
        $query->join('INNER','#__sportsmanagement_project AS p ON p.id = pt.project_id');
        $query->join('INNER','#__sportsmanagement_season_team_id as st ON st.id = pt.team_id ');
        $query->where('tp.import = 0');
        
        if ( $season_id )
            {
                $query->select('p.season_id');
                $query->where('p.season_id = '.$season_id);
            }
            
            $db->setQuery($query,$step,$count);
            
            if ( COM_SPORTSMANAGEMENT_SHOW_QUERY_DEBUG_INFO )
        {
            $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' query<br><pre>'.print_r($query->dump(),true).'</pre>'),'Notice');
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' Ausfuehrungszeit query<br><pre>'.print_r(sportsmanagementModeldatabasetool::getQueryTime($starttime, microtime()),true).'</pre>'),'Notice');
        }
        
            $result = $db->loadObjectList();
        
        foreach ( $result as $row )
            {
                // als erstes wird der spieler der saison zugeordnet
                // Create and populate an object.
                $temp = new stdClass();
                $temp->person_id = $row->person_id;
                $temp->season_id = $row->season_id;
                $temp->team_id = $row->team_id;
                $temp->picture = $row->picture;
                $temp->persontype = 2;
                // Insert the object into the user profile table.
                $result = JFactory::getDbo()->insertObject('#__sportsmanagement_season_person_id', $temp);
                
                // ist der spieler schon in der season team person tabelle ?
                // Select some fields
                $query = $db->getQuery(true);
                $query->clear();
		        $query->select('id');
                // From table
                $query->from('#__sportsmanagement_season_team_person_id');
                $query->where('person_id = '.$row->person_id);
                $query->where('season_id = '.$row->season_id);
                $query->where('team_id = '.$row->team_id);
                $db->setQuery($query);
                $new_id = $db->loadResult();
                
                if ( !$new_id )
                {
                    // Create and populate an object.
                    $temp = new stdClass();
                    $temp->season_id = $row->season_id;
                    $temp->team_id = $row->team_id;
                    $temp->person_id = $row->person_id;
                    $temp->picture = $row->picture;
                    $temp->persontype = 2;
                    $temp->active = 1;
                    $temp->published = 1;
                    // Insert the object into the user profile table.
                    $result = JFactory::getDbo()->insertObject('#__sportsmanagement_season_team_person_id', $temp);
                    if ( $result )
                    {
                        $new_id = $db->insertid();
                    }
                    else
                    {
                        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'<br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');
                    }
                
                }
                
                // Create an object for the record we are going to joomleague update.
                $object = new stdClass();
                // Must be a valid primary key value.
                $object->id = $row->id;
                $object->import = $new_id;
                // Update their details in the users table using id as the primary key.
                $result_update = JFactory::getDbo()->updateObject('#__joomleague_team_staff', $object, 'id'); 
                
                if ( !$result_update )
                    {
                        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'<br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');
                    }
                    else
                    {
                        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' update team_staff id: '.$row->id.' mit season_team_person id: '.$new_id),'');
                    }
    }  
    }      
            // danach die alten datens�tze l�schen
            //$db->truncateTable($jsm_table);
 
       
             

}


            
}    

?>
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


/**
 * sportsmanagementModelsmimageimports
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementModelsmimageimports extends JModelList
{
    var $_identifier = "pictures";
	
    
    public function __construct($config = array())
        {   
                $config['filter_fields'] = array(
                        'name',
                        'file',
                        'folder',
                        'directory'
                        );
                parent::__construct($config);
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
		$mainframe = JFactory::getApplication();
        $option = JRequest::getCmd('option');
        // Initialise variables.
		$app = JFactory::getApplication('administrator');
        
        //$mainframe->enqueueMessage(JText::_('sportsmanagementModelsmquotes populateState context<br><pre>'.print_r($this->context,true).'</pre>'   ),'');

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);

		$image_folder = $this->getUserStateFromRequest($this->context.'.filter.image_folder', 'filter_image_folder', '');
		$this->setState('filter.image_folder', $image_folder);
        
        //$mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' image_folder<br><pre>'.print_r($image_folder,true).'</pre>'),'');


//		// Load the parameters.
//		$params = JComponentHelper::getParams('com_sportsmanagement');
//		$this->setState('params', $params);

		// List state information.
		parent::populateState('obj.name', 'asc');
	}
    
    
    
	protected function getListQuery()
	{
		$mainframe = JFactory::getApplication();
        $option = JRequest::getCmd('option');
        $search	= $this->getState('filter.search');
        $filter_state = $this->getState('filter.state');
        $filter_image_folder = $this->getState('filter.image_folder');
        //$search	= $mainframe->getUserStateFromRequest($option.'.'.$this->_identifier.'.search','search','','string');
        //$search_nation		= $mainframe->getUserStateFromRequest($option.'.'.$this->_identifier.'.search_nation','search_nation','','word');
        // Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// Select some fields
		$query->select('obj.*');
		// From the hello table
		$query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_pictures as obj');
        // Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id = obj.checked_out');
        
        if (is_numeric($filter_state) )
		{
		$query->where('obj.published = '.$filter_state);	
		}
        if ( $search )
		{
		$query->where('LOWER(obj.name) LIKE '.$this->_db->Quote('%'.$search.'%'));	
		}
        if ( $filter_image_folder )
		{
		$query->where("obj.folder LIKE '".$filter_image_folder."'");	
		}
        
        $query->order($db->escape($this->getState('list.ordering', 'name')).' '.
                $db->escape($this->getState('list.direction', 'ASC')));
                
        //$mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.'<br><pre>'.print_r($query->dump(),true).'</pre>'),'');
 
		
        return $query;
	}
    
function getXMLFolder()
{
    $mainframe = JFactory::getApplication();
        $option = JRequest::getCmd('option');
// Get a db connection.
        $db = JFactory::getDBO();
        // Create a new query object.
        $query = $db->getQuery(true);
        $query->select(array('folder as id', 'folder as name'))
        ->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_pictures')
        ->order('folder ASC')
        ->group('folder ASC');

        $db->setQuery($query);
        if (!$result = $db->loadObjectList())
        {
            sportsmanagementModeldatabasetool::writeErrorLog(get_class($this), __FUNCTION__, __FILE__, $this->_db->getErrorMsg(), __LINE__);
            return array();
        }
        
        //$mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.'<br><pre>'.print_r($query->dump(),true).'</pre>'),'');
        
        return $result;    
}


/**
 * 
 * sportsmanagementModelsmimageimports::getimagesxml()
 * 
 * @return
 */
function getimagesxml()
{
    $mainframe = JFactory::getApplication(); 
        $option = JRequest::getCmd('option');  
        // sind neue bilder pakete vorhanden ?
		//$content = file_get_contents('https://raw2.github.com/diddipoeler/sportsmanagement/master/admin/helpers/xml_files/pictures.xml');
		
		$datei = "https://raw2.github.com/diddipoeler/sportsmanagement/master/admin/helpers/xml_files/pictures.xml";
if (function_exists('curl_version'))
{
    $curl = curl_init();
    //Define header array for cURL requestes
    $header = array('Contect-Type:application/xml');
    curl_setopt($curl, CURLOPT_URL, $datei);
    curl_setopt($curl, CURLOPT_VERBOSE, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    //curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER , $header);
    
    
    if (curl_errno($curl)) 
    {
        // moving to display page to display curl errors
          //echo curl_errno($curl) ;
          //echo curl_error($curl);
          //$mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__. '<br><pre>'.print_r(curl_errno($curl),true).'</pre>'),'Error');
          //$mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__. '<br><pre>'.print_r(curl_error($curl),true).'</pre>'),'Error');
          
          
    }
    else
    {
        $content = curl_exec($curl);
        //print_r($content);
        curl_close($curl);
    } 
    
    //$mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__. '<br><pre>'.print_r($content,true).'</pre>'),'');
}
else if (file_get_contents(__FILE__) && ini_get('allow_url_fopen'))
{
    $content = file_get_contents($datei);
    //$mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' '.__LINE__. '<br><pre>'.print_r($content,true).'</pre>'),'');
}
else
{
    //echo 'Sie haben weder cURL installiert, noch allow_url_fopen aktiviert. Bitte aktivieren/installieren allow_url_fopen oder Curl!';
    $mainframe->enqueueMessage(JText::_('COM_SPORTSMANAGEMENT_ADMIN_GLOBAL_ERROR_ALLOW_URL_FOPEN'),'Error');
}
		
		
		if ( $content )
        {
            //--L�schen einer einzelnen Datei
            if( JFile::delete(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'xml_files'.DS.'pictures.xml') )
            {
            //echo 'Die Datei wurde gel�scht.';
            }

		//Parsen
		$doc = DOMDocument::loadXML($content);
        
        //$mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.'<br><pre>'.print_r($doc,true).'</pre>'),'');
        
        $doc->save(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'xml_files'.DS.'pictures.xml');
        }
    
}
/**
 * sportsmanagementModelsmimageimports::getXMLFiles()
 * 
 * @return
 */
function getXMLFiles()
    {
        $mainframe = JFactory::getApplication();
        $option = JRequest::getCmd('option');
        $files = array();
        $path = JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'xml_files'.DS.'pictures.xml';
        $xml = JFactory::getXMLParser( 'Simple' );
       $xml->loadFile($path); 
       
       $i = 0;
       // schleife altersgruppen anfang
       foreach( $xml->document->pictures as $picture ) 
{
   $name = $picture->getElementByPath('picture');
   $attributes = $name->attributes();
   $picturedescription = $name->data();
   $folder = $attributes['folder'];
   $directory = $attributes['directory'];
   $file = $attributes['file'];
   
   $temp = new stdClass();
   $temp->id = $i;
   $temp->picture = $picturedescription;
   $temp->folder = $folder;
   $temp->directory = $directory;
   $temp->file = $file;
   $export[] = $temp;
   $files = array_merge($export);
   $i++;
   
   // Create and populate an object.
              $temp = new stdClass();
              $temp->name = $picturedescription;
              $temp->file = $file;
              $temp->directory = $directory;
              $temp->folder = $folder;
              $temp->published = 0;
              // Insert the object
              $result = JFactory::getDbo()->insertObject('#__'.COM_SPORTSMANAGEMENT_TABLE.'_pictures', $temp);
   
   
   
   
   }
       
    //$mainframe->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.'<br><pre>'.print_r($files,true).'</pre>'),'');   
        
        return $files;
        
    }	
    
}

?>
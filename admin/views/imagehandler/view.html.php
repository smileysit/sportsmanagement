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
* OHNE JEDE GEWÄHRLEISTUNG, bereitgestellt; sogar ohne die implizite
* Gewährleistung der MARKTFÄHIGKEIT oder EIGNUNG FÜR EINEN BESTIMMTEN ZWECK.
* Siehe die GNU General Public License für weitere Details.
*
* Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
* Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>.
*
* Note : All ini files need to be saved as UTF-8 without BOM
*/

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');


/**
 * sportsmanagementViewImagehandler
 * 
 * @package 
 * @author diddi
 * @copyright 2014
 * @version $Id$
 * @access public
 */
class sportsmanagementViewImagehandler extends sportsmanagementView  
{

	
    /**
     * sportsmanagementViewImagehandler::init()
     * 
     * @return
     */
    public function init ()
	{
		$app	= JFactory::getApplication();
		$document = JFactory::getDocument();
		$jinput = $app->input;
        $uri = JFactory::getURI();
        $tpl = '';


		if( $this->getLayout() == 'upload' || $this->getLayout() == 'upload_3' ) 
        {
			$this->_displayupload($tpl);
			return;
		}

		//get vars
		$type     	= JRequest::getVar( 'type' );
		$folder 	= ImageSelectSM::getfolder($type);
		$field 		= JRequest::getVar( 'field' );
		$fieldid 	= JRequest::getVar( 'fieldid' );
		$search 	= $app->getUserStateFromRequest( 'com_sportsmanagement.imageselect', 'search', '', 'string' );
		$search 	= trim(JString::strtolower( $search ) );
        
//        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' type -> '.$type.''),'Notice');
//        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' folder -> '.$folder.''),'Notice');

		//add css
		//$version = urlencode(sportsmanagementHelper::getVersion());
		//$document->addStyleSheet('components/com_sportsmanagement/assets/css/imageselect.css?v='.$version);

		$jinput->set( 'folder', $folder );

		// Do not allow cache
		JResponse::allowCache(false);

		//get images
		$images 	= $this->get('Images');
		$pageNav 	= $this->get('Pagination');
        
        $this->request_url	= $uri->toString();

		if (count($images) > 0 || $search) {
			$this->images	= $images;
			$this->type	= $type;
			$this->folder	= $folder;
			$this->search	= $search;
			$this->state	= $this->get('state');
			$this->pageNav	= $pageNav;
			$this->field	= $field;
			$this->fieldid	= $fieldid;
			//$this->assign('form'      	, $this->get('form'));
			//parent::display($tpl);
		} else {
			//no images in the folder, redirect to uploadscreen and raise notice
			JError::raiseNotice('SOME_ERROR_CODE', JText::_('COM_SPORTSMANAGEMENT_ADMIN_IMAGEHANDLER_NO_IMAGES'));
			$this->setLayout('upload');
			$this->form	= $this->get('form');
			$this->_displayupload($tpl);
			return;
		}
	}

	/**
	 * sportsmanagementViewImagehandler::setImage()
	 * 
	 * @param integer $index
	 * @return void
	 */
	function setImage($index = 0)
	{
		if (isset($this->images[$index]))
		{
			$this->_tmp_img = &$this->images[$index];
		} else {
			$this->_tmp_img = new JObject;
		}
	}

	/**
	 * Prepares the upload image screen
	 *
	 * @param $tpl
	 *
	 * @since 0.9
	 */
	function _displayupload($tpl = null)
	{
		$app	= JFactory::getApplication();
		$jinput = $app->input;
		$option = $jinput->getCmd('option');

		//initialise variables
		$document	= JFactory::getDocument();
		$uri 		= JFactory::getURI();
		$params 	= JComponentHelper::getParams($option);
		$type     	= $jinput->get( 'type' );
		$folder 	= ImageSelectSM::getfolder($type);
		$field  	= $jinput->get( 'field' );
		$fieldid  	= $jinput->get( 'fieldid' );
		$menu 		= $jinput->set( 'hidemainmenu', 1 );
		//get vars
		$task 		= $jinput->get( 'task' );

		jimport('joomla.client.helper');
		$ftp = JClientHelper::setCredentialsFromRequest('ftp');

		//assign data to template
		$this->params	= $params;
		$this->request_url	= $uri->toString();
		$this->ftp	= $ftp;
		$this->folder	= $folder;
		$this->field	= $field;
		$this->fieldid	= $fieldid;
		$this->menu	= $menu;
        $this->setLayout('upload');
		//parent::display($tpl);
	}
}
?>
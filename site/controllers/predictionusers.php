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

jimport('joomla.application.component.controller');
jimport('joomla.application.component.controllerform');


/**
 * sportsmanagementControllerPredictionUsers
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementControllerPredictionUsers extends JControllerLegacy
{


	/**
	 * sportsmanagementControllerPredictionUsers::display()
	 * 
	 * @param bool $cachable
	 * @param bool $urlparams
	 * @return void
	 */
	function display($cachable = false, $urlparams = false)
	{

		parent::display($cachable, $urlparams = false);
	}

	/**
	 * sportsmanagementControllerPredictionUsers::cancel()
	 * 
	 * @return
	 */
	function cancel()
	{
		JFactory::getApplication()->redirect(str_ireplace('&layout=edit','',JFactory::getURI()->toString()));
	}

	/**
	 * sportsmanagementControllerPredictionUsers::select()
	 * 
	 * @return
	 */
	function select()
	{
		JRequest::checkToken() or jexit(JText::_('JL_PRED_INVALID_TOKEN_REFUSED'));
        // Reference global application object
        $app = JFactory::getApplication();
        // JInput object
        $jinput = $app->input;
        $post = $jinput->post->getArray(array());
        
//		$pID	= JRequest::getVar('prediction_id',	'',		'post',	'int');
//		$uID	= JRequest::getVar('uid',			null,	'post',	'int');
		if (empty($post ['uid']))
        {
            $post ['uid'] = null;
            }
		$link = JSMPredictionHelperRoute::getPredictionMemberRoute($post['prediction_id'],$post['uid'],$post['task'],$post['pj'],$post['pggroup'],$post['r']);
		//echo '<br />' . $link . '<br />';
		$this->setRedirect($link);
	}

	/**
	 * sportsmanagementControllerPredictionUsers::savememberdata()
	 * 
	 * @return
	 */
	function savememberdata()
	{
		JRequest::checkToken() or jexit(JText::_('JL_PRED_USERS_INVALID_TOKEN_MEMBER_NOT_SAVED'));
        $option = JRequest::getCmd('option');
        $optiontext = strtoupper(JRequest::getCmd('option').'_');
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
        
		$msg	= '';
		$link	= '';

		$post	= JRequest::get('post');
		//echo '<br /><pre>~' . print_r($post,true) . '~</pre><br />';
		$predictionGameID	= JRequest::getVar('prediction_id',	'','post','int');
		$joomlaUserID		= JRequest::getVar('user_id',		'','post','int');

		//$model			= $this->getModel('predictionusers');
        $modelusers = JModelLegacy::getInstance("predictionusers", "sportsmanagementModel");
        $model = JModelLegacy::getInstance("prediction", "sportsmanagementModel");
		$user			= JFactory::getUser();
		$isMember		= $model->checkPredictionMembership();
		$allowedAdmin	= $model->getAllowed();

		if ( ( ( $user->id != $joomlaUserID ) ) && ( !$allowedAdmin ) )
		{
			$msg .= JText::_('COM_SPORTSMANAGEMENT_PRED_USERS_CONTROLLER_ERROR_1');
			$link = JFactory::getURI()->toString();
		}
		else
		{
			if ((!$isMember) && (!$allowedAdmin))
			{
				$msg .= JText::_('COM_SPORTSMANAGEMENT_PRED_USERS_CONTROLLER_ERROR_2');
				$link = JFactory::getURI()->toString();
			}
			else
			{
				if (!$modelusers->savememberdata())
				{
					$msg .= JText::_('COM_SPORTSMANAGEMENT_PRED_USERS_CONTROLLER_ERROR_3');
					$link = JFactory::getURI()->toString();
				}
				else
				{
					$msg .= JText::_('COM_SPORTSMANAGEMENT_PRED_USERS_CONTROLLER_MSG_1');
					$link = JFactory::getURI()->toString();
				}
			}
		}

		//echo '<br />';
		//echo '' . $link . '<br />';
		//echo '' . $msg . '<br />';
		$this->setRedirect($link,$msg);
	}

	/**
	 * sportsmanagementControllerPredictionUsers::selectprojectround()
	 * 
	 * @return
	 */
	function selectprojectround()
	{
		JRequest::checkToken() or jexit(JText::_('JL_PRED_INVALID_TOKEN_REFUSED'));
		// Reference global application object
        $app = JFactory::getApplication();
        // JInput object
        $jinput = $app->input;
        $pID = $jinput->getVar('prediction_id','0');
        $pggroup = $jinput->getVar('pggroup','0');
        $pggrouprank = $jinput->getVar('pggrouprank','0');
        $pjID = $jinput->getVar('pj','0');
        $rID = $jinput->getVar('r','0');
        $set_pj = $jinput->getVar('set_pj','0');
        $set_r = $jinput->getVar('set_r','0');

		$link = JSMPredictionHelperRoute::getPredictionMemberRoute($pID,$uID,null,$pjID,$pggroup ,$rID );
		//echo '<br />' . $link . '<br />';
		$this->setRedirect($link);
	}

}
?>
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

jimport('joomla.application.component.view');
//require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'imageselect.php');
require_once( JPATH_COMPONENT_SITE . DS . 'models' . DS . 'predictionusers.php' );


/**
 * sportsmanagementViewPredictionUser
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementViewPredictionUser extends sportsmanagementView
{

	
	/**
	 * sportsmanagementViewPredictionUser::init()
	 * 
	 * @return void
	 */
	function init()
	{
//		// Get a refrence of the page instance in joomla
//		$document	= JFactory::getDocument();
//    $option = JRequest::getCmd('option');
////    $optiontext = strtoupper(JRequest::getCmd('option').'_');
////    $this->assignRef( 'optiontext',			$optiontext );
////    $this->assign('show_debug_info', JComponentHelper::getParams('com_sportsmanagement')->get('show_debug_info',0) );
//
//		$app = JFactory::getApplication();
		
		$this->document->addScript(JURI::root().'components/com_sportsmanagement/assets/js/json2.js');
		$this->document->addScript(JURI::root().'components/com_sportsmanagement/assets/js/swfobject.js');
		
		//$model		= $this->getModel();
    $mdlPredUsers = JModelLegacy::getInstance("predictionusers", "sportsmanagementModel");
    
		$this->predictionGame = sportsmanagementModelPrediction::getPredictionGame();
        
        $tippAllowed = false;

		if (isset($this->predictionGame))
		{
			$config				= sportsmanagementModelPrediction::getPredictionTemplateConfig('predictionusers');
			$overallConfig		= sportsmanagementModelPrediction::getPredictionOverallConfig();
			$tipprankingconfig	= sportsmanagementModelPrediction::getPredictionTemplateConfig('predictionranking');
			$flashconfig 		= sportsmanagementModelPrediction::getPredictionTemplateConfig( "predictionflash" );
			
			$configavatar			= sportsmanagementModelPrediction::getPredictionTemplateConfig('predictionusers');
						
			//$rankingconfig	= $model->getPredictionTemplateConfig('ranking');

//      $this->assignRef('debuginfo',	$model->getDebugInfo());
      
			$this->model = $mdlPredUsers;
			$this->roundID = sportsmanagementModelPrediction::$roundID;
			$this->config = array_merge($overallConfig,$tipprankingconfig,$config);
			$this->configavatar = $configavatar;
			
			$this->predictionMember = sportsmanagementModelPrediction::getPredictionMember($configavatar);
			
      if (!isset($this->predictionMember->id))
			{
				$this->predictionMember->id=0;
				$this->predictionMember->pmID=0;
			}
			
			$this->predictionProjectS = sportsmanagementModelPrediction::getPredictionProjectS();

			$this->actJoomlaUser = JFactory::getUser();
			$this->isPredictionMember = sportsmanagementModelPrediction::checkPredictionMembership();
			$this->memberData = sportsmanagementModelPredictionUsers::memberPredictionData();
			$this->allowedAdmin = sportsmanagementModelPrediction::getAllowed();
			
			if (!empty($this->predictionMember->user_id)) 
            {
				$this->showediticon = sportsmanagementModelPrediction::getAllowed($this->predictionMember->user_id);
			}
			
			$this->_setPointsChartdata(array_merge($flashconfig, $config));
			$this->_setRankingChartdata(array_merge($flashconfig, $config));
			//echo '<br /><pre>~' . print_r($this->predictionMember,true) . '~</pre><br />';

			$lists = array();

			if ($this->predictionMember->pmID > 0){$dMemberID=$this->predictionMember->pmID;}else{$dMemberID=0;}
			if (!$this->allowedAdmin){$userID=$this->actJoomlaUser->id;}else{$userID=null;}

			$predictionMembers[] = JHTML::_('select.option','0',JText::_('COM_SPORTSMANAGEMENT_PRED_SELECT_MEMBER'),'value','text');
			if ($res = sportsmanagementModelPrediction::getPredictionMemberList($this->config,$userID))
            {
                $predictionMembers = array_merge($predictionMembers,$res);
                }
                
			$lists['predictionMembers'] = JHTML::_('select.genericList',$predictionMembers,'uid','class="inputbox" onchange="this.form.submit(); "','value','text',$dMemberID);
			unset($res);
			unset($predictionMembers);
        
        
        
            

			if (empty($this->predictionMember->fav_team)){$this->predictionMember->fav_team='0,0';}
			$sFavTeamsList=explode(';',$this->predictionMember->fav_team);
			foreach ($sFavTeamsList AS $key => $value){$dFavTeamsList[]=explode(',',$value);}
			foreach ($dFavTeamsList AS $key => $value){$favTeamsList[$value[0]]=$value[1];}

			//echo '<br /><pre>~' . print_r($this->predictionMember->champ_tipp,true) . '~</pre><br />';
			if (empty($this->predictionMember->champ_tipp)){$this->predictionMember->champ_tipp='0,0';}
			$sChampTeamsList=explode(';',$this->predictionMember->champ_tipp);
			foreach ($sChampTeamsList AS $key => $value){$dChampTeamsList[]=explode(',',$value);}
			foreach ($dChampTeamsList AS $key => $value){$champTeamsList[$value[0]]=$value[1];}

			if ($this->getLayout()=='edit')
			{
				$dArray[] = JHTML::_('select.option',0,JText::_('JNO'));
				$dArray[] = JHTML::_('select.option',1,JText::_('JYES'));

				$lists['show_profile']		= JHTML::_('select.radiolist',$dArray,'show_profile',	'class="inputbox" size="1"','value','text',$this->predictionMember->show_profile);
				$lists['reminder']			= JHTML::_('select.radiolist',$dArray,'reminder',		'class="inputbox" size="1"','value','text',$this->predictionMember->reminder);
				$lists['receipt']			= JHTML::_('select.radiolist',$dArray,'receipt',		'class="inputbox" size="1"','value','text',$this->predictionMember->receipt);
				$lists['admintipp']			= JHTML::_('select.radiolist',$dArray,'admintipp',		'class="inputbox" size="1"','value','text',$this->predictionMember->admintipp);
				$lists['approvedForGame']	= JHTML::_('select.radiolist',$dArray,'approved',		'class="inputbox" size="1" disabled="disabled"','value','text',$this->predictionMember->approved);
				unset($dArray);
                
                // schleife �ber die projekte
				foreach ($this->predictionProjectS AS $predictionProject)
				{
					
          if ( COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO )
          {
          echo 'predictionuser view.html -> predictionProject<br /><pre>~' . print_r($predictionProject,true) . '~</pre><br />';
          }
          
					$projectteams[] = JHTML::_('select.option','0',JText::_('COM_SPORTSMANAGEMENT_PRED_USERS_SELECT_TEAM'),'value','text');
					if ($res = sportsmanagementModelPredictionUsers::getPredictionProjectTeams($predictionProject->project_id))
					{
						$projectteams = array_merge($projectteams,$res);
					}
					if (!isset($favTeamsList[$predictionProject->project_id]))
          {
          $favTeamsList[$predictionProject->project_id]=0;
          }
          
					$lists['fav_team'][$predictionProject->project_id] = JHTML::_('select.genericList',$projectteams,'fav_team['.$predictionProject->project_id.']','class="inputbox"','value','text',$favTeamsList[$predictionProject->project_id]);

		// kann champion ausgewaehlt werden ?
        
		if ( $predictionProject->champ )
          {
          $disabled='';
          // ist �berhaupt das startdatum gesetzt ?
          if ( $predictionProject->start_date == '0000-00-00' )
          {
          $app->enqueueMessage(JText::_('COM_SPORTSMANAGEMENT_PRED_PREDICTION_NOT_EXISTING_STARTDATE'),'Error');  
          $disabled=' disabled="disabled" ';
          }
          else
          {
          // ist die saison beendet ?
          $predictionProjectSettings = sportsmanagementModelPrediction::getPredictionProject($predictionProject->project_id);
          $time=strtotime($predictionProject->start_date);
          $time += 86400; // Ein Tag in Sekunden
          $showDate=date("Y-m-d",$time);
          $thisTimeDate = sportsmanagementHelper::getTimestamp(date("Y-m-d H:i:s"),1,$predictionProjectSettings->timezone);
          $competitionStartTimeDate = sportsmanagementHelper::getTimestamp($showDate,1,$predictionProjectSettings->timezone);
          $tippAllowed =	( ( $thisTimeDate < $competitionStartTimeDate ) ) ;
		if (!$tippAllowed)
        {
            $disabled=' disabled="disabled" ';
            }
            else
            {
                $disabled=''; 
            }
          }
          
          //$predictionMembers[] = JHTML::_('select.option','0',JText::_('COM_SPORTSMANAGEMENT_GLOBAL_SELECT_PREDICTION_MEMBER_GROUP'),'value','text');
//			if ( $res = sportsmanagementModelPrediction::getPredictionGroupList())
//            {
//                $predictionMembers = array_merge($predictionMembers,$res);
//                }
//                
//			$lists['grouplist']=JHTML::_('select.genericList',$predictionMembers,'group_id','class="inputbox" '.$disabled.'onchange=""','value','text',$this->predictionMember->group_id);
//			unset($res);
//			unset($predictionMembers);
          
          
          
          if ( COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO )
            {
echo '<br />predictionuser view.html edit -> time <pre>~' . print_r($time,true) . '~</pre><br />';
echo '<br />predictionuser view.html edit -> showDate <pre>~' . print_r($showDate,true) . '~</pre><br />';
echo '<br />predictionuser view.html edit -> thisTimeDate <pre>~' . print_r($thisTimeDate,true) . '~</pre><br />';
echo '<br />predictionuser view.html edit -> competitionStartTimeDate <pre>~' . print_r($competitionStartTimeDate,true) . '~</pre><br />';
echo '<br />predictionuser view.html edit -> tippAllowed <pre>~' . print_r($tippAllowed,true) . '~</pre><br />';
echo '<br />predictionuser view.html edit -> disabled <pre>~' . print_r($disabled,true) . '~</pre><br />';
echo '<br />predictionuser view.html edit -> this->predictionProjectS <pre>~' . print_r($this->predictionProjectS,true) . '~</pre><br />';
            }
            
          }
          else
          {
          $disabled=' disabled="disabled" ';
          }
          
          $predictionMembers[] = JHTML::_('select.option','0',JText::_('COM_SPORTSMANAGEMENT_GLOBAL_SELECT_PREDICTION_MEMBER_GROUP'),'value','text');
			if ( $res = sportsmanagementModelPrediction::getPredictionGroupList())
            {
                $predictionMembers = array_merge($predictionMembers,$res);
                }
                
			$lists['grouplist']=JHTML::_('select.genericList',$predictionMembers,'group_id','class="inputbox" '.$disabled.'onchange=""','value','text',$this->predictionMember->group_id);
			unset($res);
			unset($predictionMembers);
          
					if (!isset($champTeamsList[$predictionProject->project_id]))
          {
          $champTeamsList[$predictionProject->project_id] = 0;
          }
          
					$lists['champ_tipp_disabled'][$predictionProject->project_id] = JHTML::_('select.genericList',$projectteams,'champ_tipp['.$predictionProject->project_id.']','class="inputbox"'.$disabled.'','value','text',$champTeamsList[$predictionProject->project_id]);
					$lists['champ_tipp_enabled'][$predictionProject->project_id] = JHTML::_('select.genericList',$projectteams,'champ_tipp['.$predictionProject->project_id.']','class="inputbox"'.$disabled.'','value','text',$champTeamsList[$predictionProject->project_id]);
					unset($projectteams);
				}
        
				$this->form = $this->get('form');
                $this->form->setValue('picture', null,$this->predictionMember->picture);	
			}
			else
			{
				$this->favTeams = $favTeamsList;
				$this->champTeams = $champTeamsList;
			}

			$this->lists = $lists;
            $this->tippallowed = $tippAllowed;
      
			// Set page title
			$pageTitle = JText::_('COM_SPORTSMANAGEMENT_PRED_USERS_TITLE');
            $this->headertitle = JText::_('COM_SPORTSMANAGEMENT_PRED_USERS_TITLE' );

			$this->document->setTitle($pageTitle);

			//parent::display($tpl);
		}
		else
		{
			JError::raiseNotice(500,JText::_('COM_SPORTSMANAGEMENT_PRED_PREDICTION_NOT_EXISTING'));
		}



	}

	/**
	 * sportsmanagementViewPredictionUser::_setPointsChartdata()
	 * 
	 * @param mixed $config
	 * @return
	 */
	function _setPointsChartdata($config)
	{
		require_once( JPATH_COMPONENT_SITE.DS."assets".DS."classes".DS."open-flash-chart".DS."open-flash-chart.php" );

		$data = sportsmanagementModelPredictionUsers::getPointsChartData();

    //echo 'data -> <pre> '.print_r($data,true).'</pre><br>';
    
		// Calculate Values for Chart Object
		$userpoints= array();		
		$round_labels = array();

		foreach( $data as $rw )
		{
			if (!$rw->points) $rw->points = 0;
			$userpoints[] = (int)$rw->points;
			$round_labels[] = $rw->roundcode;		
		}

		
		$chart = new open_flash_chart();
		$chart->set_bg_colour($config['bg_colour']);
		
	if(!empty($userpoints))
	{
		$bar = new $config['bartype_1']();
		$bar->set_values( $userpoints);	
		$bar->set_tooltip( JText::_('COM_SPORTSMANAGEMENT_PRED_USER_POINTS'). ": #val#" );
		$bar->set_colour( $config['bar1'] );
		$bar->set_on_show(new bar_on_show($config['animation_1'], $config['cascade_1'], $config['delay_1']));

		$chart->add_element( $bar );
	}
		//X-axis
		$x = new x_axis();
		$x->set_colours($config['x_axis_colour'], $config['x_axis_colour_inner']);
		$x->set_labels_from_array($round_labels);
		$chart->set_x_axis( $x );
		$x_legend = new x_legend( JText::_('COM_SPORTSMANAGEMENT_PRED_USER_ROUNDS') );
		$x_legend->set_style( '{font-size: 15px; color: #778877}' );
		$chart->set_x_legend( $x_legend );

		//Y-axis
		$y = new y_axis();
		$y->set_range( 0, @max($userpoints)+2, 1);
		$y->set_steps(round(@max($userpoints)/8));
		$y->set_colours($config['y_axis_colour'], $config['y_axis_colour_inner']);
		$chart->set_y_axis( $y );
		$y_legend = new y_legend( JText::_('COM_SPORTSMANAGEMENT_PRED_USER_POINTS') );
		$y_legend->set_style( '{font-size: 15px; color: #778877}' );
		$chart->set_y_legend( $y_legend );
		
		$this->assignRef( 'pointschartdata',  $chart);
	}

	/**
	 * sportsmanagementViewPredictionUser::_setRankingChartdata()
	 * 
	 * @param mixed $config
	 * @return
	 */
	function _setRankingChartdata($config)
	{
		require_once( JPATH_COMPONENT_SITE.DS."assets".DS."classes".DS."open-flash-chart".DS."open-flash-chart.php" );

		//$data = $this->get('RankChartData');		
		//some example data....fixme!!!
		$data_1 = array();
		$data_2 = array();

		for( $i=0; $i<6.2; $i+=0.2 )
		{
			$data_1[] = (sin($i) * 1.9) + 10;
		}

		for( $i=0; $i<6.2; $i+=0.2 )
		{
			$data_2[] = (sin($i) * 1.3) + 10;
		}
		
		$chart = new open_flash_chart();
		//***********
		
		//line 1
		$d = new $config['dotstyle_1']();
		$d->size((int) $config['line1_dot_strength']);
		$d->halo_size(1);
		$d->colour($config['line1']);
		$d->tooltip('Rank: #val#');

		$line = new line();
		$line->set_default_dot_style($d);
		$line->set_values( $data_1 );
		$line->set_width( (int) $config['line1_strength'] );
		///$line->set_key($team->name, 12);
		$line->set_colour( $config['line1'] );
		$line->on_show(new line_on_show($config['l_animation_1'], $config['l_cascade_1'], $config['l_delay_1']));
		$chart->add_element($line);
		
		//Line 2
		$d = new $config['dotstyle_2']();
		$d->size((int) $config['line2_dot_strength']);
		$d->halo_size(1);
		$d->colour($config['line2']);
		$d->tooltip('Rank: #val#');

		$line = new line();
		$line->set_default_dot_style($d);
		$line->set_values( $data_2);
		$line->set_width( (int) $config['line2_strength'] );
		//$line->set_key($team->name, 12);
		$line->set_colour( $config['line2'] );
		$line->on_show(new line_on_show($config['l_animation_2'], $config['l_cascade_2'], $config['l_delay_2']));
		$chart->add_element($line);
		
		//X-axis
		$x = new x_axis();
		$x->set_colours($config['x_axis_colour'], $config['x_axis_colour_inner']);
		//$x->set_labels_from_array($round_labels);
		$chart->set_x_axis( $x );
		$x_legend = new x_legend( JText::_('COM_SPORTSMANAGEMENT_PRED_USER_ROUNDS') );
		$x_legend->set_style( '{font-size: 15px; color: #778877}' );
		$chart->set_x_legend( $x_legend );

		//Y-axis
		$y = new y_axis();
		$y->set_range( 0, @max($data_1)+2, 1);
		$y->set_steps(round(@max($data_1)/8));
		$y->set_colours($config['y_axis_colour'], $config['y_axis_colour_inner']);
		$chart->set_y_axis( $y );
		$y_legend = new y_legend( JText::_('COM_SPORTSMANAGEMENT_PRED_USER_POINTS') );
		$y_legend->set_style( '{font-size: 15px; color: #778877}' );
		$chart->set_y_legend( $y_legend );
		
		$this->assignRef( 'rankingchartdata',  $chart);
	}
}
?>
<?php 

defined('_JEXEC') or die; 

//add js file
JHtml::_('behavior.framework');

//echo ' -><pre>'.print_r($this->opos,true).'</pre>';
?>
<?php echo $this->pagetitle; ?>
<div class="table-responsive">
<table class="<?php echo $this->config['table_class']; ?>">
	<tr class="sectiontableheader">
    <th class="name_row"><?php echo ''; ?></th>
		<th class="name_row"><?php echo JText::_('COM_SPORTSMANAGEMENT_RIVALS_RIVAL'); ?></th>
		<th class="match_row"><?php echo JText::_('COM_SPORTSMANAGEMENT_RIVALS_MATCHES'); ?></th>
		<th class="win_row"><?php echo JText::_('COM_SPORTSMANAGEMENT_RIVALS_WIN'); ?></th>
		<th class="tie_row"><?php echo JText::_('COM_SPORTSMANAGEMENT_RIVALS_DRAW'); ?></th>
		<th class="los_row"><?php echo JText::_('COM_SPORTSMANAGEMENT_RIVALS_LOS'); ?></th>
		<th class="goals_row"><?php echo JText::_('COM_SPORTSMANAGEMENT_RIVALS_TOTAL_GOALS'); ?></th>
	</tr>
	<?php
	$k=0;
	foreach ($this->opos as $opos => $v)
	{
	   
       //echo ' -><pre>'.print_r($v,true).'</pre><br>';
       
       //echo $v['name'].'</pre><br>';
       
		if( empty($v['name']) ) continue;
		$team = JArrayHelper::toObject($v);
        
        //echo ' -><pre>'.print_r($team,true).'</pre><br>';
        
		if(empty($team->id)) continue;
		?>
	<tr class="">
    <td class="">
    <?PHP
/**
* darstellung der verschiedenen bilder
*  
*/    
    switch ($this->config['show_picture'])
	{
    case 'logo_small':
    echo sportsmanagementModelProject::getClubIconHtml($team,1,0,'logo_small',JRequest::getInt('cfg_which_database',0),0 );
    break;
    case 'logo_middle':
    echo sportsmanagementModelProject::getClubIconHtml($team,1,0,'logo_middle',JRequest::getInt('cfg_which_database',0),0 );
    break;
    case 'logo_big':
    echo sportsmanagementModelProject::getClubIconHtml($team,1,0,'logo_big',JRequest::getInt('cfg_which_database',0),0 );
    break;
    case 'projectteam_picture':
    echo sportsmanagementHelper::getPictureThumb($team->projectteam_picture,$team->name,$this->config['picture_width'],'auto',1);
    break;
    case 'team_picture':
    echo sportsmanagementHelper::getPictureThumb($team->team_picture,$team->name,$this->config['picture_width'],'auto',1);
    break;
    case 'country_flag':
    echo JSMCountries::getCountryFlag($team->country_flag);
    break;
    case 'logo_small_country_flag':
    echo sportsmanagementModelProject::getClubIconHtml($team,1,0,'logo_small',JRequest::getInt('cfg_which_database',0),0 ).' '.JSMCountries::getCountryFlag($team->country_flag);
    break;
    case 'country_flag_logo_small':
    echo JSMCountries::getCountryFlag($team->country_flag).' '.sportsmanagementModelProject::getClubIconHtml($team,1,0,'logo_small',JRequest::getInt('cfg_which_database',0),0 );
    break;
    case 'logo_middle_country_flag':
    echo sportsmanagementModelProject::getClubIconHtml($team,1,0,'logo_middle',JRequest::getInt('cfg_which_database',0),0 ).' '.JSMCountries::getCountryFlag($team->country_flag);
    break;
    case 'country_flag_logo_middle':
    echo JSMCountries::getCountryFlag($team->country_flag).' '.sportsmanagementModelProject::getClubIconHtml($team,1,0,'logo_middle',JRequest::getInt('cfg_which_database',0),0 );
    break;
    case 'logo_big_country_flag':
    echo sportsmanagementModelProject::getClubIconHtml($team,1,0,'logo_big',JRequest::getInt('cfg_which_database',0),0 ).' '.JSMCountries::getCountryFlag($team->country_flag);
    break;
    case 'country_flag_logo_big':
    echo JSMCountries::getCountryFlag($team->country_flag).' '.sportsmanagementModelProject::getClubIconHtml($team,1,0,'logo_big',JRequest::getInt('cfg_which_database',0),0 );
    break;
    
    }
    ?>
    </td>
		<td class="">
        <?php 
		$isFavTeam = in_array( $team->id, explode(",",$this->project->fav_team) );
		// TODO: ranking deviates from the other views, regarding highlighting of the favorite team(s). Align this...
		$config['highlight_fav'] = $isFavTeam;
		echo sportsmanagementHelper::formatTeamName( $team, 'tr' . $k, $this->config, $isFavTeam );
		//echo $v['name'].'</pre><br>';
		?>
        </td>
		<td class="match_row"><?php echo $v['match']; ?></td>
		<td class="win_row"><?php echo $v['win']!=0 ? $v['win'] : 0; ?></td>
		<td class="tie_row"><?php echo $v['tie']!=0 ? $v['tie']: 0 ; ?></td>
		<td class="los_row"><?php echo $v['los']!=0 ? $v['los']: 0 ; ?></td>
		<td class="goals_row"><?php echo $v['g_for'].' '. $this->overallconfig['seperator'] .' '.$v['g_aga']; ?></td>
	</tr>
	<?php
	$k=1-$k;
	}
	?>
</table>
</div>
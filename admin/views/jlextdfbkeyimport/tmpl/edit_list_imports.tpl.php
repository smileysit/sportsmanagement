<?php defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

/* JoomLeague League Management and Prediction Game for Joomla!
 * Copyright (C) 2007  Robert Moss
 *  
 * Homepage: http://www.joomleague.de
 * Support: htt://www.joomleague.de/forum/
 * 
 * This file is part of JoomLeague.
 *  
 * JoomLeague is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * Please note that the GPL states that any headers in files and
 * Copyright notices as well as credits in headers, source files
 * and output (screens, prints, etc.) can not be removed.
 * You can extend them with your own credits, though...
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * The "GNU General Public License" (GPL) is available at
 * http://www.gnu.org/copyleft/gpl.html.
*/ 

?>

<div id="right">
	<div id="rightpad">
	
<!-- Header START -->
		<div id="step">
			<div class="t">
        <div class="t">
          <div class="t"></div>
        </div>
      </div>
      <div class="m" align="left">
				<div class="far-right">
							<div class="button1-left"><div class="help"><a href="http://www.joomleague.de/wiki" alt="<?php echo _COM_SPORTSMANAGEMENT_ADMIN_MANUAL;?>" title="<?php echo _COM_SPORTSMANAGEMENT_ADMIN_MANUAL;?>" target="blank"><?php echo _COM_SPORTSMANAGEMENT_ADMIN_MANUAL;?></a></div></div>
							<div class="button1-left"><div class="forum"><a href="http://www.joomleague.de/forum/" alt="<?php echo _COM_SPORTSMANAGEMENT_ADMIN_FORUM;?>" title="<?php echo _COM_SPORTSMANAGEMENT_ADMIN_FORUM;?>" target="blank"><?php echo _COM_SPORTSMANAGEMENT_ADMIN_FORUM;?></a></div></div>
							<div class="button1-left"><div class="about"><a href="http://www.joomleague.de" alt="<?php echo _COM_SPORTSMANAGEMENT_ADMIN_INFO;?>" title="<?php echo _COM_SPORTSMANAGEMENT_ADMIN_INFO;?>" target="blank"><?php echo _COM_SPORTSMANAGEMENT_ADMIN_INFO;?></a></div></div>
				</div>
				<div class="button1-left"><div class="blank"><a href="index2.php?option=com_joomleague"></a></div></div><span class="step"><?php echo _COM_SPORTSMANAGEMENT_ADMIN_EDIT_LIST_IMPORTS_LIST_IMPORTS;?></span>
			</div>
      <div class="b">
        <div class="b">
          <div class="b"></div>
        </div>
      </div>
    </div>
<!-- Header END -->     

    <div id="installer">
    
<!-- Title START -->    
			<div class="t">
        <div class="t">
          <div class="t"></div>
        </div>
      </div>
      <div class="m" align="left">
      	<h2><?php echo _COM_SPORTSMANAGEMENT_ADMIN_EDIT_LIST_IMPORTS_TITLE;?></h2>		
<!-- Titel END -->

<!-- Info START -->	 			
		<div id="step">
			<div class="t">
        <div class="t">
          <div class="t"></div>
        </div>
      </div>
      <div class="m" align="left">
<table class="content" cellpadding="4">
 <tr>
  <td><?php echo _COM_SPORTSMANAGEMENT_ADMIN_EDIT_LIST_IMPORTS_HINT1;?>  
  </td>
 </tr>
</table>
			</div>
      <div class="b">
        <div class="b">
          <div class="b"></div>
        </div>
      </div>
    </div>
<!-- Info END -->				

<!-- Content START -->		
				<div class="install-text2">
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist2">
  	<thead>
    <tr>  		
    <th width="20"><?php echo _COM_SPORTSMANAGEMENT_ADMIN_EDIT_LIST_IMPORTS_DATE;?></th>
    <th width="15%"><?php echo _COM_SPORTSMANAGEMENT_ADMIN_EDIT_LIST_IMPORTS_PROJECT;?></th>
    <th width="15%"><?php echo _COM_SPORTSMANAGEMENT_ADMIN_EDIT_LIST_IMPORTS_LINK;?></th>
    <th width="70%"><?php echo _COM_SPORTSMANAGEMENT_ADMIN_EDIT_LIST_IMPORTS_INFO;?></th>
  	</tr>
  	</thead>
    <tr>
    <td width="20" nowrap="nowrap">20.05.2007</td>
    <td width="15%" nowrap="nowrap">Fussball Bundesliga Saison 2006/2007 *</td>
    <td width="15%" nowrap="nowrap"><a href="<?php echo sprintf(COM_SPORTSMANAGEMENT_ADMIN_COMPONENT_LINK."&act=%s&id=1", $act);?>">jl_buli_06_07</a></td>
    <td width="70%" >Die erste Fussball Bundesliga mit allen Vereinen, Mannschaften, Spieltagen und Ergebnissen (bis zum Erstellungsdatum)<br />
    <b>Folgende Daten werden ersetzt:</b> Projekt mit ID1, Liga mit ID1, Saison mit ID1, Vereine und Mannschaften mit den IDs 1-18 und alle Spieltage und Spiele
    die Projekt ID1 zugewiesen sind. Wenn Du bereits eigene Daten angelegt hast, diesen Import bitte nicht verwenden!</td>
    </tr>
    <tr>
    <td width="20" nowrap="nowrap">28.06.2007</td>
    <td width="15%" nowrap="nowrap">Fussball Bundesliga Saison 2007/2008 *</td>
    <td width="15%" nowrap="nowrap"><a href="<?php echo sprintf(COM_SPORTSMANAGEMENT_ADMIN_COMPONENT_LINK."&act=%s&id=4", $act);?>">jl_buli_07_08</a></td>
    <td width="70%" >Die erste Fussball Bundesliga mit allen Vereinen, Mannschaften, Spieltagen und Ergebnissen.<br />
    <b>Voraussetzungen:</b> Import der Saison 2006/2007 <b>und</b> Import der Spielorte der 1. Fussball Bundesliga!<br />
    Dieser Import kann auch verwendet werden wenn schon eigene Projekte angelegt wurden, da die IDs vorher kontrolliert werden.</td>
    </tr>    
    <tr>
    <td width="20" nowrap="nowrap">23.02.2007</td>
    <td width="15%" nowrap="nowrap">Standard Ereignisse f�r Fussball Projekte</td>
    <td width="15%" nowrap="nowrap"><a href="<?php echo sprintf(COM_SPORTSMANAGEMENT_ADMIN_COMPONENT_LINK."&act=%s&id=2", $act);?>">jl_events</a></td>
    <td width="70%" nowrap="nowrap">Mit diesem Import werden einige Ereignistypen hinzugef�gt z.B. gelbe Karte, rote Karte usw.</td>
    </tr> 
    <tr>
    <td width="20" nowrap="nowrap">23.02.2007</td>
    <td width="15%" nowrap="nowrap">Schiedsrichter der 1. Fussball Bundesliga</td>
    <td width="15%" nowrap="nowrap"><a href="<?php echo sprintf(COM_SPORTSMANAGEMENT_ADMIN_COMPONENT_LINK."&act=%s&id=3", $act);?>">jl_referees</a></td>
    <td width="70%" nowrap="nowrap">Alle Schiedsrichter der Fussball Bundesliga (Stand: Februar 2007)</td>
    </tr>
    <tr>
    <td width="20" nowrap="nowrap">25.06.2007</td>
    <td width="15%" nowrap="nowrap">Spielorte der 1. Fussball Bundesliga</td>
    <td width="15%" nowrap="nowrap"><a href="<?php echo sprintf(COM_SPORTSMANAGEMENT_ADMIN_COMPONENT_LINK."&act=%s&id=5", $act);?>">jl_playgrounds</a></td>
    <td width="70%" nowrap="nowrap">Alle Spielorte der Fussball Bundesliga (Stand: Juni 2007)</td>
    </tr>
    <tr>
    <td width="20" nowrap="nowrap">13.07.2007</td>
    <td width="15%" nowrap="nowrap">L�nderdatenbank</td>
    <td width="15%" nowrap="nowrap"><a href="<?php echo sprintf(COM_SPORTSMANAGEMENT_ADMIN_COMPONENT_LINK."&act=%s&id=6", $act);?>">jl_countries</a></td>
    <td width="70%" nowrap="nowrap">Alle L�nder f�r die Verwendung in JoomLeague (Stand: Juni 2007)</td>
    </tr>

<tr>
    <td width="20" nowrap="nowrap">28.09.2007</td>
    <td width="15%" nowrap="nowrap">DFB-Schl�ssel</td>
    <td width="15%" nowrap="nowrap"><a href="<?php echo sprintf("index2.php?option=%s&act=%s&id=7",$option, $act);?>">jl_dfbkeys</a></td>
    <td width="70%" nowrap="nowrap">DFB-Schl�ssel f�r die Verwendung in JoomLeague </td>
    </tr>
    
    
    
		</table>
				</div>
<!-- Content END -->	
			
        <div class="clr"></div>
      </div>
      <div class="b">
        <div class="b">
          <div class="b"></div>
        </div>
      </div>
		</div>
	</div>
</div>
<div class="clr"></div>

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

defined('_JEXEC') or die('Restricted access');

//Ordering allowed ?
$ordering=($this->sortColumn == 'obj.ordering');

JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
$templatesToLoad = array('footer');
sportsmanagementHelper::addTemplatePaths($templatesToLoad, $this);
?>
	<div id="editcell">
		<table class="adminlist">
			<thead>
				<tr>
					<th width="5"><?php echo JText::_('COM_SPORTSMANAGEMENT_GLOBAL_NUM'); ?></th>
					<th width="20">
						<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
					</th>
					<th width="20">&nbsp;</th>
					<th>
						<?php
						echo JHtml::_('grid.sort','COM_SPORTSMANAGEMENT_ADMIN_EVENTS_STANDARD_NAME_OF_EVENT','obj.name',$this->sortDirection,$this->sortColumn);
						?>
					</th>
					<th>
						<?php echo JText::_('COM_SPORTSMANAGEMENT_ADMIN_EVENTS_TRANSLATION'); ?>
					</th>
					<th width="10%">
						<?php
						echo JHtml::_('grid.sort','COM_SPORTSMANAGEMENT_ADMIN_EVENTS_ICON','obj.icon',$this->sortDirection,$this->sortColumn);
						?>
					</th>
					<th width="10%">
						<?php
						echo JHtml::_('grid.sort','COM_SPORTSMANAGEMENT_ADMIN_EVENTS_SPORTSTYPE','obj.sports_type_id',$this->sortDirection,$this->sortColumn);
						?>
					</th>
					<th width="1%">
						<?php
						echo JHtml::_('grid.sort','JSTATUS','obj.published',$this->sortDirection,$this->sortColumn);
						?>
					</th>
					<th width="10%">
						<?php
						echo JHtml::_('grid.sort','JGRID_HEADING_ORDERING','obj.ordering',$this->sortDirection,$this->sortColumn);
						echo JHtml::_('grid.order',$this->items, 'filesave.png', 'eventtypes.saveorder');
						?>
					</th>
					<th width="5%">
						<?php
						echo JHtml::_('grid.sort','JGRID_HEADING_ID','obj.id',$this->sortDirection,$this->sortColumn);
						?>
					</th>
				</tr>
			</thead>
			<tfoot><tr><td colspan="6"><?php echo $this->pagination->getListFooter(); ?></td>
            <td colspan="4"><?php echo $this->pagination->getResultsCounter(); ?>
            
            </td>
            </tr></tfoot>
			<tbody>
				<?php
				$k=0;
				for ($i=0,$n=count($this->items); $i < $n; $i++)
				{
					$row=&$this->items[$i];

					$link=JRoute::_('index.php?option=com_sportsmanagement&task=eventtype.edit&id='.$row->id);
					$checked=JHtml::_('grid.checkedout',$row,$i);
					$published=JHtml::_('grid.published',$row,$i,'tick.png','publish_x.png','eventtype.');
					?>
					<tr class="<?php echo "row$k"; ?>">
						<td class="center"><?php echo $this->pagination->getRowOffset($i); ?></td>
						<td class="center"><?php echo $checked; ?></td>
						<?php
						if (JTable::isCheckedOut($this->user->get('id'),$row->checked_out))
						{
							$inputappend=' disabled="disabled"';
							?><td class="center">&nbsp;</td><?php
						}
						else
						{
							$inputappend='';
							?>
							<td class="center">
								<a href="<?php echo $link; ?>">
									<?php
									$imageTitle=JText::_('COM_SPORTSMANAGEMENT_ADMIN_EVENTS_EDIT_DETAILS');
									echo JHtml::_(	'image','administrator/components/com_sportsmanagement/assets/images/edit.png',
													$imageTitle, 'title= "'.$imageTitle.'"');
									?>
								</a>
							</td>
							<?php
						}
						?>
						<td><?php echo $row->name; ?></td>
						<td>
							<?php
							if ($row->name == JText::_($row->name))
							{
								echo '&nbsp;';
							}
							else
							{
								echo JText::_($row->name);
							}
							?>
						</td>
						<td class="center">
							<?php
							$desc=JText::_($row->name);
							echo sportsmanagementHelper::getPictureThumb($row->icon, $desc, 0, 21, 4);
							?>
						</td>
						<td class="center">
							<?php
							echo JText::_(sportsmanagementHelper::getSportsTypeName($row->sports_type_id));
							?>
						</td>
						<td class="center">
							<?php
							echo $published;
							?>
						</td>
						<td class="order">
							<span>
								<?php
								echo $this->pagination->orderUpIcon($i,$i > 0,'eventtypes.orderup','COM_SPORTSMANAGEMENT_GLOBAL_ORDER_UP',$ordering);
								?>
							</span>
							<span>
								<?php
								echo $this->pagination->orderDownIcon($i,$n,$i < $n,'eventtypes.orderdown','COM_SPORTSMANAGEMENT_GLOBAL_ORDER_DOWN',$ordering);
								?>
								<?php
								$disabled=true ? '' : 'disabled="disabled"';
								?>
							</span>
							<input	type="text" name="order[]" size="5" value="<?php echo $row->ordering;?>" <?php echo $disabled ?>
									class="text_area" style="text-align: center" />
						</td>
						<td class="center">
							<?php
							echo $row->id;
							?>
						</td>
					</tr>
					<?php
					$k=1 - $k;
				}
				?>
			</tbody>
		</table>
	</div>
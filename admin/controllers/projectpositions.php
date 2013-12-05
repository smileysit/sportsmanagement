<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 

class sportsmanagementControllerprojectpositions extends JControllerAdmin
{
	
  /**
	 * Method to store projectpositions
	 *
	 * @access	public
	 * @return	boolean	True on success
	 *
	 */
  function store()
	{
		$post = JRequest::get('post');
        // Check for request forgeries
		JRequest::checkToken() or die('JINVALID_TOKEN');

        $model = $this->getModel();
       $msg = $model->store($post);
        $this->setRedirect('index.php?option=com_sportsmanagement&view=close&tmpl=component',$msg);
	}
  
  
  /**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'Projectposition', $prefix = 'sportsmanagementModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	


	
}
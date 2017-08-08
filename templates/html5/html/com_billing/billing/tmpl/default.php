<?php defined('_JEXEC') or die('Restricted access'); 

$task = JRequest::getVar('task');
$id = JRequest::getInt('id');
$db = JFactory::getDBO();
$fieldid = JRequest::getVar('fieldid');
$user = JFactory::getUser();
$uid = $user->id;


if($task='checkuser')
{
	$username = strtolower(JRequest::getVar('username'));
	$query = "select count(*) from `#__users` where LOWER(username) = '$username'  or LOWER(email) = '$username'";
	$result = $db->setQuery($query);   
	$n = $db->loadResult();
	if($n > 0)
	{
		echo "<img src='/administrator/templates/bluestork/images/admin/icon-16-allow.png'>";
	}
	else
	{
		echo "<img src='/administrator/templates/bluestork/images/admin/icon-16-notice-note.png' title='not found' alt='not found'>";
	}
	return;
}

?>

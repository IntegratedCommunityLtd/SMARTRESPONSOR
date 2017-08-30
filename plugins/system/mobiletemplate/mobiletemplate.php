<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */


// no direct access
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.plugin.plugin' );

class  plgSystemMobileTemplate extends JPlugin
{
	public function onAfterInitialise()
	{
		$app = JFactory::getApplication();

		if($app->isAdmin()){
			return;
		}
		$mobile_style = 'iPhone|iPod|BlackBerry|Pre|Palm|Googlebot-Mobile|mobi|Safari Mobile|Windows Mobile|Android|Opera Mini|mobile';
        $mobile_array = explode('|',$mobile_style);
        $i =0;
        $filter = $this->params->get('filter',array());
        foreach($filter as $f_word){
            if (preg_match("/$f_word/i",$_SERVER['HTTP_USER_AGENT'])) {
                return;
			}
        }
	    foreach ($mobile_array as $moblie) {
			if (preg_match("/$moblie/i",$_SERVER['HTTP_USER_AGENT'])) {
				$i =1;
				break;
			}
		}
		if ($i ==0) return ;
		$template_name = $this->params->get('template_name','mobiletemplate');

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->qn('template'));
		$query->select($db->qn('params'));
		$query->from($db->qn('#__template_styles'));
		$query->where($db->qn('client_id'). ' = 0');
		$query->where($db->qn('template'). ' = "'.$template_name.'"');
		$query->order($db->qn('id'));
		$db->setQuery( $query );
		$row = $db->loadObject();
		if(!$row || empty($row->template)){
			return;
		}

		if(is_dir(JPATH_THEMES."/".$row->template)){
			$app->setTemplate($row->template, (new JRegistry($row->params)));
		}
	}
}

<?php
/**
 * SMFAQ
 *
 * @package		Component for Joomla 2.5.6+
 * @version		1.7.3
 * @copyright	(C)2009 - 2013 by SmokerMan (http://joomla-code.ru)
 * @license		GNU/GPL v.3 see http://www.gnu.org/licenses/gpl.html
 */

// защита от прямого доступа
defined('_JEXEC') or die('@-_-@');

jimport( 'joomla.application.component.view');

class SmfaqViewCategory extends JView
{
	function display($tpl = null)
	{
		$app = JFactory::getApplication();

		$document = JFactory::getDocument();

		$id = $app->input->get('id', null, 'int');
		$document->link = JRoute::_('index.php?option=com_smfaq&view=category&id=' . $id);

		$app->input->set('limit', $app->getCfg('feed_limit'));
		$siteEmail = $app->getCfg('mailfrom');
		$fromName = $app->getCfg('fromname');
		$document->editor = $fromName;
		$document->editorEmail = $siteEmail;

		// Get some data from the model
		$items		= $this->get( 'data' );
		$category	= $this->get( 'category' );
		$i = 1;
		foreach ( $items as $item )
		{
			// strip html from feed item title
			$title = $this->escape( $item->question );
			$title = html_entity_decode( $title );

			// url link to article
			$link = JRoute::_('index.php?option=com_smfaq&view=category&id='.$category->slug.'#p'.$item->id );
			$i++;
			// strip html from feed item description text
			$description = $item->answer;
			$date = ( $item->created ? date( 'r', strtotime($item->created) ) : '' );

			// load individual item creator class
			$feeditem = new JFeedItem();
			$feeditem->title 		= $title;
			$feeditem->link 		= $link;
			$feeditem->description 	= $description;
			$feeditem->date			= $date;
			$feeditem->category   	= $category->title;

			// loads item info into rss array
			$document->addItem( $feeditem );
		}
	}
}
?>

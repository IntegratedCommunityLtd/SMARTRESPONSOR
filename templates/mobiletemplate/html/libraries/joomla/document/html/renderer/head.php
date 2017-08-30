<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Document
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * JDocument head renderer
 *
 * @package     Joomla.Platform
 * @subpackage  Document
 * @since       11.1
 */
class JDocumentRendererHead extends JDocumentRenderer
{
	/**
	 * Renders the document head and returns the results as a string
	 *
	 * @param   string  $head     (unused)
	 * @param   array   $params   Associative array of values
	 * @param   string  $content  The script
	 *
	 * @return  string  The output of the script
	 *
	 * @since   11.1
	 *
	 * @note    Unused arguments are retained to preserve backward compatibility.
	 */
	public function render($head, $params = array(), $content = null)
	{
		ob_start();
		echo $this->fetchHead($this->_doc);
		$buffer = ob_get_contents();
		ob_end_clean();

		return $buffer;
	}

	/**
	 * Generates the head HTML and return the results as a string
	 *
	 * @param   JDocument  &$document  The document for which the head will be created
	 *
	 * @return  string  The head hTML
	 *
	 * @since   11.1
	 */
	public function fetchHead(&$document)
	{
		// Trigger the onBeforeCompileHead event (skip for installation, since it causes an error)
		$app = JFactory::getApplication();
		$app->triggerEvent('onBeforeCompileHead');
		// Get line endings
		$lnEnd = $document->_getLineEnd();
		$tab = $document->_getTab();
		$tagEnd = ' />';
		$buffer = '';

		// Generate base tag (need to happen first)
		$base = $document->getBase();
		if (!empty($base))
		{
			$buffer .= $tab . '<base href="' . $document->getBase() . '" />' . $lnEnd;
		}

		// Generate META tags (needs to happen as early as possible in the head)
		foreach ($document->_metaTags as $type => $tag)
		{
			foreach ($tag as $name => $content)
			{
				if ($type == 'http-equiv')
				{
					$content .= '; charset=' . $document->getCharset();
					$buffer .= $tab . '<meta http-equiv="' . $name . '" content="' . htmlspecialchars($content) . '" />' . $lnEnd;
				}
				elseif ($type == 'standard' && !empty($content))
				{
					$buffer .= $tab . '<meta name="' . $name . '" content="' . htmlspecialchars($content) . '" />' . $lnEnd;
				}
			}
		}

		// Don't add empty descriptions
		$documentDescription = $document->getDescription();
		if ($documentDescription)
		{
			$buffer .= $tab . '<meta name="description" content="' . htmlspecialchars($documentDescription) . '" />' . $lnEnd;
		}

		// Don't add empty generators
		$generator = $document->getGenerator();
		if ($generator)
		{
			/* $buffer .= $tab . '<meta name="generator" content="' . htmlspecialchars($generator) . '" />' . $lnEnd; */
		}

		$buffer .= $tab . '<title>' . htmlspecialchars($document->getTitle(), ENT_COMPAT, 'UTF-8') . '</title>' . $lnEnd;

		// Generate link declarations
		foreach ($document->_links as $link => $linkAtrr)
		{
		// Код отключения 
			$ex_src = explode("/",$strSrc); 
			$js_file_name = $ex_src[count($ex_src)-1];
			$js_to_ignore = array("favicon.ico");
			if( in_array($js_file_name,$js_to_ignore) AND substr_count($document->baseurl,"/administrator") < 1 AND $_GET['view'] != 'form')
			continue;
		// КОНЕЦ Код отключения
			$buffer .= $tab . '<link href="' . $link . '" ' . $linkAtrr['relType'] . '="' . $linkAtrr['relation'] . '"';
			if ($temp = JArrayHelper::toString($linkAtrr['attribs']))
			{
				$buffer .= ' ' . $temp;
			}
			$buffer .= ' />' . $lnEnd;
		}

		// Generate stylesheet links
		foreach ($document->_styleSheets as $strSrc => $strAttr)
		{
		// Код отключения css","modal.css","general.css","system.css","bootstrap.css","animate.css","font-awesome.css","font-socialico.css","template-turquoise.css","your_css.css","pattern.css","jquery.miniColors.css","responsive.css","responsive.css","glyphicon.css","shortcodes.css","chosen.css?vmver=8615","css?family=Arial,+sans"
			$ex_src = explode("/",$strSrc); 
			$js_file_name = $ex_src[count($ex_src)-1];
			$js_to_ignore = array("bootstrap.min.css","bootstrap-responsive.css","vm-ltr-common.css?vmver=8615","vm-ltr-site.css?vmver=8615","facebox.css?vmver=8615","vm-ltr-reviews.css?vmver=8615","jquery.fancybox-1.3.4.css?vmver=8615","modal.css","template.css","slogin.css","bundle.css","autocomplete.css","style.css","minitip.css","jquery-ui-1.9.2.custom.css","jquery-ui-timepicker-addon.css","classic.combined.css","frontediting.css");
			if( in_array($js_file_name,$js_to_ignore) AND substr_count($document->baseurl,"/administrator") < 1 AND $_GET['view'] != 'form')
			continue;
		// КОНЕЦ Код отключения css
			$buffer .= $tab . '<link rel="stylesheet" href="' . $strSrc . '" type="' . $strAttr['mime'] . '"';
			if (!is_null($strAttr['media']))
			{
				$buffer .= ' media="' . $strAttr['media'] . '" ';
			}
			if ($temp = JArrayHelper::toString($strAttr['attribs']))
			{
				$buffer .= ' ' . $temp;
			}
			$buffer .= $tagEnd . $lnEnd;
		}

		// Generate stylesheet declarations
		foreach ($document->_style as $type => $content)
		{
			$buffer .= $tab . '<style type="' . $type . '">' . $lnEnd;

			// This is for full XHTML support.
			if ($document->_mime != 'text/html')
			{
				$buffer .= $tab . $tab . '<![CDATA[' . $lnEnd;
			}

			$buffer .= $content . $lnEnd;

			// See above note
			if ($document->_mime != 'text/html')
			{
				$buffer .= $tab . $tab . ']]>' . $lnEnd;
			}
			$buffer .= $tab . '</style>' . $lnEnd;
		}

		// Generate script file links
		foreach ($document->_scripts as $strSrc => $strAttr)
		{
		// Код отключения js на сайте(в админке ничего не изменится)"caption.js","validate.js","less.min.js","yt-script.js","prettify.js","ytcpanel.js","jquery.miniColors.min.js","yt-extend.js","jquery.easing.1.3.js","jquery.megamenu.js","jquery.prettyPhoto.js","prettify.js","shortcodes.js","jquery.min.js",,
			$ex_src = explode("/",$strSrc);
			$js_file_name = $ex_src[count($ex_src)-1];
			$js_to_ignore = array("jquery-ui.min.js","jquery.ui.autocomplete.html.js","jquery.noconflict.js","jquery-noconflict.js","mootools-core.js","core.js","modal.js","mootools-more.js","mootools-core-uncompressed.js","core-uncompressed.js","modal-uncompressed.js","mootools-more-uncompressed.js","validate-uncompressed.js","vmsite.js?vmver=8615","facebox.js?vmver=8615","jquery.fancybox-1.3.4.pack.js?vmver=8615","vmprices.js?vmver=8615","bootstrap.min.js","jquery.min.js","slogin.js","script-1.2.min.js","window-1.0.min.js","joms.ajax.js","joms.jquery-1.8.1.min.js","tabs.js","stream.js","minitip-1.0.js","overlib_all_mini.js","jquery-ui-1.9.2.custom.js","jquery-ui-timepicker-addon.min.js","picker.combined.js","loader.js","ajax_1.5.pack.js","jquery-migrate.min.js","frontediting.js");
			if( in_array($js_file_name,$js_to_ignore) AND substr_count($document->baseurl,"/administrator") < 1 AND $_GET['view'] != 'form')
			continue;
		// КОНЕЦ Код отключения js на сайте(в админке ничего не изменится)
			$buffer .= $tab . '<script src="' . $strSrc . '"';
			if (!is_null($strAttr['mime']))
			{
				$buffer .= ' type="' . $strAttr['mime'] . '"';
			}
			if ($strAttr['defer'])
			{
				$buffer .= ' defer="defer"';
			}
			if ($strAttr['async'])
			{
				$buffer .= ' async="async"';
			}
			$buffer .= '></script>' . $lnEnd;
		}

		// Generate script declarations
		foreach ($document->_script as $type => $content)
		{
			$buffer .= $tab . '<script type="' . $type . '">' . $lnEnd;

			// This is for full XHTML support.
			if ($document->_mime != 'text/html')
			{
				$buffer .= $tab . $tab . '<![CDATA[' . $lnEnd;
			}

			$buffer .= $content . $lnEnd;

			// See above note
			if ($document->_mime != 'text/html')
			{
				$buffer .= $tab . $tab . ']]>' . $lnEnd;
			}
			$buffer .= $tab . '</script>' . $lnEnd;
		}

		// Generate script language declarations.
		if (count(JText::script()))
		{
			$buffer .= $tab . '<script type="text/javascript">' . $lnEnd;
			$buffer .= $tab . $tab . '(function() {' . $lnEnd;
			$buffer .= $tab . $tab . $tab . 'var strings = ' . json_encode(JText::script()) . ';' . $lnEnd;
			$buffer .= $tab . $tab . $tab . 'if (typeof Joomla == \'undefined\') {' . $lnEnd;
			$buffer .= $tab . $tab . $tab . $tab . 'Joomla = {};' . $lnEnd;
			$buffer .= $tab . $tab . $tab . $tab . 'Joomla.JText = strings;' . $lnEnd;
			$buffer .= $tab . $tab . $tab . '}' . $lnEnd;
			$buffer .= $tab . $tab . $tab . 'else {' . $lnEnd;
			$buffer .= $tab . $tab . $tab . $tab . 'Joomla.JText.load(strings);' . $lnEnd;
			$buffer .= $tab . $tab . $tab . '}' . $lnEnd;
			$buffer .= $tab . $tab . '})();' . $lnEnd;
			$buffer .= $tab . '</script>' . $lnEnd;
		}

		foreach ($document->_custom as $custom)
		{
			$buffer .= $tab . $custom . $lnEnd;
		}

		return $buffer;
	}
}

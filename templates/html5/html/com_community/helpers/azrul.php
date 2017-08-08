<?php
defined('_JEXEC') or die('Restricted access');

function getJomSocialPoweredByLink()
{
   	$powerBy = '';
	if (!COMMUNITY_PRO_VERSION) {
		$powerBy = '';
	}

	return $powerBy;
}

function checkFolderExist( $folderLocation )
{
	if( JFolder::exists( $folderLocation ) )
	{
		return true;
	}

	return false;
}
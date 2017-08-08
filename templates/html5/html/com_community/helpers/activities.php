<?php

/**
 * @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
 * @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author iJoomla.com <webmaster@ijoomla.com>
 * @url https://www.jomsocial.com/license-agreement
 * The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
 * More info at https://www.jomsocial.com/license-agreement
 */

defined('_JEXEC') or die('Restricted access');

Class CActivitiesHelper {

    static protected $_permission = array(0 => 'COM_COMMUNITY_PRIVACY_PUBLIC',
        10 => 'COM_COMMUNITY_PRIVACY_PUBLIC',
        PRIVACY_MEMBERS => 'COM_COMMUNITY_PRIVACY_SITE_MEMBERS',
        PRIVACY_FRIENDS => 'COM_COMMUNITY_PRIVACY_FRIENDS',
        PRIVACY_PRIVATE => 'COM_COMMUNITY_PRIVACY_ME'
    );
    static protected $_icons = array(0 => 'joms-icon-globe',
        10 => 'joms-icon-globe',
        PRIVACY_MEMBERS => 'joms-icon-users',
        PRIVACY_FRIENDS => 'joms-icon-user',
        PRIVACY_PRIVATE => 'joms-icon-lock'
    );
    static protected $_apps = array(
        /* Groups */
        'groups' => array(
            'groups.bulletin' => array(
                'comment' => true,
                'like' => true
            ),
            'groups.discussion' => array(
                'comment' => true,
                'like' => true
            ),
            'groups.wall' => array(
                'comment' => true,
                'like' => true
            ),
            'groups.featured' => array(
                'comment' => false,
                'like' => true
            )
        ),
        /* Photos */
        'photos' => array(
            'photos.comment' => array(
                'comment' => false,
                'like' => true
            )
        ),
        /* videos */
        'videos' => array(
            'videos.comment' => false
        ),
        /* Kunena */
        'kunena' => array(
            'kunena.post' => array(
                'comment' => false,
                'like' => true
            ),
            'kunena.reply' => array(
                'comment' => false,
                'like' => true
            ),
            'kunena.thankyou' => array(
                'comment' => false,
                'like' => true
            )
        )
    );

    /**
     * Get array of children' apps
     * @param string $key
     * @return array
     */
    public static function getAppChildren($key) {
        if (isset(self::$_apps[$key])) {
            $children = array();
            foreach (self::$_apps as $key => $data) {
                $children[] = $key;
            }
            return $children;
        }
    }

    /**
     *
     * @param type $app
     * @param type $action
     * @return boolean
     */
    public static function isActionAllowed($app, $action) {
        $parts = explode('.', $app);
        $allowed = true; /* By default we allowed this action */
        /* This app have no sub apps */
        if (count($parts) == 1) {
            /* Make sure this app exists */
            if (isset(self::$_apps[$parts[0]])) {
                /* Make sure this app' action exists */
                if (isset(self::$_apps[$parts[0]][$action])) {
                    $allowed = (self::$_apps[$parts[0]][$action]);
                }
            }
        } else {
            /* This app have sub apps */
            if (isset(self::$_apps[$parts[0]])) {
                /* Make sure this chilapp exists */
                if (isset(self::$_apps[$parts[0]][$app])) {
                    $allowed = (self::$_apps[$parts[0]][$app][$action]);
                }
            }
        }

        return $allowed;
    }

    static public function getStreamPermissionHTML($privacy, $actorId = NULL) {

        $my = CFactory::getUser();

        if (($my->id != $actorId && !is_null($actorId) ) && !COwnerHelper::isCommunityAdmin()) {
            return;
        }

        $html = '<span class="joms-share-meta joms-share-privacy">' . JText::_(self::$_permission[$privacy]) . '</span>';
        $html .= '<div class="joms-privacy-dropdown joms-stream-privacy">';
        $html .= '<button type="button" class="dropdown-toggle" data-value="" data-toggle="dropdown"><span class="dropdown-value"><i class="' . self::$_icons[$privacy] . '"></i></span><span class="dropdown-caret joms-icon-caret-down"></span></button>';
        $html .= '<ul class="dropdown-menu">';


        $permissions = self::$_permission;
        unset($permissions[0]);
        foreach ($permissions as $value => $permission) {
            $html .= '<li><a href="javascript:" data-option-value="' . $value . '"><i class="' . self::$_icons[$value] . '"></i><span>' . JText::_($permission) . '</span></a></li>';
        }

        $html .= '</ul></div>';

        return $html;
    }

    public static function hasTag($id, $message) {
        $pattern = '/@\[\[(\d+):([a-z]+):([^\]]+)\]\]/';
        preg_match_all($pattern, $message, $matches);

        if (isset($matches[1]) && count($matches[1]) > 0) {
            foreach ($matches[1] as $match) {
                if ($match == $id) {
                    return true;
                }
            }
        }

        return false;
    }

}

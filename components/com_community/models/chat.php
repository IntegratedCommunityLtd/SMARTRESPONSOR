<?php
/**
 * @copyright (C) 2016 iJoomla, Inc. - All rights reserved.
 * @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author iJoomla.com <webmaster@ijoomla.com>
 * @url https://www.jomsocial.com/license-agreement
 * The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
 * More info at https://www.jomsocial.com/license-agreement
 */
defined('_JEXEC') or die('Restricted access');

require_once ( JPATH_ROOT .'/components/com_community/models/models.php');

class CommunityModelChat extends JCCModel
{
    /**
     * Configuration data
     *
     * @var object	JPagination object
     **/
    var $_pagination	= '';

    /**
     * Configuration data
     *
     * @var object	JPagination object
     **/
    var $total			= '';

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get a list of user chat List
     * @param int $userid
     * @param bool $activeOnly if set to true, only get the list where the user hasn't read the message
     */
    public function getUserChatList($userid = 0, $activeOnly = false){

    }

    /**
     * Retrive the last x amount of message if specified, else we will retrieve from admin settings
     * @param $chatId
     * @param int $total
     * @param int $lastID
     * @return object
     */
    public function getLastChat($chatId, $total = 0, $lastID = 0){
        $my = CFactory::getUser();
        $db = JFactory::getDbo();
        //easy validation to see if this chat ID belongs to the right person
        $chatTable = JTable::getInstance('Chat', 'CTable');
        $chatTable->load($chatId);
        if($chatTable->to != $my->id && $chatTable->from != $my->id){
            return false;
        }

        $condition = "";
        if($lastID){
            $condition = " AND ".$db->quoteName('id')." < ".$db->quote($lastID)." ";
        }

        $limit = ($total) ? ' LIMIT 0,'.$total : '';

        //retrieve all the last x chat
        $query = "SELECT * FROM ".$db->quoteName('#__community_chat_reply')." WHERE "
            .$db->quoteName('chat_id')."=".$db->quote($chatId).$condition." ORDER BY id DESC ".$limit;

        $db->setQuery($query);

        $results = $this->formatResults($db->loadObjectList());
        return $results;
    }

    /**
     * Get chat id between two users
     * @param $userId
     * @param int $secondaryUserId
     * @return int
     */
    public function getChatIdByUser($userId, $secondaryUserId = 0){
        $db = JFactory::getDbo();
        $my = ($secondaryUserId) ? CFactory::getUser($secondaryUserId) : CFactory::getUser();
        $query = "SELECT id FROM ".$db->quoteName('#__community_chat')
            ." WHERE (".$db->quoteName('from')."=".$db->quote($userId)
            ." AND ".$db->quoteName('to')."=".$db->quote($my->id).") OR ("
            .$db->quoteName('from')."=".$db->quote($my->id)
            ." AND ".$db->quoteName('to')."=".$db->quote($userId).")";
        $db->setQuery($query);
        return $db->loadColumn();
    }

    public function formatResults($chatList){
        foreach($chatList as $chat){
            //@todo: we must cater files too
            $params = new CParameter($chat->params);
            $chat->msg = htmlentities($chat->msg);
            unset($chat->params);
        }

        return $chatList;

    }

    public function getChatId(){

    }

    /**
     * @param int $userId
     */
    public function getAllUnreadMessageWindow(){
        $db = JFactory::getDbo();
        $my = CFactory::getUser();

        $myChatIds = $this->getAllChatWindowsId();
        if(empty($myChatIds)){
            return;
        }
        $myChatWindows = implode(',',$myChatIds);
        $query = "SELECT chat_id FROM ".$db->quoteName('#__community_chat_reply')." WHERE "
            .$db->quoteName('chat_id')." IN (".$myChatWindows.") AND "
            .$db->quoteName('status')."=".$db->quote(1)." AND "
            .$db->quoteName('from')."<>".$db->quote($my->id)
            ." GROUP BY".$db->quoteName('chat_id');

        $db->setQuery($query);
        $results = $db->loadColumn();

        // key is the id of the chat_id and value is the total message that hasnt be read
        $chatWindowsInfo = array();
        foreach($results as $result){
            $chatWindowsInfo[$result] = (isset($chatWindowsInfo[$result])) ? ++$chatWindowsInfo[$result] : 1;
        }

        return $chatWindowsInfo;
    }

    /**
     * Will return all the chat windows id of specific users
     * @param int $userId
     * @return mixed
     */
    public function getAllChatWindowsId($userId = 0){
        $db = JFactory::getDbo();
        $user = ($userId) ? CFactory::getUser($userId) : CFactory::getUser();
        $query = "SELECT id FROM ".$db->quoteName('#__community_chat')
            ." WHERE ".$db->quoteName('from')."=".$db->quote($user->id)
            ." OR ".$db->quoteName('to')."=".$db->quote($user->id);
        $db->setQuery($query);
        $result = $db->loadColumn();
        return $result;
    }

    /**
     * set the chat status to 2(read)
     * @param $chatId
     * @return mixed
     */
    public function setMessageToRead($chatId){
        //when message is seen, set all the previous chat reply to 2
        $db = JFactory::getDbo();
        $query = "UPDATE ".$db->quoteName('#__community_chat_reply')." SET ".$db->quoteName('status')."=".$db->quote(2)
            . " WHERE ".$db->quoteName('id')."=".$db->quote($chatId->id)
            ." AND ".$db->quoteName('status')."=".$db->quote(1);
        return $db->execute($query);
    }

    /**
     * This function is used to retrieve the last message that might be a newly added message
     */
    public function getLiveMessage(){

        $my = CFactory::getUser();

        if(!$my->id){
            return false;
        }

        $db = JFactory::getDbo();

        //if there is an update, it should be something fresh between the time that is set in config
        $timeOut = CFactory::getConfig()->get('message_pooling_time_active') + 2;//add two second to the settings

        //we will set if there is an update in the session
        $session = JFactory::getSession();
        $lastPolling = $session->get('last_message_polling_time');
        if(!$lastPolling) {
            $session->set('last_message_polling_time', time());
        }elseif(($lastPolling + $timeOut) < time()){
            $session->set('last_message_polling_time', time());
        }else{
            return $session->get('last_message_results'); // we do not want to spam the db query
        }

        $intervalTimer = $timeOut * 2;

        //get a list of the chat that has been updated together with the changes
        $query = "SELECT * FROM ".$db->quoteName('#__community_chat_reply')." WHERE "
                .$db->quoteName('created_at')." > (NOW() - INTERVAL ".$intervalTimer." SECOND) AND "
                .$db->quoteName('to')."=".$db->quote($my->id);

        $db->setQuery($query);
        $results = $db->loadObjectList();
        $session->set('last_message_results', $results);

        if(count($results) > 0){
            return $this->formatResults($results);
        }else{
            return false;
        }
    }

    public function recallMessage($chatReplyId){
        $my = CFactory::getUser();
        //simple and straight forward validation
        $timeout = CFactory::getConfig()->get('message_recall_minutes');

        if(!$timeout){
            return false;
        }

        $db = JFactory::getDbo();

        $query = "SELECT id FROM ".$db->quoteName('#__community_chat_reply')." WHERE "
            .$db->quoteName('id')."=".$db->quote($chatReplyId)." AND "
            .$db->quoteName('created_at')." > (NOW() - INTERVAL ".$timeout." MINUTE) AND "
            .$db->quoteName('from')."=".$db->quote($my->id);
        $db->setQuery($query);
        $result = $db->loadColumn();

        if($result){ // if there exists such record, delete it immediately
            $query = "DELETE FROM ".$db->quoteName('#__community_chat_reply')." WHERE "
                .$db->quoteName('id')."=".$db->quote($chatReplyId);
            $db->setQuery($query);
            return $db->execute();
        }

        return false;
    }

    /**
     * All we need is the to which user since we can look for the chat Id within the db
     * @param $to
     * @param $message
     * @param $latestMessageId
     * @return if successfully added, true boolean will be returned otherwise false
     */
    public function addChat($to, $message, $latestMessageId = 0){
        $my = CFactory::getUser();
        $to = CFactory::getUser($to);

        //@todo if there are chat between, return the entire chat that is missing between

        // load the information if there is any, else create a new one to get the chat id
        $key = array('to'=>$to->id, 'from' => $my->id);
        $keyReversed = array('to'=>$my->id, 'from' => $to->id); // load the other way round
        $chatTable = JTable::getInstance('Chat','CTable');
        $chatTable->load($key);
        if(!$chatTable->id){
            //check in reversed list
            $chatTable = JTable::getInstance('Chat','CTable');
            $chatTable->load($keyReversed);
        }

        if(!$chatTable->id){
            $chatTable->bind($key);
            $chatTable->store();
        }

        if($chatTable->id){
            //add the message into chat reply
            $chatReplyTable = JTable::getInstance('ChatReply', 'CTable');
            $values = array(
                'msg' => $message,
                'status' => 1,
                'from' => $my->id,
                'to' => $to->id,
                'chat_id' => $chatTable->id
            );
            $chatReplyTable->bind($values);
            $chatReplyTable->store();

            //we will set if there is an update in the session
            $session = JFactory::getSession();
            $session->start();
            $session->set('hasChatUpdate', time());
        }

        return isset($chatReplyTable->id) ? $chatReplyTable->id : false;
    }

    public function addFile($chat){
        $mainframe = JFactory::getApplication();
        $jinput    = $mainframe->input;

        $file = $jinput->files->get('file', null, 'raw');
    }

    /*===================================
     * Group Chat functions
     ===================================*/

    public function createGroupChat($userIds){
        $my = CFactory::getUser();

        //lets create a new group chat for this
        $chatTable = JTable::getInstance('GroupChat','CTable');
        $chatTable->created_by = $my->id;
        $chatTable->store();

        if(!$chatTable->id){
            //something went wrong
            return false;
        }

        array_push($userIds, $my->id); // add own id into this list

        //if chat is created, create all the participants detail
        foreach($userIds as $id){
            $participantTable = JTable::getInstance('GroupChatParticipants', 'CTable');
            $participantTable->group_chat_id = $chatTable->id;
            $participantTable->user_id = $id;
            $participantTable->mute = false;
            $participantTable->mute_duration = false;
            $participantTable->store();
        }

        return $chatTable->id;

    }

    public function getAllGroupChatWindowsId($userId){
        $db = JFactory::getDbo();
        $user = ($userId) ? CFactory::getUser($userId) : CFactory::getUser();
        $query = "SELECT group_chat_id FROM ".$db->quoteName('#__community_group_chat_participants')
            ." WHERE ".$db->quoteName('user_id')."=".$db->quote($user->id)
            ." AND ".$db->quoteName('active')."=".$db->quote('1');
        $db->setQuery($query);
        $result = $db->loadColumn();
        return $result;
    }

    public function getLastGroupChat($chatId, $total = 0, $lastID = 0){
        $my = CFactory::getUser();
        $db = JFactory::getDbo();

        //check if the user really belongs to the group chat
        $result = $this->checkGroupParticipant($my->id, $chatId);
        if(!$result){
            return false;
        }

        $condition = "";
        if($lastID){
            $condition = " AND ".$db->quoteName('id')." < ".$db->quote($lastID)." ";
        }

        $limit = ($total) ? ' LIMIT 0,'.$total : '';

        //retrieve all the last x chat
        $query = "SELECT * FROM ".$db->quoteName('#__community_group_chat_reply')." WHERE "
            .$db->quoteName('group_chat_id')."=".$db->quote($chatId).$condition." ORDER BY id DESC ".$limit;

        $db->setQuery($query);

        $results = $this->formatResults($db->loadObjectList());
        return $results;
    }

    public function addGroupChat($groupChatId, $message, $latestMessageId){
        $my = CFactory::getUser();
        $db = JFactory::getDbo();

        //@todo if there are chat between, return the entire chat that is missing between

        //validate the user to see if he is the part of the group chat
        $result = $this->checkGroupParticipant($my->id, $groupChatId);
        if(!$result){
            return false;
        }

        //update the last update field
        $chatTable = JTable::getInstance('GroupChat','CTable');
        $chatTable->load($groupChatId);
        $chatTable->store();

        //add the message into chat reply
        $chatReplyTable = JTable::getInstance('GroupChatReply', 'CTable');
        $values = array(
            'msg' => $message,
            'status' => 1,
            'created_by' => $my->id,
            'group_chat_id' => $groupChatId
        );
        $chatReplyTable->bind($values);
        $chatReplyTable->store();

        //we will set if there is an update in the session
        $session = JFactory::getSession();
        $session->start();
        $session->set('hasChatUpdate', time());


        return isset($chatReplyTable->id) ? $chatReplyTable->id : false;
    }

    /**
     * Set current user last read message within this groupChat
     * @param $groupChatId
     * @return bool
     */
    public function setGroupMessageToRead($groupChatId){
        $my = CFactory::getUser();
        $db = JFactory::getDbo();

        //when a message is read by the current user, it should set the group param to all
        $chatTable = JTable::getInstance('GroupChat','CTable');
        $chatTable->load($groupChatId);

        //get latest group chat Id
        $query = "SELECT id FROM ".$db->quoteName('#__community_group_chat_reply')." WHERE "
            .$db->quoteName('group_chat_id')."=".$db->quote($groupChatId)." ORDER BY id DESC";

        $db->setQuery($query);
        $latestGroupChatId = $db->loadResult();

        if($latestGroupChatId){
            //we will set it in a param [user_id]:[last_group_reply_id_that_I_read]
            $chatTable->setReadBy($my->id, $groupChatId);
            $chatTable->store();

            return true;
        }

        return false;
    }

    /**
     * To get The group message seen info
     */
    public function getGroupMessageSeenInfo($groupChatId){
        $chatTable = JTable::getInstance('GroupChat','CTable');
        $chatTable->load($groupChatId);

        return $chatTable->getReadBy();
    }

    private function checkGroupParticipant($userId, $groupChatId, $ignoreActive = false){
        $db = JFactory::getDbo();
        //easy validation to see if this chat ID belongs to the right person
        $query = "SELECT id FROM ".$db->quoteName('#__community_group_chat_participants')
            ." WHERE ".$db->quoteName('user_id')."=".$db->quote($userId)
            ." AND ".$db->quoteName('group_chat_id')."=".$db->quote($groupChatId);

        $query .= (!$ignoreActive) ? " AND ".$db->quoteName('active')."=".$db->quote('1') : '';

        $db->setQuery($query);

        $result = $db->loadResult();
        if(!$result){
            return false;
        }

        return true;
    }

    /**
     * This function is used to retrieve the last group message that might be a newly added message
     */
    public function getLiveGroupMessage(){

        $my = CFactory::getUser();

        if(!$my->id){
            return false;
        }

        $db = JFactory::getDbo();

        //if there is an update, it should be something fresh between the time that is set in config
        $timeOut = CFactory::getConfig()->get('message_pooling_time_active') + 2;//add two second to the settings

        //we will set if there is an update in the session
        $session = JFactory::getSession();
        $lastPolling = $session->get('last_group_message_polling_time');
        if(!$lastPolling) {
            $session->set('last_group_message_polling_time', time());
        }elseif(($lastPolling + $timeOut) < time()){
            $session->set('last_group_message_polling_time', time());
        }else{
            return $session->get('last_group_message_results'); // we do not want to spam the db query
        }

        $intervalTimer = $timeOut * 2;

        //get a list of the chat that has been updated together with the changes
        $query = "SELECT * FROM ".$db->quoteName('#__community_group_chat_reply')." AS a JOIN "
            .$db->quoteName('#__community_group_chat_participants')." AS b on a.group_chat_id=b.group_chat_id WHERE a."
            .$db->quoteName('created_at')." > (NOW() - INTERVAL ".$intervalTimer." SECOND) AND b."
            .$db->quoteName('user_id')."=".$db->quote($my->id);

        $db->setQuery($query);
        $results = $db->loadObjectList();
        $session->set('last_group_message_results', $results);

        if(count($results) > 0){
            return $this->formatResults($results);
        }else{
            return false;
        }
    }

    public function addGroupChatUser($chatId, $userId){
        $user = CFactory::getUser();//get current user
        if(!CFactory::getUser()->authorise('community.send', 'chat.message.' . $user)){
            return false;
        };

        //we must check if the invitor is active in the chatId
        $participantTable = JTable::getInstance('GroupChatParticipants', 'CTable');

        if(!$participantTable->checkUserExists($chatId, $userId) || $chatId == $user->id){
            return false;
        }

        //valid user, add this user to the chat
        $participantTable->group_chat_id = $chatId;
        $participantTable->user_id = $userId;
        $participantTable->mute = false;
        $participantTable->mute_duration = false;
        $participantTable->store();

        return true;
    }

}

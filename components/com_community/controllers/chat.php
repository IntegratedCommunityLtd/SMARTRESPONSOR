<?php
/**
 * @copyright (C) 2016 iJoomla, Inc. - All rights reserved.
 * @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author iJoomla.com <webmaster@ijoomla.com>
 * @url https://www.jomsocial.com/license-agreement
 * The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
 * More info at https://www.jomsocial.com/license-agreement
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class CommunityChatController extends CommunityBaseController
{

    //this should be the main page for chat
    public function display($cacheable = false, $urlparams = false){
        $document = JFactory::getDocument();
        $viewType = $document->getType();
        $view = $this->getView('chat', '', $viewType);
        echo $view->get('display');

        $tmpl = new CTemplate();
        $config = CFactory::getConfig();
        $assets = CAssets::getInstance();
        $assets->addVariable('chat_page_list', $tmpl->fetch('chat/page-list', true));
        $assets->addVariable('chat_window', $tmpl->fetch('chat/window', true));
        $assets->addVariable('chat_polling_interval', $config->get('message_pooling_time_active'));
    }

    /**
     * @param $to
     * @param $message
     * @param $latestMessageId
     * @return either the chat id if successfully send through, else get a false
     */
    public function ajaxAddChat($to, $message, $latestMessageId = 0){

        if(!CFactory::getUser()->authorise('community.send', 'chat.message.' . $to)){
            die(json_encode(false));
        };

        $model = CFactory::getModel('chat');
        die(json_encode($model->addChat($to, $message, $latestMessageId)));
    }

    /**
     * Ping the server to find out if there is any new message for the current user.
     * If there is a new message, it will return the message information, same structure as getLastChat
     * OR return false if there is nothing new
     */
    public function ajaxPingChat(){
        $model = CFactory::getModel('chat');
        die(json_encode(
            array('single'=>$model->getLiveMessage(),
                'group'=>$model->getLiveGroupMessage()
            ))
        );
    }

    /**
     * Retrive the last x amount of message if specified, else we will retrieve from admin settings
     * @param $chatId
     * @param int $total
     * @param int $lastID
     */
    public function ajaxGetLastChat($chatId, $total = 0, $lastID = 0){
        $model = CFactory::getModel('chat');
        die(json_encode($model->getLastChat($chatId, $total, $lastID)));
    }

    /**
     * get current user and targeted user chat id
     * @param $userId
     * if there is nothing at all, it will be empty
     */
    public function ajaxGetChatId($userId){
        $model = CFactory::getModel('chat');
        die(json_encode($model->getChatIdByUser($userId)));
    }

    /**
     * Gets all the windows that is not read
     * Returns [chat_id] => Total Unread message
     */
    public function ajaxGetAllUnreadWindow(){
        $model = CFactory::getModel('chat');
        die(json_encode($model->getAllUnreadMessageWindow()));
    }

    /**
     * Gets all the chat windows from current user, with one message each
     * Returns all the chat windows with one latest chat info.
     * avatar = receiver avatar, chat_id = chat id
     */
    public function ajaxGetAllChatWindows(){
        $model = CFactory::getModel('chat');
        $my = CFactory::getUser();
        $results = $model->getAllChatWindowsId();
        $compiledResults = array(); // formatted result of the id key and the chat info
        if ($results) {
            foreach($results as $result){
                $chatInfo = $model->getLastChat($result, 1);
                $chatInfo = isset($chatInfo[0]) ? $chatInfo[0] : null;
                if ($chatInfo) {
                    $chatUser = CFactory::getUser($chatInfo->to == $my->id ? $chatInfo->from : $chatInfo->to);
                    $chatInfo->avatar = $chatUser->getAvatar();
                    $chatInfo->displayName = $chatUser->getDisplayName();
                }
                $compiledResults[] = $chatInfo;
            }
        }
        die(json_encode($compiledResults));
    }

    /**
     * This should be called when user focus into the chat window
     * @param $chatId
     */
    public function ajaxMessageSeen($chatId){
        //we will need a validation here
        $model = CFactory::getModel('chat');
        $model->setMessageToRead($chatId);
    }

    /**
     * Pass in the message id and that's it
     * @param $chatReplyId
     * @return true or false.
     */
    public function ajaxRecallMessage($chatReplyId){
        $model = CFactory::getModel('chat');
        die(json_encode($model->recallMessage($chatReplyId)));
    }

    public function ajaxAddFile(){

    }

    public function ajaxRemoveFile(){

    }

    public function test(){
        //temporary test function for alex, rudy
        $model = CFactory::getModel('chat');
        $model->addChat(404, '\n tet \'select \'');
    }

    /*===================================
     * Group Chat ajax functions
     ===================================*/

    /**
     * Allow current user to create a new group chat
     * @param $userid json
     * @return chat_id that is successfully created
     */
    public function ajaxCreateGroupChat($userIds){
        //we must make sure that this current user can only chat with respective users

        $userIds = json_decode($userIds);
        foreach($userIds as $userid){
            if(!CFactory::getUser()->authorise('community.send', 'chat.message.' . $userid)){
                die(json_encode(false));
            };
        }

        //let's create a group chat here
        $model = CFactory::getModel('chat');
        $result = $model->createGroupChat($userIds);
        die(json_decode($result));
    }

    /**
     * Gets all the chat windows from current user, with one message each
     * Returns all the chat windows with one latest chat info.
     * avatar = receiver avatar, chat_id = chat id
     */
    public function ajaxGetAllGroupChatWindows(){
        $model = CFactory::getModel('chat');
        $my = CFactory::getUser();
        $results = $model->getAllGroupChatWindowsId($my->id);
        $compiledResults = array(); // formatted result of the id key and the chat info
        if ($results) {
            foreach($results as $result){
                $chatInfo = $model->getLastGroupChat($result, 1);
                $chatInfo = isset($chatInfo[0]) ? $chatInfo[0] : null;
                if ($chatInfo) {
                    $chatUser = CFactory::getUser($chatInfo->user_id);
                    $chatInfo->avatar = $chatUser->getAvatar();
                    $chatInfo->displayName = $chatUser->getDisplayName();
                }
                $compiledResults[] = $chatInfo;
            }
        }
        die(json_encode($compiledResults));
    }

    /**
     * @param $to
     * @param $message
     * @param $latestMessageId
     * @return either the chat id if successfully send through, else get a false
     */
    public function ajaxAddGroupChat($groupChatId, $message, $latestMessageId = 0){
        $model = CFactory::getModel('chat');
        die(json_encode($model->addGroupChat($groupChatId, $message, $latestMessageId)));
    }

    /**
     * This should be called when user focus into the group chat window
     * @param $chatId
     */
    public function ajaxGroupMessageSeen($chatId){
        //we will need a validation here
        $model = CFactory::getModel('chat');
        $model->setGroupMessageToRead($chatId);
    }

    /**
     * @param $chatId
     * Will return the user id and the last seen message id for each users.
     */
    public function ajaxGroupChatSeenInfo($chatId){
        //we will need a validation here
        $model = CFactory::getModel('chat');
        json_encode($model->getGroupMessageSeenInfo($chatId));
    }

    /**
     * Used by the group chat owner to invite a user
     * @param $chatId
     * @param $userId
     */
    public function ajaxInviteGroupChatUser($chatId, $userId){
        //currently we will allow anyone to invite people into the group chat
        $model = CFactory::getModel('chat');
        json_encode($model->addGroupChatUser($chatId, $userId));
    }

    public function ajaxLeaveGroup($userId, $chatId){

    }
}


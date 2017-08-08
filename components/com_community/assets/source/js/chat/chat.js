(function( $, _, Backbone, factory ) {

    joms.onStart(function() {
        var Chat = factory( $, _, Backbone );
        var chat = new Chat();
    });

})( joms_libs.$, joms_libs._, joms_libs.Backbone, function( $, _, Backbone ) {

/**
 * Conversation header view.
 * @class {Backbone.View} HeaderView
 */
var HeaderView = Backbone.View.extend({

    el: '.joms-js--chat-header',

    events: {
        'click .joms-button--neutral': 'selectorShow',
        'keyup .joms-input': 'selectorHideOnEscape',
        'blur .joms-input': 'selectorHideOnBlur',
        'mousedown .joms-js--chat-header-selector-div a': 'selectorSelect'
    },

    initialize: function() {
        this.$info = this.$('.joms-js--chat-header-info');
        this.$recipients = this.$info.find('.joms-chat__recipents');
        this.$selector = this.$('.joms-js--chat-header-selector');
        this.$selectorInput = this.$selector.find('.joms-input');
        this.$selectorDiv = this.$selector.find('.joms-js--chat-header-selector-div');

        joms_observer.add_action('chat_buddies_update', this.selectorRender, 10, 1, this );
        joms_observer.add_action('chat_conversation_open', this.conversationOpen, 10, 1, this );
    },

    /**
     * Render friend selector.
     * @param {object} buddies
     */
    selectorRender: _.throttle(function( buddies ) {
        this.buddies = buddies;
        this.$selectorDiv.empty();
        _.each( buddies, function( buddy ) {
            this.$selectorDiv.append([
                '<a href="#" data-id="', buddy.id, '" style="display:block;padding:5px">',
                '<span>', buddy.name , '</span>',
                '</a>'
            ].join(''));
        }, this );
    }, 1000 ),

    /**
     * Show new chat selector.
     * @params {HTMLEvent} e
     */
    selectorShow: function( e ) {
        e.preventDefault();
        e.stopPropagation();
        this.$info.hide();
        this.$selector.show();
        this.$selectorInput.val('').focus();
    },

    /**
     * Hide new chat selector.
     */
    selectorHide: function() {
        this.$selectorInput.val('');
        this.$selector.hide();
        this.$info.show();
    },

    /**
     * Hide new chat selector if Esc key is pressed.
     * @params {HTMLEvent} e
     */
    selectorHideOnEscape: function( e ) {
        if ( e.which === 27 /* Esc key */ ) {
            this.selectorHide();
        }
    },

    /**
     * Hide new chat selector on input blur.
     * @params {HTMLEvent} e
     */
    selectorHideOnBlur: function( e ) {
        this.selectorHide();
    },

    /**
     * Create new conversation with friend.
     * @params {HTMLEvent} e
     */
    selectorSelect: function( e ) {
        var $el = $( e.currentTarget );
        joms_observer.do_action('chat_header_select', $el.data('id') );
    },

    /**
     * Render header on conversation open.
     * @params {number} userId
     */
    conversationOpen: function( userId ) {
        var buddy = this.buddies[ userId ],
            html = '<a href="#" data-id="##id##">Unknown</a>';

        if ( buddy ) {
            html = html.replace('Unknown', buddy.name ).replace('##id##', buddy.id );
        }

        this.$recipients.html( html );
    }
});

/**
 * Conversation sidebar view.
 * @class {Backbone.View} SidebarView
 */
var SidebarView = Backbone.View.extend({

    el: '.joms-chat__conversations-wrapper',

    events: {
        'click .joms-chat__item': 'itemSelect',
    },

    initialize: function() {
        this.$loading = this.$('.joms-js-loading');
        this.$list = this.$('.joms-js-list');
        this.$notice = this.$('.joms-js-notice');

        joms_observer.add_action('chat_user_login', this.userLogin, 10, 1, this );
        joms_observer.add_action('chat_user_logout', this.userLogout, 10, 1, this );
        joms_observer.add_action('chat_conversation_update', this.itemAddAll, 10, 1, this );
        joms_observer.add_action('chat_conversation_open', this.conversationOpen, 10, 1, this );
        joms_observer.add_action('chat_message_sending', this.messageSending, 10, 2, this );
    },

    /**
     * Update sidebar on login event.
     */
    userLogin: function() {
        this.$loading.hide();
        this.$notice.hide();
        this.$list.show();
    },

    /**
     * Update sidebar on logout event.
     */
    userLogout: function() {
        this.$loading.hide();
        this.$list.hide();
        this.$notice.show();
    },

    /**
     * Render all conversation items.
     * @param {object[]} data
     */
    itemAddAll: function( data ) {
        this.$list.empty();
        if ( _.isArray( data ) ) {
            _.each( data, this.itemAddOne, this );
        }
    },

    /**
     * Render a conversation item.
     * @param {object} data
     */
    itemAddOne: function( data ) {
        var template, html;
        if ( _.isObject( data ) ) {
            template = typeof window.joms_vars.chat_page_list === 'string' && window.joms_vars.chat_page_list || '';
            html = template
                .replace(/##user_id##/g, data.user_id )
                .replace(/##chat_id##/g, data.chat_id )
                .replace(/##name##/g, data.displayName )
                .replace(/##avatar##/g, data.avatar )
                .replace(/##message##/g, data.msg )
                .replace(/##unread##/g, 'unread');
            this.$list.append( html );
        }
    },

    /**
     * Show particular conversation item.
     * @param {HTMLEvent} e
     */
    itemSelect: function( e ) {
        var $item = $( e.currentTarget ),
            userId = $item.data('user-id'),
            chatId = $item.data('chat-id');

        e.preventDefault();
        e.stopPropagation();

        this.itemSetActive( $item );
        joms_observer.do_action('chat_sidebar_select', userId, chatId );
    },

    /**
     * Set active item on conversation open.
     * @param {jQuery} $item
     */
    itemSetActive: function( $item ) {
        $item.siblings('.active').removeClass('active');
        $item.removeClass('unread').addClass('active');
    },

    /**
     * Handle open conversation.
     * @param {number} userId
     */
    conversationOpen: function( userId ) {
        var $item = this.$list.find('.joms-js--chat-item-user-' + userId );
        if ( $item.length ) {
            this.itemSetActive( $item );
        }
    },

    /**
     * Handle send message.
     * @param {object} message
     * @param {object} active
     */
    messageSending: function( message, active ) {
        var $item;
        if ( active && active.user_id ) {
            $item = this.$list.find('.joms-js--chat-item-user-' + active.user_id );
            if ( $item.length ) {
                $item.find('.joms-js--chat-item-msg').text( message );
            }
        }
    }

});

/**
 * Conversation messages view.
 * @class {Backbone.View} MessagesView
 */
var MessagesView = Backbone.View.extend({

    el: '.joms-chat__messages',

    events: {
        'click a.joms-js-close': 'messageRecall'
    },

    initialize: function( config ) {
        this.$loading = this.$('.joms-js--chat-conversation-loading');
        this.$messages = this.$('.joms-js--chat-conversation-messages');

        joms_observer.add_action('chat_conversation_open', this.render, 10, 2, this );
        joms_observer.add_action('chat_messages_loading', this.messagesLoading, 10, 1, this );
        joms_observer.add_action('chat_messages_loaded', this.messagesLoaded, 10, 3, this );
        joms_observer.add_action('chat_messages_received', this.messagesReceived, 10, 2, this );
        joms_observer.add_action('chat_message_sending', this.messageSending, 10, 4, this );
        joms_observer.add_action('chat_message_sent', this.messageSent, 10, 2, this );

    },

    render: function() {
        this.$messages.empty().hide();
    },

    messagesLoading: function() {
        this.$messages.hide();
        this.$loading.show();
    },

    messagesLoaded: function( data, me, buddy ) {
        this.$loading.hide();
        this.$messages.show();

        if ( _.isArray( data ) ) {
            data.reverse();
            data = _.map( data, function( item ) {
                var user;
                if ( +item.from === me.id ) {
                    user = $.extend({}, me, { name: 'You' });
                } else {
                    user = $.extend({}, buddy );
                }
                return this.messageFormat( item.id, item.msg, user );
            }, this );
            this.$messages.prepend( data.join('') );
            this.scrollToBottom();
        }
    },

    messagesReceived: function( data, buddy ) {
        if ( _.isArray( data ) && data.length ) {
            data = _.map( data, function( item ) {
                var user = $.extend({}, buddy );
                return this.messageFormat( item.id, item.msg, user );
            }, this );
            this.$messages.append( data.join('') );
            this.scrollToBottom();
        }
    },

    messageFormat: function( id, message, user, timestamp ) {
        id = id ? ( ' data-id="' + id + '"' ) : '';
        timestamp = timestamp ? ( ' joms-js-message-ts' + timestamp ) : '';

        return [
            '<div class="joms-chat__message-item', timestamp, '"', id, '>',
                '<div class="joms-avatar">',
                    '<img src="', user.avatar,'" alt="', '">',
                '</div>',
                '<div class="joms-chat__message-body">',
                    '<a href="#">', user.name, '</a>',
                    '<span>', message, '</span>',
                '</div>',
                '<div class="joms-chat__message-actions">',
                    '<a href="#" class="joms-js-close">',
                        '<svg viewBox="0 0 16 16" class="joms-icon">',
                            '<use xlink:href="#joms-icon-close"></use>',
                        '</svg>',
                    '</a>',
                '</div>',
            '</div>'
        ].join('');
    },

    messageAppend: function( message, me, timestamp ) {
        var html = this.messageFormat( null, message, me, timestamp );
        this.$messages.append( html );
    },

    messageSending: function( message, active, me, timestamp ) {
        this.messageAppend( message, $.extend({}, me, { name: 'You' }), timestamp );
        this.scrollToBottom();
    },

    messageSent: function( id, timestamp ) {
        var className = 'joms-js-message-ts' + timestamp,
            $message = this.$messages.children('.' + className );

        $message.removeClass( className ).attr('data-id', id );
    },

    messageRecall: function( e ) {
        var $item = $( e.currentTarget ).closest('.joms-chat__message-item'),
            msgId = $item.data('id');

        e.preventDefault();
        e.stopPropagation();

        if ( msgId ) {
            $item.remove();
            joms_observer.do_action('chat_message_recall', msgId );
        }
    },

    scrollToBottom: function() {
        var div = this.$messages[0];
        div.scrollTop = div.scrollHeight;
    }

});

/**
 * Conversation message postbox.
 * @class {Backbone.View} MessageBox
 */
var MessageBox = Backbone.View.extend({

    el: '.joms-chat__messagebox',

    events: {
        'keyup textarea': 'messageSendOnEnter'
    },

    initialize: function() {
        this.$textarea = this.$('textarea');

        joms_observer.add_action('chat_conversation_open', this.render, 10, 2, this );
    },

    render: function() {
        this.$textarea.removeAttr('disabled');
        this.$textarea.val('').focus();
    },

    messageSendOnEnter: function( e ) {
        if ( !e.shiftKey && e.which === 13 /* Enter key */ ) {
            joms_observer.do_action('chat_messagebox_send', $.trim( this.$textarea.val() ));
            this.$textarea.val('');
        }
    }

});

/**
 * Conversation main class.
 * @class Chat
 */
function Chat() {
    this.init();
}

Chat.prototype = {

    /**
     * Current user information.
     */
    me: { id: 0, name: '', avatar: '' },

    /**
     * Buddy list.
     */
    buddies: {},

    /**
     * Conversation list.
     * @type {object[]}
     */
    conversations: [],

    /**
     * Active convesation.
     */
    active: {},

    /**
     * Chat initialization.
     */
    init: function() {
        this.render();

        var id = +window.joms_my_id;
        if ( !id ) {
            joms_observer.do_action('chat_user_logout');
            return false;
        }

        this.me.id = id;
        joms_observer.do_action('chat_user_login');

        joms_observer.add_action('chat_header_select', this.conversationOpen, 10, 1, this );
        joms_observer.add_action('chat_sidebar_select', this.conversationOpen, 10, 2, this );
        joms_observer.add_action('chat_messagebox_send', this.messageSend, 10, 1, this );
        joms_observer.add_action('chat_message_recall', this.messageRecall, 10, 1, this );

        this.conversationListUpdate().then( this.friendListUpdate ).then( this.conversationPing );
    },

    render: function() {
        // initialize views
        var header = new HeaderView();
        var sidebar = new SidebarView();
        var messages = new MessagesView();
        var messageBox = new MessageBox();
    },

    /**
     * Get list of conversation by current user.
     * @returns jQuery.Deferred
     */
    conversationListUpdate: function() {
        return $.Deferred( $.proxy(function( defer ) {
            joms.ajax({
                func: 'chat,ajaxGetAllChatWindows',
                callback: $.proxy(function( json ) {
                    this.conversations = _.filter( json, _.isObject ); // filter-out on object input
                    _.each( this.conversations, function( conv ) {
                        conv.user_id = +conv.to === this.me.id ? +conv.from : +conv.to; // add additional data
                        this.buddyAdd( conv.user_id, conv.displayName, conv.avatar );
                    }, this );
                    joms_observer.do_action('chat_conversation_update', this.conversations );
                    joms_observer.do_action('chat_buddies_update', this.buddies );
                    defer.resolveWith( this, [ json ]);
                }, this  )
            });
        }, this  ));
    },

    /**
     * Open conversation with specific user.
     * @param {number} userId
     * @param {number} [chatId]
     * @returns jQuery.Deferred
     */
    conversationOpen: function( userId, chatId ) {
        return $.Deferred( $.proxy(function( defer ) {
            // exit if opened chat is currently active
            if ( this.active.user_id === userId ) {
                defer.resolveWith( this );
                return;
            }
            // update active conversation
            this.active = { user_id: userId };
            // notify child views
            joms_observer.do_action('chat_conversation_open', userId );
            // get chat id
            this.conversationGetId( userId, chatId ).done( $.proxy(function( chatId ) {
                if ( chatId ) {
                    // update active conversation
                    $.extend( this.active, { chat_id: chatId });
                    // get previous messages
                    this.conversationGetPrevMessages( chatId ).done( $.proxy(function( json ) {
                        defer.resolveWith( this );
                    }, this ));
                }
            }, this ));
        }, this  ));
    },

    /**
     * Get chat id for conversation with particular user.
     * @param {number} userId
     * @param {number} [chatId]
     * @returns jQuery.Deferred
     */
    conversationGetId: function( userId, chatId ) {
        return $.Deferred( $.proxy(function( defer ) {
            if ( chatId ) {
                defer.resolveWith( this, [ chatId ]);
            } else {
                joms.ajax({
                    func: 'chat,ajaxGetChatId',
                    data: [ userId ],
                    callback: $.proxy(function( json ) {
                        if ( json && _.isArray( json ) ) {
                            defer.resolveWith( this, [ json[0] ]);
                        } else {
                            defer.rejectWith( this );
                        }
                    }, this  )
                });
            }
        }, this ));
    },

    /**
     * Get conversation messages before specific message defined it's ID.
     * @param {number} chatId
     * @param {number} [lastMessageId]
     * @returns jQuery.Deferred
     */
    conversationGetPrevMessages: function( chatId, lastMessageId ) {
        return $.Deferred( $.proxy(function( defer ) {
            joms_observer.do_action('chat_messages_loading');
            joms.ajax({
                func: 'chat,ajaxGetLastChat',
                data: [ chatId, 10, lastMessageId || 0 ],
                callback: $.proxy(function( json ) {
                    if ( _.isArray( json ) ) {
                        joms_observer.do_action('chat_messages_loaded', json, this.me, this.buddyGet( this.active.user_id ) );
                    }
                    defer.resolveWith( this, [ json ]);
                }, this  )
            });
        }, this  ));
    },

    /**
     * Ping server for any update on conversations.
     */
    conversationPing: function() {
        var interval = Math.max( 1, +joms.chat_polling_interval || 10 );
        this._conversationPing().done( $.proxy(function( unreads ) {
            if ( unreads && unreads.length ) {
                unreads = _.filter( unreads, $.proxy(function( item ) {
                    return +this.active.chat_id === +item.chat_id;
                }, this ));
                joms_observer.do_action('chat_messages_received', unreads, this.buddyGet( this.active.user_id ) );
            }
            setTimeout( $.proxy(function() {
                this.conversationPing();
            }, this ), interval * 1000 );
        }, this ));
    },

    _conversationPing: function() {
        return $.Deferred( $.proxy(function( defer ) {
            joms.ajax({
                func: 'chat,ajaxPingChat',
                callback: $.proxy(function( json ) {
                    defer.resolveWith( this, [ json ]);
                }, this  )
            });
        }, this  ));
    },

    /**
     * Sends message.
     * @param {string} message
     * @returns jQuery.Deferred
     */
    messageSend: function( message ) {
        return $.Deferred( $.proxy(function( defer ) {
            if ( ! this.active.user_id ) {
                defer.rejectWith( this );
                return;
            }

            var now = ( new Date() ).getTime();

            joms_observer.do_action('chat_message_sending', message, this.active, this.me, now );
            joms.ajax({
                func: 'chat,ajaxAddChat',
                data: [ this.active.user_id, message ],
                callback: $.proxy(function( json ) {
                    joms_observer.do_action('chat_message_sent', json, now );
                    defer.resolveWith( this, [ json ]);
                }, this  )
            });
        }, this  ));
    },

    /**
     * Recall sent message.
     * @param {number} msgId
     * @returns jQuery.Deferred
     */
    messageRecall: function( msgId ) {
        return $.Deferred( $.proxy(function( defer ) {
            joms.ajax({
                func: 'chat,ajaxRecallMessage',
                data: [ msgId ],
                callback: $.proxy(function( json ) {
                    defer.resolveWith( this, [ json ]);
                }, this  )
            });
        }, this  ));
    },


    /**
     * Naively get friend list from `window.joms_friends` value.
     * @returns jQuery.Deferred
     */
    friendListUpdate: function() {
        return $.Deferred( $.proxy(function( defer ) {
            var timer = setInterval( $.proxy(function() {
                if ( !_.isUndefined( window.joms_friends ) ) {
                    clearInterval( timer );
                    _.each( window.joms_friends, function( friend ) {
                        var id = +friend.id;
                        if ( id === this.me.id ) { // save as user information
                            $.extend( this.me, { name: friend.name, avatar: friend.avatar });
                        } else { // save in buddy list
                            this.buddyAdd( friend.id, friend.name, friend.avatar );
                        }
                    }, this );
                    joms_observer.do_action('chat_me_update', this.me );
                    joms_observer.do_action('chat_buddies_update', this.buddies );
                    defer.resolveWith( this, [ window.joms_friends ]);
                }
            }, this ), 1000 );
        }, this  ));
    },

    /**
     * Add buddy list.
     * @param {number} id
     * @param {string} name
     * @param {string} avatar
     */
    buddyAdd: function( id, name, avatar ) {
        id = +id;
        this.buddies[ id ] = {
            id: id,
            name: name,
            avatar: avatar
        };
    },

    /**
     * Get buddy info by it's ID. Or all buddy list if ID is omitted.
     * @param {number} id
     * @returns {object|boolean}
     */
    buddyGet: function( id ) {
        if ( _.isUndefined( id ) ) {
            return this.buddies;
        }
        if ( this.buddies[ id ] ) {
            return this.buddies[ id ];
        }
        return false;
    }

};

return Chat;

});

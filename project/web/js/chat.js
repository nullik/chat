function Chat(selector, channelName) {
    var self = this;
    this.jqueryObj = $(selector);
    this.usersListObj = $(selector + ' #usersList');
    this.chatBox = $(selector + ' #chatBox');
    this.sendMessageBtn = $(selector + ' #sendMessageBtn');
    this.messageInput = $(selector + ' #messageInput');

    this.updateInterval = 1000;
    this.channelName = channelName;
    this.interval = null;

    this.sendMessageBtn.click(function() {
        self.sendMsg();
    });
    $(document).keypress(function(e) {
        if (e.which == 13) {
            self.sendMsg();
        }
    });

    this.messageContextMenu = new ChatContextMenu('#messageContextMenu');
    this.messageContextMenu.sendPrivateMsgCallback(function(clickedObj) { self.addReceiverFromMsg(clickedObj); });
    this.messageContextMenu.deleteMsgCallback(function(clickedObj) { self.deleteMsg(clickedObj); });

    this.usersListContextMenu = new ChatContextMenu('#usersListContextMenu');
    this.usersListContextMenu.sendPrivateMsgCallback(function(clickedObj) {
        self.addReceiverFromUsersList(clickedObj);
    });

    this.run = function() {
        self.update();
        this.interval = setInterval(self.update, this.updateInterval);
    }

    this.stop = function() {
        if (this.interval)
            clearInterval(this.interval);
    }

    this.update = function() {
        $.ajax({
            url: '/channels/' + this.channelName + '/get-data',
            method: 'GET',
            async: true,
            success: function(data) {
                var jsonData = JSON.parse(data);
                if (jsonData) {
                    if (jsonData['usersList']) {
                        self.updateUsersList(jsonData['usersList']);
                    }
                    if (jsonData['messages'])
                        self.updateChatBox(jsonData['messages']);
                }
            }
        });
    }

    this.updateUsersList = function(usersList) {
        if (self.usersListObj) {

            usersList.forEach(function(user) {
                var userExists = false;
                $.each(self.usersListObj.find('span'), function () {
                    if ($(this).text() === user['username']) {
                        $(this).parent().addClass('validItem');
                        userExists = true;
                        return false;
                    }
                });

                if (userExists === false) {
                    var newItem = self.createUsersListItemDiv(user['username']);
                    self.usersListObj.append(newItem);
                    self.usersListContextMenu.addEventTarget(newItem);
                }
            });

            self.usersListObj.find('.usersList-item:not(.validItem)').remove();
            self.usersListObj.find('.validItem').removeClass('validItem');
        }
    }

    this.updateChatBox = function(messages) {
        if (self.chatBox) {


            var isScrolledToEnd = self.chatBox.scrollTop() === self.chatBox[0].scrollTopMax;

            messages.forEach(function(message) {
                var messageDiv = self.chatBox.find('.msg-id-' + message['id']);
                if (messageDiv.length > 0) {
                    messageDiv.addClass('validItem');
                }
                else {
                    var newMessageDiv = self.createMessageDiv(message);
                    self.chatBox.append(newMessageDiv);
                    self.messageContextMenu.addEventTarget(newMessageDiv);
                }
            });

            var notValidItems = self.chatBox.find('.chat-msg:not(.validItem)');
            notValidItems.remove();
            self.chatBox.find('.validItem').removeClass('validItem');

            if (isScrolledToEnd)
                self.chatBox.scrollTop(self.chatBox[0].scrollTopMax);
        }
    }

    this.createMessageDiv = function(message) {
        var newItemPrototype =
            '<div class="chat-msg">' +
            '<span class="sender">' +
            '<strong></strong>' +
            '</span>' +
            '<span class="messageText"></span>' +
            '</div>';

        var newItem = $(newItemPrototype);
        newItem.find('.messageText').text(message['messageText']);

        if (message['receiver'])
            newItem.find('.sender strong').text(message['sender'] + ' > ' + message['receiver'] + ':');
        else
            newItem.find('.sender strong').text(message['sender'] + ':');

        newItem.addClass('msg-id-' + message['id']);
        newItem.addClass('validItem');
        return newItem;
    }

    this.createUsersListItemDiv = function(username) {
        var newItemPrototype =
            '<div class="usersList-item validItem">' +
            '<span></span>' +
            '</div>';

        var newItem = $(newItemPrototype);
        newItem.find('span').text(username);
        return newItem
    }

    this.sendMsg = function() {
        var message = self.messageInput.val();

        if (message) {
            var receiver = null;
            var messageWithReceiver = message.match(/^\[to:([\w\u0400-\u04FF]+)\]\s{1}(.+)/i);
            if (messageWithReceiver) {
                receiver = messageWithReceiver[1];
                message = messageWithReceiver[2];
                self.messageInput.val('[to:' + receiver + '] ');
            } else
                self.messageInput.val('');

            $.ajax({
                async: true,
                method: 'POST',
                url: '/channels/send-message',
                data: {
                    channelName: channelName,
                    message: message,
                    receiver: receiver
                },
                success: function() {
                    self.update();
                }
            });
        }
    }

    this.addReceiverFromMsg = function(messageItem) {
        if (messageItem) {
            var username = messageItem.find('.sender').text();
            if (username.indexOf('>') > -1) {
                var parts = username.split(' ');
                if (parts.length > 0)
                    username = parts[0];
            } else
                username = username.substring(0, username.length - 1);

            self.addReceiver(username);
        }
    }

    this.addReceiverFromUsersList = function(usersListItem) {
        if (usersListItem) {
            var username = usersListItem.find('span').text();
            self.addReceiver(username);
        }
    }

    this.addReceiver = function(receiverName) {
        self.messageInput.val('[to:' + receiverName + '] ' + self.messageInput.val());
        self.messageInput.focus();
        var text = self.messageInput.val();
        self.messageInput.val('');
        self.messageInput.val(text);
    }

    this.deleteMsg = function(messageDiv) {
        var classes = messageDiv.attr('class');
        var result = classes.match(/msg-id-(\d+)/i);
        if (result && result.length > 1) {
            var id = result[1];

            $.ajax({
                async: true,
                method: 'POST',
                url: '/channels/del-message',
                data: {
                    id: id
                },
                success: function() {
                    self.update();
                }
            });
        }
    }
}
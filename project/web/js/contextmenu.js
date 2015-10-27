function ContextMenu(selector) {
    var self = this;
    this.jqueryObj = $(selector);
    this.lastClickedObject = null;

    $(document).click(function() {
        if (self.isShown()) self.hide();
    });

    this.switchContextMenu = function (event) {
        if (self.isShown())
            self.hide();
        else
            self.show(event.clientX, event.clientY);
    }

    this.show = function (x, y) {
        x = x || 0;
        y = y || 0;

        self.jqueryObj.removeClass('hidden');
        self.jqueryObj.addClass('shown');
        self.jqueryObj[0].style.left = x + 'px';
        self.jqueryObj[0].style.top = y + 'px';

    }

    this.hide = function () {
        self.jqueryObj.removeClass('shown');
        self.jqueryObj.addClass('hidden');
    }

    this.isShown = function () {
        return self.jqueryObj.hasClass('shown');
    }

    this.addEventTarget = function (target) {
        if (target) {
            target.bind('contextmenu', function(e) {
                self.switchContextMenu(e);
                self.lastClickedObject = target;
                return false;
            });
        }
    }
}

function ChatContextMenu(selector) {
    ContextMenu.apply(this, arguments);
    var self = this;

    this.sendPrivateMsgBtn = $(selector + ' [name="sendPrivateMsg"]');
    this.deleteMsgBtn = $(selector + ' [name="deleteMsg"]');

    this.sendPrivateMsgCallback = function(callback) {
        this.sendPrivateMsgBtn.click(function() {
            callback(self.lastClickedObject);
        });
    }

    this.deleteMsgCallback = function(callback) {
        this.deleteMsgBtn.click(function() {
            callback(self.lastClickedObject);
        });
    }
}

ChatContextMenu.prototype = Object.create(ContextMenu.prototype);
ChatContextMenu.prototype.constructor = ChatContextMenu;
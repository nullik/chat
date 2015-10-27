var channelName = window.location.pathname;
channelName = channelName.replace('/channels/', '');

$(function() {
    var chat = new Chat('#chat', channelName);
    chat.updateInterval = 1000;
    chat.run();
});
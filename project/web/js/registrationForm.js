$(function() {
    $('#registrationform-username').change(function() { removeSpaces($(this)); });
    $('#registrationform-email').change(function() { removeSpaces($(this)); });
});

function removeSpaces(obj) {
    var spacesRemoved = obj.val().replace(/\s+/g, '');
    obj.val(spacesRemoved);
}
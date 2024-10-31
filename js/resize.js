// This prevents the execution of the code before the document is finished loading.
jQuery(document).ready(function() {
    // The 'A+' element  in the page is associated with the jQuery click() event.
    jQuery('#increase-font').click(function(event) {
        // This prevents the default action of the click() event from being triggered.
        event.preventDefault();
        // The jQuery each() event interates over each elements belonging to the 'resize' class
        jQuery('.resize').each(function() {
            // Call to a custom function to increase the text size
            changeTextSize(this, change);
        });
    });
    // The 'A-' element  in the page is associated with the jQuery click() event.
    jQuery('#decrease-font').click(function(event) {
        // This prevents the default action of the click() event from being triggered.
        event.preventDefault();
        // The jQuery each() event interates over each elements belonging to the 'resize' class
        jQuery('.resize').each(function() {
            // Call to a custom function to decrease the text size
            changeTextSize(this, -change);
        });
    });
});
// Three variables have been used to define range of the text size and the increment/decrement value respectively.
var min = 8, max = 100, change = 2;
// Defines a custom function with two parameters determining the element to be resized and the size
function changeTextSize(element, value) {
    var currentSize = parseFloat(jQuery(element).css('font-size'));
    var newSize = currentSize + value;
    if (newSize <= max && newSize >= min) {
        jQuery(element).css('font-size', newSize + 'px');
    }
}
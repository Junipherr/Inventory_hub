$(document).ready(function() {
    if ($('#sidebar-collapse').hasClass('slimScrollDiv')) {
        $('#sidebar-collapse').slimScroll({destroy: true}).css({overflow: 'visible', height: 'auto'});
    }
});
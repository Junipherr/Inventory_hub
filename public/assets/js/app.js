var APP = function() {

    // PATHS
    // ======================
    //this.ASSETS_PATH = '../../assets/';
    this.ASSETS_PATH = './assets/';
    this.SERVER_PATH = this.ASSETS_PATH + 'demo/server/';

    // GLOBAL HELPERS
    // ======================
	this.is_touch_device = function() {
        return !!('ontouchstart' in window) || !!('onmsgesturechange' in window);
	};
};

var APP = new APP();

// APP UI SETTINGS
// ======================

APP.UI = {
	scrollTop: 0, // Minimal scrolling to show scrollTop button
};


// PAGE PRELOADING ANIMATION
$(window).on('load', function() {
	setTimeout(function() {
		$('.preloader-backdrop').fadeOut(200);
		$('body').addClass('has-animation');
	},0);
});

// Hide sidebar on small screen
$(window).on('load resize scroll', function () {
    if ($(this).width() < 992) {
        $('body').addClass('sidebar-mini');
    }
});


$(function () {

    // SIDEBAR ACTIVATE METISMENU WITH PROPER CONFIG
    $(".metismenu").metisMenu({
        toggle: true,
        preventDefault: false,  // Changed to allow default click behavior
        activeClass: 'active',
        collapseClass: 'collapse',
        collapseInClass: 'show',  // Changed from 'in' to 'show' for Bootstrap 4+ compatibility
        collapsingClass: 'collapsing',
        triggerElement: 'a'  // Explicitly set trigger element
    }).on('shown.metisMenu', function(event) {
        // Ensure dropdown stays visible
        $(event.target).find('.collapse').addClass('show');
    }).on('hidden.metisMenu', function(event) {
        // Clean up state
        $(event.target).find('.collapse').removeClass('show');
    });

    // Disable metisMenu for the Inventory Management dropdown specifically
    $('.metismenu > li:has(a[href="javascript:;"])').metisMenu('dispose');
    
    // Custom handling for Inventory Management dropdown
    $('.metismenu > li:has(a[href="javascript:;"]) > a').on('click', function(e) {
        e.preventDefault();
        const $submenu = $(this).next('ul');
        const $arrow = $(this).find('.arrow');
        
        // Toggle submenu and arrow icon
        $submenu.toggleClass('show');
        $arrow.toggleClass('fa-angle-left fa-angle-down');
        
        // Close other open submenus
        $(this).closest('.metismenu').find('> li > ul.show').not($submenu).removeClass('show');
        $(this).closest('.metismenu').find('> li > a .arrow.fa-angle-down').not($arrow).toggleClass('fa-angle-left fa-angle-down');
    });

    // Activate Tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Activate Popovers
    $('[data-toggle="popover"]').popover();

    // Activate slimscroll
    $('.scroller').each(function(){
        $(this).slimScroll({
            height: $(this).attr('data-height'),
            color: $(this).attr('data-color'),
            railOpacity: '0.9',
        });
    });


    // TOGGLE THEME-CONFIG BOX    
    $('.theme-config-toggle').on('click', function() {
        $(this).parents('.theme-config').toggleClass('opened');
    });

    // LAYOUT SETTINGS
    // ======================

    // SIDEBAR TOGGLE ACTION
    $('.js-sidebar-toggler').click(function() {
        $('body').toggleClass('sidebar-mini');
        // Sync topbar by toggling class on header
        $('.header').toggleClass('sidebar-mini-active');
        // Ensure sidebar and topbar are in sync
        if ($('body').hasClass('sidebar-mini')) {
            $('.header').addClass('sidebar-mini-active');
        } else {
            $('.header').removeClass('sidebar-mini-active');
        }
    });
    
    // fixed layout
    $('#_fixedlayout').change(function(){
        if( $(this).is(':checked') ) {
           $('body').addClass('fixed-layout');
            $('#sidebar-collapse').slimScroll({
                height: '100%',
                railOpacity: '0.9',
            });
        } else {
            $('#sidebar-collapse').slimScroll({destroy: true}).css({overflow: 'visible', height: 'auto'});
            $('body').removeClass('fixed-layout');
        }
    });

    // fixed navbar
    $('#_fixedNavbar').change(function() {
        if($(this).is(':checked')) $('body').addClass('fixed-navbar');
        else $('body').removeClass('fixed-navbar');
    });
    
    // Boxed layout
    $("[name='layout-style']").change(function(){
        if(+$(this).val()) $('body').addClass('boxed-layout');
        else $('body').removeClass('boxed-layout');
    });

    // THEMES CHANGE
    // ======================

    $('.color-skin-box input:radio').change(function() {
        var val = $(this).val();
        if(val != 'default') {
            if(! $('#theme-style').length ) {
                $('head').append( "<link href='assets/css/themes/"+val+".css' rel='stylesheet' id='theme-style' >" );
            } else $('#theme-style').attr('href', 'assets/css/themes/'+val+'.css');
        } else $('#theme-style').remove();
    });


	// BACK TO TOP
	$(window).scroll(function() {
		if($(this).scrollTop() > APP.UI.scrollTop) $('.to-top').fadeIn();
        else $('.to-top').fadeOut();
	});
	$('.to-top').click(function(e) {
		$("html, body").animate({scrollTop:0},500);
	});



    // PANEL ACTIONS
    // ======================

    $('.ibox-collapse').click(function(){
    	var ibox = $(this).closest('div.ibox');
        ibox.toggleClass('collapsed-mode').children('.ibox-body').slideToggle(200);
    });
    $('.ibox-remove').click(function(){
    	$(this).closest('div.ibox').remove();
    });
    $('.fullscreen-link').click(function(){
        if($('body').hasClass('fullscreen-mode')) {
            $('body').removeClass('fullscreen-mode');
            $(this).closest('div.ibox').removeClass('ibox-fullscreen');
            $(window).off('keydown',toggleFullscreen);
        } else {
            $('body').addClass('fullscreen-mode');
            $(this).closest('div.ibox').addClass('ibox-fullscreen');
            $(window).on('keydown', toggleFullscreen);
        }
    });
    function toggleFullscreen(e) {
        // pressing the ESC key - KEY_ESC = 27 
        if(e.which == 27) {
            $('body').removeClass('fullscreen-mode');
            $('.ibox-fullscreen').removeClass('ibox-fullscreen');
            $(window).off('keydown',toggleFullscreen);
        }
    }
    
    // Backdrop functional

    $.fn.backdrop = function() {
	    $(this).toggleClass('shined');
	    $('body').toggleClass('has-backdrop');
        return $(this);
	};

    $('.backdrop').click(closeShined);

    function closeShined() {
        $('body').removeClass('has-backdrop');
        $('.shined').removeClass('shined');
    }

});



//== VENDOR PLUGINS OPTIONS

$(function () {
    
    // Timepicker
    if($.fn.timepicker) {
        $.fn.timepicker.defaults = $.extend(!0, {}, $.fn.timepicker.defaults, {
            icons: {
                up: "fa fa-angle-up",
                down: "fa fa-angle-down"
            }
        });
    }

});


//Function sa modal(Legend)
document.getElementById('scannerForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const submitButton = document.getElementById('submitCheckedUnitsButton');
    submitButton.disabled = true;
    submitButton.innerText = 'Submitting...';

    const form = event.target;
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': formData.get('_token'),
            'Accept': 'application/json',
        },
        body: formData,
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            const successMessage = document.getElementById('dynamicSuccessMessage');
            const successMessageText = document.getElementById('successMessageText');
            successMessageText.textContent = data.message || 'Checked units submitted successfully.';
            successMessage.style.display = 'block';

            submitButton.innerText = 'Submitted';

            setTimeout(() => {
                successMessage.style.transition = 'opacity 0.5s ease';
                successMessage.style.opacity = '0';
                setTimeout(() => {
                    successMessage.style.display = 'none';
                    successMessage.style.opacity = '1';
                }, 500);
            }, 2000);
        } else {
            alert(data.message || 'Failed to submit checked units.');
            submitButton.disabled = false;
            submitButton.innerText = 'Submit Checked Units';
        }
    })
    .catch(error => {
        alert('Error submitting checked units: ' + error.message);
        submitButton.disabled = false;
        submitButton.innerText = 'Submit Checked Units';
    });
});
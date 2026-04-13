(function($) {
    'use strict';

    $(document).ready(function() {
        var settings = window.patropiMegaMenuSettings || {
            trigger: 'hover',
            animation: 'fade'
        };

        function initMegaMenu() {
            var $menuItems = $('.patropi-mega-menu-item');

            if (settings.trigger === 'click') {
                initClickTrigger($menuItems);
            } else {
                initHoverTrigger($menuItems);
            }

            initCloseOnClickOutside();
        }

        function initHoverTrigger($menuItems) {
            $menuItems.each(function() {
                var $item = $(this);
                var $dropdown = $item.find('.patropi-mega-menu-dropdown').first();

                if ($dropdown.length) {
                    $item.on('mouseenter', function() {
                        showDropdown($item, $dropdown);
                    });

                    $item.on('mouseleave', function() {
                        hideDropdown($item, $dropdown);
                    });
                }
            });
        }

        function initClickTrigger($menuItems) {
            $menuItems.each(function() {
                var $item = $(this);
                var $link = $item.find('.patropi-mega-menu-link').first();
                var $dropdown = $item.find('.patropi-mega-menu-dropdown').first();

                if ($dropdown.length) {
                    $link.on('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        var isActive = $item.hasClass('active');

                        $menuItems.removeClass('active');
                        $menuItems.find('.patropi-mega-menu-dropdown').removeClass('active');

                        if (!isActive) {
                            $item.addClass('active');
                            $dropdown.addClass('active');
                        }
                    });
                }
            });
        }

        function showDropdown($item, $dropdown) {
            $item.addClass('active');
            $dropdown.addClass('active');
            
            var itemOffset = $item.offset();
            var itemHeight = $item.outerHeight();
            $dropdown.css({
                'top': itemOffset.top + itemHeight
            });
            
            if (settings.animation === 'fade') {
                $dropdown.stop(true, true).fadeIn(300);
            } else if (settings.animation === 'slide') {
                $dropdown.stop(true, true).slideDown(300);
            }
        }

        function hideDropdown($item, $dropdown) {
            if (settings.animation === 'fade') {
                $dropdown.stop(true, true).fadeOut(200, function() {
                    $item.removeClass('active');
                    $dropdown.removeClass('active');
                    $dropdown.css('top', '');
                });
            } else if (settings.animation === 'slide') {
                $dropdown.stop(true, true).slideUp(200, function() {
                    $item.removeClass('active');
                    $dropdown.removeClass('active');
                    $dropdown.css('top', '');
                });
            }
        }

        function initCloseOnClickOutside() {
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.patropi-mega-menu-wrapper').length) {
                    var $dropdowns = $('.patropi-mega-menu-dropdown');
                    var $items = $('.patropi-mega-menu-item');
                    
                    if (settings.animation === 'fade') {
                        $dropdowns.stop(true, true).fadeOut(200, function() {
                            $items.removeClass('active');
                            $dropdowns.removeClass('active');
                        });
                    } else if (settings.animation === 'slide') {
                        $dropdowns.stop(true, true).slideUp(200, function() {
                            $items.removeClass('active');
                            $dropdowns.removeClass('active');
                        });
                    }
                }
            });
        }

        if ($('.patropi-mega-menu-wrapper').length) {
            initMegaMenu();
        }
    });
})(jQuery);
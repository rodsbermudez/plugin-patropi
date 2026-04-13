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
            initMobileMenu();
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

        function initMobileMenu() {
            var $toggle = $('.patropi-mega-menu-toggle');
            var $close = $('.patropi-mega-menu-mobile-close');
            var $overlay = $('.patropi-mega-menu-mobile-overlay');
            var $mobileMenu = $('.patropi-mega-menu-mobile');
            var $submenuHeaders = $('.patropi-mega-menu-mobile-item-header');

            $toggle.on('click', function() {
                $mobileMenu.addClass('active');
                $overlay.addClass('active');
                $('body').css('overflow', 'hidden');
            });

            function closeMobileMenu() {
                $mobileMenu.removeClass('active');
                $overlay.removeClass('active');
                $('body').css('overflow', '');
            }

            $close.on('click', closeMobileMenu);
            $overlay.on('click', closeMobileMenu);

            $submenuHeaders.on('click', function() {
                var $item = $(this).closest('.patropi-mega-menu-mobile-item');
                var $submenu = $item.find('.patropi-mega-menu-mobile-submenu');
                
                if ($item.hasClass('active')) {
                    $item.removeClass('active');
                    $submenu.animate({ maxHeight: '0' }, 300);
                } else {
                    $item.addClass('active');
                    $submenu.css('max-height', '2000px');
                    $submenu.animate({ maxHeight: $submenu[0].scrollHeight + 'px' }, 300);
                }
            });
        }

        if ($('.patropi-mega-menu-wrapper').length) {
            initMegaMenu();
        }
    });
})(jQuery);
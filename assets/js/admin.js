(function($) {
    'use strict';

    $(document).on('click', '.patropi-toggle', function(e) {
        var $toggle = $(this);
        var $checkbox = $toggle.find('input[type="checkbox"]');
        
        if (e.target.tagName !== 'INPUT') {
            e.preventDefault();
            var isChecked = $checkbox.prop('checked');
            $checkbox.prop('checked', !isChecked);
        }
        
        $checkbox.trigger('change');
    });

    $(document).on('change', '.patropi-toggle input[type="checkbox"]', function() {
        var $switch = $(this).siblings('.patropi-toggle-switch');
        if ($(this).is(':checked')) {
            $switch.css('background', '#2c3e50');
        } else {
            $switch.css('background', '#95a5a6');
        }
        
        // Handle icon section visibility for rotation toggle
        if ($(this).attr('name') === 'faq_icon_rotation') {
            var $container = $(this).closest('.patropi-card');
            if ($(this).is(':checked')) {
                $container.find('.icon-section-single').show();
                $container.find('.icon-section-dual').hide();
            } else {
                $container.find('.icon-section-single').hide();
                $container.find('.icon-section-dual').show();
            }
        }
    });

    $(document).ready(function() {
        // Initialize toggle switch colors
        $('.patropi-toggle input[type="checkbox"]').each(function() {
            var $switch = $(this).siblings('.patropi-toggle-switch');
            if ($(this).is(':checked')) {
                $switch.css('background', '#2c3e50');
            } else {
                $switch.css('background', '#95a5a6');
            }
        });

        // Initialize icon section visibility based on current rotation setting
        $('input[name="faq_icon_rotation"]').each(function() {
            var $container = $(this).closest('.patropi-card');
            if ($(this).is(':checked')) {
                $container.find('.icon-section-single').show();
                $container.find('.icon-section-dual').hide();
            } else {
                $container.find('.icon-section-single').hide();
                $container.find('.icon-section-dual').show();
            }
        });

        // Highlight selected icon options
        $(document).on('click', '.icon-option', function() {
            var $container = $(this).closest('.icon-select-container');
            $container.find('.icon-option').css({
                'border-color': '#ddd',
                'background': '#fff'
            });
            $(this).css({
                'border-color': '#2c3e50',
                'background': '#f8f9fa'
            });
        });
    });
})(jQuery);
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
    });

    $(document).ready(function() {
        $('.patropi-toggle input[type="checkbox"]').each(function() {
            var $switch = $(this).siblings('.patropi-toggle-switch');
            if ($(this).is(':checked')) {
                $switch.css('background', '#2c3e50');
            } else {
                $switch.css('background', '#95a5a6');
            }
        });
    });
})(jQuery);
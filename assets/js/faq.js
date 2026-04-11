(function($) {
    'use strict';

    $(document).ready(function() {
        var settings = window.patropiFaqSettings || { openFirst: false, closeOthers: true };

        if (settings.openFirst) {
            $('.patropi-faq-question').first().addClass('active');
            $('.patropi-faq-answer').first().addClass('open');
            updateIcon($('.patropi-faq-question').first());
        }

        $('.patropi-faq-question').on('click', function() {
            var $this = $(this);
            var $answer = $this.next('.patropi-faq-answer');
            var isOpen = $answer.hasClass('open');

            if (settings.closeOthers) {
                $('.patropi-faq-question').removeClass('active');
                $('.patropi-faq-answer').removeClass('open');
                $('.patropi-faq-icon').text('+');
            }

            if (!isOpen) {
                $this.addClass('active');
                $answer.addClass('open');
                updateIcon($this);
            } else if (!settings.closeOthers) {
                $this.removeClass('active');
                $answer.removeClass('open');
                updateIcon($this);
            }
        });

        function updateIcon($button) {
            var $icon = $button.find('.patropi-faq-icon');
            if ($button.hasClass('active')) {
                $icon.text('-');
            } else {
                $icon.text('+');
            }
        }
    });
})(jQuery);
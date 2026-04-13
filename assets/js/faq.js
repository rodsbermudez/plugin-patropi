(function($) {
    'use strict';

    $(document).ready(function() {
        var rawSettings = window.patropiFaqSettings || { 
            openFirst: false, 
            closeOthers: true,
            iconRotation: true,
            iconClosed: 'dashicons-arrow-down',
            iconOpen: 'dashicons-arrow-up'
        };

        var settings = {
            openFirst: !!rawSettings.openFirst,
            closeOthers: !!rawSettings.closeOthers,
            iconRotation: !!(rawSettings.iconRotation === 1 || rawSettings.iconRotation === '1' || rawSettings.iconRotation === true || rawSettings.iconRotation === 'true'),
            iconClosed: String(rawSettings.iconClosed) || 'dashicons-arrow-down',
            iconOpen: String(rawSettings.iconOpen) || 'dashicons-arrow-up'
        };

        function updateIcon($button, isOpen) {
            var $icon = $button.find('.patropi-faq-icon');
            $icon.removeClass('dashicons-insert dashicons-remove dashicons-arrow-up dashicons-arrow-down dashicons-arrow-up-alt2 dashicons-arrow-down-alt2 dashicons-plus-alt2 dashicons-minus rotate open');
            
            if (settings.iconRotation) {
                $icon.addClass(settings.iconClosed);
                $icon.addClass('rotate');
                if (isOpen) {
                    $icon.addClass('open');
                }
            } else {
                if (isOpen) {
                    $icon.addClass(settings.iconOpen);
                } else {
                    $icon.addClass(settings.iconClosed);
                }
            }
        }

        // Initialize icons
        $('.patropi-faq-question').each(function() {
            var $this = $(this);
            var $answer = $this.next('.patropi-faq-answer');
            var isOpen = $answer.hasClass('open');
            updateIcon($this, isOpen);
        });

        // Initialize first item if openFirst is enabled
        if (settings.openFirst) {
            var $firstQuestion = $('.patropi-faq-question').first();
            var $firstAnswer = $firstQuestion.next('.patropi-faq-answer');
            
            $firstAnswer.addClass('open');
            $firstAnswer.css('height', 'auto');
            var fullHeight = $firstAnswer[0].scrollHeight;
            $firstAnswer.css('height', '0');
            
            setTimeout(function() {
                $firstAnswer.css({
                    'height': fullHeight + 'px',
                    'transition': 'height 0.3s ease-out'
                });
            }, 50);
            
            $firstQuestion.addClass('active');
            updateIcon($firstQuestion, true);
        }

        // Click handler
        $('.patropi-faq-question').on('click', function() {
            var $this = $(this);
            var $answer = $this.next('.patropi-faq-answer');
            var isOpen = $answer.hasClass('open');

            if (settings.closeOthers) {
                $('.patropi-faq-question').not($this).removeClass('active');
                $('.patropi-faq-question').not($this).next('.patropi-faq-answer').removeClass('open').css('height', '0');
                $('.patropi-faq-question').not($this).each(function() {
                    updateIcon($(this), false);
                });
            }

            if (!isOpen) {
                $answer.addClass('open');
                $answer.css('height', 'auto');
                var fullHeight = $answer[0].scrollHeight;
                $answer.css('height', '0');
                
                setTimeout(function() {
                    $answer.css({
                        'height': fullHeight + 'px',
                        'transition': 'height 0.3s ease-out'
                    });
                }, 50);
                
                $this.addClass('active');
                updateIcon($this, true);
            } else {
                $answer.css('height', '0');
                setTimeout(function() {
                    $answer.removeClass('open');
                }, 300);
                $this.removeClass('active');
                updateIcon($this, false);
            }
        });
    });
})(jQuery);
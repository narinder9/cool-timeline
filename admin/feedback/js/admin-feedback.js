(function($) {
    $(document).ready(function() {
        let plugin_name = 'cool-timeline';
        let plugin_slug = 'ctl';
        $target = $('#the-list').find('[data-slug="' + plugin_name + '"] span.deactivate a');

        var plugin_deactivate_link = $target.attr('href');

        $($target).on('click', function(event) {
            event.preventDefault();
            $('#wpwrap').css('opacity', '0.4');

            $("#cool-plugins-deactivate-feedback-dialog-wrapper").animate({
                opacity: 1
            }, 200, function() {
                $("#cool-plugins-deactivate-feedback-dialog-wrapper").removeClass('hide-feedback-popup');
                $("#cool-plugins-deactivate-feedback-dialog-wrapper").find('#cool-plugin-submitNdeactivate').addClass(plugin_slug);
                $("#cool-plugins-deactivate-feedback-dialog-wrapper").find('#cool-plugin-skipNdeactivate').addClass(plugin_slug);
            });
        });

        $('.cool-plugins-deactivate-feedback-dialog-input').on('click', function() {
            if ($('#cool-plugins-GDPR-data-notice').is(":checked") === true && $('.cool-plugins-deactivate-feedback-dialog-input').is(':checked') === true) {
                $('#cool-plugin-submitNdeactivate').removeClass('button-deactivate');
            } else {
                $('#cool-plugin-submitNdeactivate').addClass('button-deactivate');
            }

        });

        $('#cool-plugins-GDPR-data-notice').on('click', function() {

            if ($('#cool-plugins-GDPR-data-notice').is(":checked") === true && $('.cool-plugins-deactivate-feedback-dialog-input').is(':checked') === true) {
                $('#cool-plugin-submitNdeactivate').removeClass('button-deactivate');
            } else {
                $('#cool-plugin-submitNdeactivate').addClass('button-deactivate');
            }
        })

        $('#wpwrap').on('click', function(ev) {
            if ($("#cool-plugins-deactivate-feedback-dialog-wrapper.hide-feedback-popup").length == 0) {
                ev.preventDefault();
                $("#cool-plugins-deactivate-feedback-dialog-wrapper").animate({
                    opacity: 0
                }, 200, function() {
                    $("#cool-plugins-deactivate-feedback-dialog-wrapper").addClass("hide-feedback-popup");
                    $("#cool-plugins-deactivate-feedback-dialog-wrapper").find('#cool-plugin-submitNdeactivate').removeClass(plugin_slug);
                    $('#wpwrap').css('opacity', '1');
                })

            }
        })

        $(document).on('click', '#cool-plugin-submitNdeactivate.' + plugin_slug + ':not(".button-deactivate")', function(event) {
            let nonce = $("input[name='ctl-wpnonce']").val();
            let reason = $('.cool-plugins-deactivate-feedback-dialog-input:checked').val();
            let message = '';

            if ($('textarea[name="reason_' + reason + '"]').length > 0) {
                if ($('textarea[name="reason_' + reason + '"]').val() == '') {
                    alert('Please provide some extra information!');
                    return;
                } else {
                    message = $('textarea[name="reason_' + reason + '"]').val();
                }
            }

            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    'action': plugin_slug + '_submit_deactivation_response',
                    '_wpnonce': nonce,
                    'reason': reason,
                    'message': message,
                },
                beforeSend: function(data) {
                    $('#cool-plugin-submitNdeactivate').text('Deactivating...');
                    $('#cool-plugin-submitNdeactivate').attr('id', 'deactivating-plugin');
                    $('#cool-plugins-loader-wrapper').show();
                    $('#cool-plugin-skipNdeactivate').remove();
                },
                success: function(res) {
                    $('#cool-plugins-loader-wrapper').hide();
                    window.location = plugin_deactivate_link;
                    $('#deactivating-plugin').text('Deactivated');
                }
            })

        });

        $(document).on('click', '#cool-plugin-skipNdeactivate.' + plugin_slug + ':not(".button-deactivate")', function() {
            $('#cool-plugin-submitNdeactivate').remove();
            $('#cool-plugin-skipNdeactivate').addClass('button-deactivate');
            $('#cool-plugin-skipNdeactivate').attr('id', 'deactivating-plugin');
            window.location = plugin_deactivate_link;
        });

    });
})(jQuery);
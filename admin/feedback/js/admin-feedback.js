(function($) {
    $(document).ready(function() {
        let plugin_name = 'cool-timeline';
        let plugin_slug = 'ctl';
        $target = $('#the-list').find('[data-slug="' + plugin_name + '"] span.deactivate a');

        var plugin_deactivate_link = $target.attr('href');

        $($target).on('click', function(event) {
            event.preventDefault();
            $('#wpwrap').css('opacity', '0.4');

            $("#cool-plugins-deactivate-feedback-dialog-wrapper[data-slug='" + plugin_slug + "']").animate({
                opacity: 1
            }, 200, function() {
                $("#cool-plugins-deactivate-feedback-dialog-wrapper[data-slug='" + plugin_slug + "']").removeClass('hide-feedback-popup');
                $("#cool-plugins-deactivate-feedback-dialog-wrapper[data-slug='" + plugin_slug + "']").find('#cool-plugin-submitNdeactivate').addClass(plugin_slug);
                $("#cool-plugins-deactivate-feedback-dialog-wrapper[data-slug='" + plugin_slug + "']").find('#cool-plugin-skipNdeactivate').addClass(plugin_slug);
            });
        });

        $('.cool-plugins-deactivate-feedback-dialog-input').on('click',function(){

            if($('#cool-plugins-GDPR-data-notice-'+plugin_slug).is(":checked") === true && $('.cool-plugins-deactivate-feedback-dialog-input').is(':checked') === true){ 

                $('#ctl-cool-plugin-submitNdeactivate').removeClass('button-deactivate');

            }

            else{

                $('#ctl-cool-plugin-submitNdeactivate').addClass('button-deactivate');

            }




        });
        $('#cool-plugins-GDPR-data-notice-'+plugin_slug).on('click', function(){



           if($('#cool-plugins-GDPR-data-notice-'+plugin_slug).is(":checked") === true && $('.cool-plugins-deactivate-feedback-dialog-input').is(':checked') === true){ 

                $('#ctl-cool-plugin-submitNdeactivate').removeClass('button-deactivate');

            }

            else{

                $('#ctl-cool-plugin-submitNdeactivate').addClass('button-deactivate');

            }

        });
  $('#wpwrap').on('click', function(ev){

            if( $("#cool-plugins-deactivate-feedback-dialog-wrapper.hide-feedback-popup").length==0 ){

                ev.preventDefault();

                $("#cool-plugins-deactivate-feedback-dialog-wrapper").animate({

                    opacity:0

                },200,function(){

                    $("#cool-plugins-deactivate-feedback-dialog-wrapper").addClass("hide-feedback-popup");
                     $("#cool-plugins-deactivate-feedback-dialog-wrapper").find('#ctl-cool-plugin-submitNdeactivate').removeClass(plugin_slug);

                    // $("#cool-plugins-deactivate-feedback-dialog-wrapper").find('#ctl-cool-plugin-submitNdeactivate').removeClass(plugin_slug);

                    $('#wpwrap').css('opacity','1');

                })



            }

        })
      
 $(document).on('click','#ctl-cool-plugin-submitNdeactivate:not(".button-deactivate")', function(event){

             let nonce = $('#_wpnonce').val();
            let reason = $('.cool-plugins-deactivate-feedback-dialog-input:checked').val();
            let message = '';
            if( $('textarea[name="reason_'+reason+'"]').length>0 ){
                let $textareas = $('textarea[name="reason_'+reason+'"]');
                let allMessages = [];
                
                $textareas.each(function() {
                    let reasonText = $(this).val().trim();
                    if (reasonText) {
                        allMessages.push(reasonText);
                    }
                });

                if (allMessages.length === 0) {
                    alert('Please provide some extra information!');
                    return;
                } else {
                    message = allMessages.join(' | ');
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
                    $('#ctl-cool-plugin-submitNdeactivate').text('Deactivating...');
                    $('#ctl-cool-plugin-submitNdeactivate').attr('id', 'deactivating-plugin');
                    $('#cool-plugins-loader-wrapper').show();
                    $('#ctl-cool-plugin-skipNdeactivate').remove();
                },
                success: function(res) {
                    $('#cool-plugins-loader-wrapper').hide();
                    window.location = plugin_deactivate_link;
                    $('#deactivating-plugin').text('Deactivated');
                }
            })

        });

         $(document).on('click', '#ctl-cool-plugin-skipNdeactivate:not(".button-deactivate")', function(){

            $('#ctl-cool-plugin-submitNdeactivate').remove();
            $('#ctl-cool-plugin-skipNdeactivate').addClass('button-deactivate');
            $('#ctl-cool-plugin-skipNdeactivate').attr('id', 'deactivating-plugin');
            window.location = plugin_deactivate_link;
        });
      
    });
})(jQuery);
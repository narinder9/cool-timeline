(function($) {
    //Code block here
    $(window).on("load resize", function() {
        // masonry plugin call
        var left_plus = 0;
        var right_plus = 0;
        var i = 0;

        //$('.timeline-mansory').removeClass("ctl-right").removeClass("ctl-left");

        $('.compact-wrapper .cooltimeline_cont').masonry({
            itemSelector: '.timeline-mansory'
        });
        setTimeout(function() {

            $('.compact-wrapper .cooltimeline_cont').find('.timeline-mansory').each(function(index) {

                var leftPos = $(this).position().left;

                if (leftPos <= 0) {
                    var topPos = $(this).position().top + left_plus;
                    $($(".timeline-mansory").get(i)).removeClass("ctl-right");
                    $(this).addClass('ctl-left');
                } else {
                    var topPos = $(this).position().top + right_plus;
                    $($(".timeline-mansory").get(i)).removeClass("ctl-left");
                    $(this).addClass('ctl-right');
                }

                i++;

                if ($(this).next('.timeline-post').length > 0) {
                    var next_leftPos = $(this).next().position().left;
                    if (next_leftPos <= 0) {
                        var second_topPos = $(this).next().position().top + left_plus;
                    } else {
                        var second_topPos = $(this).next().position().top + right_plus;
                    }
                    var top_gap = second_topPos - topPos;
                    if (top_gap <= 44) {
                        if (leftPos <= 0) {
                            right_plus = right_plus + 44 - top_gap;
                        } else {
                            left_plus = left_plus + 44 - top_gap;
                        }
                    }
                }

                var firstHeight = $(this).outerHeight(true);
                var firstBottom = topPos + firstHeight + 60;

                $(this).css({
                    'top': topPos + 'px'
                });

                $('.compact-wrapper .cooltimeline_cont').css({
                    'height': firstBottom + 'px'
                });

            });

        }, 800);
    });

    $(document).ready(function() {
        // masonry plugin call
        var left_plus = 0;
        var right_plus = 0;

        $('.compact-wrapper .cooltimeline_cont').masonry({
            itemSelector: '.timeline-mansory'
        });
        $('.compact-wrapper .cooltimeline_cont').find('.timeline-mansory').each(function(index) {

            var leftPos = $(this).position().left;

            if (leftPos <= 0) {
                var topPos = $(this).position().top + left_plus;
                $(this).addClass('ctl-left');
            } else {
                var topPos = $(this).position().top + right_plus;;
                $(this).addClass('ctl-right');
            }

            if ($(this).next('.timeline-post').length > 0) {
                var next_leftPos = $(this).next().position().left;
                if (next_leftPos <= 0) {
                    var second_topPos = $(this).next().position().top + left_plus;
                } else {
                    var second_topPos = $(this).next().position().top + right_plus;
                }
                var top_gap = second_topPos - topPos;
                if (top_gap <= 44) {
                    if (leftPos <= 0) {
                        right_plus = right_plus + 44 - top_gap;
                    } else {
                        left_plus = left_plus + 44 - top_gap;
                    }
                }
            }

            var firstHeight = $(this).outerHeight(true);
            var firstBottom = topPos + firstHeight + 60;

            $(this).css({
                'top': topPos + 'px'
            });

            $('.compact-wrapper .cooltimeline_cont').css({
                'height': firstBottom + 'px'
            });

        });
    });
})(jQuery);

$(document).ready(function () {
    var $loadingBlock = $('.mobile-menu__viewport'),
        itemActive = 'mobile-menu__item--active',
        loadingClass = 'mobile-menu__preloader',
        isOpen = 'mobile-menu--open';

    $(document).on('click', '.header__mobile-menu-btn, .mobile-menu__close, .main-overlay', function (e) {
        isMobileMenu();
    });

    $(document).on('click', '.mobile-menu__item--realty', function () {
        if ($(this).hasClass(itemActive)) {
            $(this).removeClass(itemActive);
            if ($(this).hasClass('mobile-menu__item--parent-realty')) {
                $(this).closest('li').find('.mobile-menu__realty-parent-list').filter(':first').hide();
            }
        } else {
            $(this).addClass(itemActive);
            if ($(this).hasClass('mobile-menu__item--parent-realty')) {
                $(this).closest('li').find('.mobile-menu__realty-parent-list').filter(':first').show();
            }
        }
    });

    $(document).on('click', '.mobile-menu__open_category', function (e) {
        e.preventDefault();
        var url = $(this).data('href');
        window.location.href = url;
        return false;
    });

    function isMobileMenu() {
        var noScroll = 'no-scroll',
            $body = $('body'),
            $menuBlock = $('.mobile-menu'),
            $overlay = $('.main-overlay');
        if ($menuBlock.hasClass(isOpen)) {
            $menuBlock.removeClass(isOpen);
            $overlay.hide();
            $body.removeClass(noScroll);
        } else {
            $menuBlock.addClass(isOpen);
            $overlay.show();
            $body.addClass(noScroll);
        }
        if (!$loadingBlock.find('.mobile-menu__block').length) {
            $loadingBlock.addClass(loadingClass);
            var url = '/helper/mobile-menu';
            $.get(url, function (response) {
                try {
                    $loadingBlock.html(response);
                    $loadingBlock.removeClass(loadingClass);
                } catch (e) {
                    console.log('Error: ' + e.message);
                }
            });
        }
    }
});

/*!
 * @package   yii2-bootstrap4-dropdown
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2015 - 2020
 * @version   1.0.1
 *
 * Bootstrap 4 Dropdown Nested Submenu Script
 * 
 * For more JQuery plugins visit http://plugins.krajee.com
 * For more Yii related demos visit http://demos.krajee.com
 **/
(function ($) {
    "use strict";
    $('.dropdown-menu a.dropdown-toggle').on('click', function () {
        // noinspection JSValidateTypes
        var $el = $(this), $parent = $el.offsetParent(".dropdown-menu"), $subMenu, $subMenuParent;
        if (!$el.next().hasClass('show')) {
            $el.parents('.dropdown-menu').first().find('.show').removeClass("show");
        }
        $subMenu = $el.next(".dropdown-menu").toggleClass('show');
        $subMenuParent = $subMenu.closest('.dropdown');
        $subMenuParent.closest('.dropdown-menu').find('.dropdown').each(function () {
            var $el = $(this);
            if (!$el.is($subMenuParent)) {
                $el.removeClass('is-expanded');
            }
        });
        $subMenuParent.toggleClass('is-expanded');
        $el.parent("li.nav-item").toggleClass('show');
        $el.parents('.dropdown.show').on('hidden.bs.dropdown', function () {
            $('.dropdown-menu .show').removeClass("show");
            $('.dropdown-menu .is-expanded').removeClass("is-expanded");
        });
        $el.next().css({"top": $el[0].offsetTop, "left": $parent.outerWidth() - 4});
        return false;
    });
})(window.jQuery);
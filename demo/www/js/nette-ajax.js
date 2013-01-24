/**
 * AJAX Nette Framework plugin for jQuery
 *
 * @copyright   Copyright (c) 2009 Jan Marek
 * @license     MIT
 * @link        http://nettephp.com/cs/extras/jquery-ajax
 * @version     0.2
 */

(function($) {

$.extend({
	nette: {
		updateSnippet: function (id, html) {
			id = $.nette.escapeSelector(id);
			html === null ? $('#' + id).remove() : $('#' + id).html(html);
		},

		success: function (payload) {
			if (typeof payload !== 'undefined') {
				// redirect
				if (payload.redirect) {
					window.location.href = payload.redirect;
					return ;
				}

				// snippets
				if (payload.snippets) {
					$.each(payload.snippets, function (id, html) {
						$.nette.updateSnippet(id, html);
					});
				}
			}
		},

		escapeSelector: function (s) {
			return s.replace(/[\!"#\$%&'\(\)\*\+,\.\/:;<=>\?@\[\\\]\^`\{\|\}~]/g, '\\$&');
		}
	}
});

$.ajaxSetup({
	success: $.nette.success
});



$(function () {
    // vhodně nastylovaný div vložím po načtení stránky
    $('<div id="ajax-spinner"></div>').appendTo("body").ajaxStop(function () {
        // a při události ajaxStop spinner schovám a nastavím mu původní pozici
        $(this).hide().css({
            position: "fixed",
            left: "50%",
            top: "50%"
        });
    }).hide();
});

// zajaxovatění odkazů provedu takto
$("a.ajax").live("click", function (event) {
    event.preventDefault();

    $.get(this.href);

    // zobrazení spinneru a nastavení jeho pozice
    $("#ajax-spinner").show().css({
        position: "absolute",
        left: event.pageX + 20,
        top: event.pageY + 40
    });
});


}) (jQuery);


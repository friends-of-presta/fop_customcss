/**
 * Copyright since 2020 Friends of Presta
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to <infos@friendsofpresta.org> so we can send you a copy immediately.
 *
 * @author    Friends of Presta <infos@friendsofpresta.org>
 * @copyright 2020 Friends of Presta
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 *
 * Don't forget to prefix your containers with your own identifier
 * to avoid any conflicts with others containers.
 */

$(function() {
    var options = {
		maxLines: Infinity,
		minLines: 15,
		wrap: true,
		autoScrollEditorIntoView: true
	};


        var editor = ace.edit("css_editor", options);
        editor.session.setMode("ace/mode/css");
        var input2 = $('textarea[name="css_real_value"]');
        editor.getSession().on("change", function () {
            input2.val(editor.getSession().getValue());
        });

})

<?php
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
 */

/**
 * /views/css/front.css is under cvs, it should not be.
 * It's shop specific.
 * It must not be overwritten by module upgrade.
 *
 * Copy current file contents to new file
 */
function upgrade_module_1_1_0($module)
{
    $original_file = realpath(__DIR__ . '/../views/css/front.css');
    $new_file      = __DIR__ . '/../views/css/fop_customcss.css';
    if ($original_file)
    {
        if(!rename($original_file, $new_file))
        {
            throw new Exception("migration failed : copy and remove $original_file and try again.");
        }
    }

    return true;
}


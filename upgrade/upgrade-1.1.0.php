<?php
/**
 * 2020-present Friends of Presta community
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/MIT
 *
 * @author    Friends of Presta community
 * @copyright 2020-present Friends of Presta community
 * @license   https://opensource.org/licenses/MIT MIT
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
        if (!rename($original_file, $original_file))
        {
            throw new Exception("failed to rename '$original_file' to '$new_file' ");
        }
    }

    return true;
}



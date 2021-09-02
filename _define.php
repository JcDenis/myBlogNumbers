<?php
/**
 * @brief myBlogNumbers, a plugin for Dotclear 2
 * 
 * @package Dotclear
 * @subpackage Plugin
 * 
 * @author Jean-Christian Denis, Pierre Van Glabeke
 * 
 * @copyright Jean-Christian Denis
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('DC_RC_PATH')) {
    return null;
}

$this->registerModule(
    'My blog numbers',
    'Show some figures of your blog',
    'Jean-Christian Denis, Pierre Van Glabeke',
    '2021.08.27.1',
    [
        'requires' => [['core', '2.19']],
        'permissions' => 'usage,contentadmin',
        'type' => 'plugin',
        'support' => 'http://forum.dotclear.org/viewtopic.php?id=40934',
        'details' => 'http://plugins.dotaddict.org/dc2/details/myBlogNumbers',
        'repository' => 'https://raw.githubusercontent.com/JcDenis/myBlogNumbers/master/dcstore.xml'
    ]
);
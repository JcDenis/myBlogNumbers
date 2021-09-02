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

$core->addBehavior('initWidgets', 'myBlogNumbersWidgetAdmin');

function myBlogNumbersWidgetAdmin($w)
{
    global $core;

    $w
        ->create(
            'myblognumbers',
            __('My blog numbers'),
            'myBlogNumbersWidgetPublic',
            null,
            __('Show some figures of your blog')
        )
        ->addTitle(__('My blog numbers'))

        # Entry
        ->setting(
            'entry_show',
            __('Show entries count'),
            1,
            'check'
        )
        ->setting(
            'entry_title',
            __('Title for entries count:'),
            __('Entries:'),
            'text'
        )

        # Cat
        ->setting(
            'cat_show',
            __('Show categories count'),
            1,
            'check'
        )
        ->setting(
            'cat_title',
            __('Title for categories count:'),
            __('Categories:'),
            'text'
        )

        # Comment
        ->setting(
            'comment_show',
            __('Show comments count'),
            1,
            'check'
        )
        ->setting(
            'comment_title',
            __('Title for comments count:'),
            __('Comments:'),
            'text'
        )

        # Trackback
        ->setting(
            'trackback_show',
            __('Show trackbacks count'),
            1,
            'check'
        )
        ->setting(
            'trackback_title',
            __('Title for trackbacks count:'),
            __('Trackbacks:'),
            'text'
        );

    if ($core->plugins->moduleExists('tags')) {
        # Tag
        $w->myblognumbers
            ->setting(
                'tag_show',
                __('Show tags count'),
                1,
                'check'
            )
            ->setting(
                'tag_title',
                __('Title for tags count:'),
                __('Tags:'),
                'text'
            );
    }

    # Users (that post)
    $w->myblognumbers
        ->setting(
            'user_show',
            __('Show users count'),
            1,
            'check'
        )
        ->setting(
            'user_title',
            __('Title for users count:'),
            __('Authors:'),
            'text'
        );

    # --BEHAVIOR-- myBlogNumbersWidgetInit
    $core->callBehavior('myBlogNumbersWidgetInit',$w);

    # widget option - page to show on
    $w->myblognumbers
        ->addHomeOnly()
        ->addContentOnly()
        ->addClass()
        ->addOffline();
}
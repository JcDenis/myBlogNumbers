<?php

declare(strict_types=1);

namespace Dotclear\Plugin\myBlogNumbers;

use Dotclear\App;
use Dotclear\Helper\Html\Html;
use Dotclear\Plugin\widgets\WidgetsStack;
use Dotclear\Plugin\widgets\WidgetsElement;

/**
 * @brief       myBlogNumbers widgets class.
 * @ingroup     myBlogNumbers
 *
 * @author      Jean-Christian Denis (author)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class Widgets
{
    public static function initWidgets(WidgetsStack $w): void
    {
        $w
            ->create(
                'myblognumbers',
                __('My blog numbers'),
                self::frontendWidget(...),
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

        if (App::plugins()->moduleExists('tags')) {
            # Tag
            $w->get('myblognumbers')
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
        $w->get('myblognumbers')
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
        App::behavior()->callBehavior('myBlogNumbersWidgetInit', $w);

        # widget option - page to show on
        $w->get('myblognumbers')
            ->addHomeOnly()
            ->addContentOnly()
            ->addClass()
            ->addOffline();
    }

    public static function frontendWidget(WidgetsElement $w): string
    {
        if (!App::blog()->isDefined()
            || $w->get('offline')
            || !$w->checkHomeOnly(App::url()->type)
        ) {
            return '';
        }

        $content = $addons = '';
        $s_line  = '<li>%s%s</li>';
        $s_title = '<strong>%s</strong> ';

        # Entry
        if ($w->get('entry_show')) {
            $title = $w->get('entry_title') ?
                sprintf($s_title, Html::escapeHTML($w->get('entry_title'))) : '';

            $count = (int) App::blog()->getPosts([], true)->f(0);

            $text = $count == 0 ?
                sprintf(__('no entries'), $count) :
                sprintf(__('one entry', '%s entries', $count), $count);

            $content .= sprintf($s_line, $title, $text);
        }

        # Cat
        if ($w->get('cat_show')) {
            $title = $w->get('cat_title') ?
                sprintf($s_title, Html::escapeHTML($w->get('cat_title'))) : '';

            $count = App::blog()->getCategories([])->count();

            $text = $count == 0 ?
                sprintf(__('no categories'), $count) :
                sprintf(__('one category', '%s categories', $count), $count);

            $content .= sprintf($s_line, $title, $text);
        }

        # Comment
        if ($w->get('comment_show')) {
            $title = $w->get('comment_title') ?
                sprintf($s_title, Html::escapeHTML($w->get('comment_title'))) : '';

            $params = [
                'post_type'         => 'post',
                'comment_status'    => 1,
                'comment_trackback' => 0,
            ];
            $count = (int) App::blog()->getComments($params, true)->f(0);

            $text = $count == 0 ?
                sprintf(__('no comments'), $count) :
                sprintf(__('one comment', '%s comments', $count), $count);

            $content .= sprintf($s_line, $title, $text);
        }

        # Trackback
        if ($w->get('trackback_show')) {
            $title = $w->get('trackback_title') ?
                sprintf($s_title, Html::escapeHTML($w->get('trackback_title'))) : '';

            $params = [
                'post_type'         => 'post',
                'comment_status'    => 1,
                'comment_trackback' => 1,
            ];
            $count = (int) App::blog()->getComments($params, true)->f(0);

            $text = $count == 0 ?
                sprintf(__('no trackbacks'), $count) :
                sprintf(__('one trackback', '%s trackbacks', $count), $count);

            $content .= sprintf($s_line, $title, $text);
        }

        # Tag
        if (App::plugins()->moduleExists('tags') && $w->get('tag_show')) {
            $title = $w->get('tag_title') ?
                sprintf($s_title, Html::escapeHTML($w->get('tag_title'))) : '';

            $count = (int) App::db()->con()->select(
                'SELECT count(M.meta_id) AS count ' .
                'FROM ' . App::db()->con()->prefix() . App::meta()::META_TABLE_NAME . ' M ' .
                'LEFT JOIN ' . App::db()->con()->prefix() . 'post P ON P.post_id=M.post_id ' .
                "WHERE M.meta_type='tag' " .
                "AND P.blog_id='" . App::blog()->id() . "' "
            )->f(0);

            $text = $count == 0 ?
                sprintf(__('no tags'), $count) :
                sprintf(__('one tag', '%s tags', $count), $count);

            $content .= sprintf($s_line, $title, $text);
        }

        # User (that post)
        if ($w->get('user_show')) {
            $title = $w->get('user_title') ?
                sprintf($s_title, Html::escapeHTML($w->get('user_title'))) : '';

            $count = App::blog()->getPostsUsers('post')->count();

            $text = $count == 0 ?
                sprintf(__('no author'), $count) :
                sprintf(__('one author', '%s authors', $count), $count);

            $content .= sprintf($s_line, $title, $text);
        }

        # --BEHAVIOR-- myBlogNumbersWidgetParse
        $addons = App::behavior()->callBehavior('myBlogNumbersWidgetParse', $w);

        # Nothing to display
        if (!$content && !$addons) {
            return '';
        }

        # Display
        return $w->renderDiv(
            (bool) $w->get('content_only'),
            'myblognumbers ' . $w->get('class'),
            '',
            ($w->get('title') ? $w->renderTitle(Html::escapeHTML($w->get('title'))) : '') .
                sprintf('<ul>%s</ul>', $content . $addons)
        );
    }
}

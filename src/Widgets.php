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
declare(strict_types=1);

namespace Dotclear\Plugin\myBlogNumbers;

use dcCore;
use dcMeta;
use Dotclear\Helper\Html\Html;
use Dotclear\Plugin\widgets\WidgetsStack;
use Dotclear\Plugin\widgets\WidgetsElement;

class Widgets
{
    public static function initWidgets(WidgetsStack $w): void
    {
        $w
            ->create(
                'myblognumbers',
                __('My blog numbers'),
                [self::class, 'frontendWidget'],
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

        if (dcCore::app()->plugins->moduleExists('tags')) {
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
        dcCore::app()->callBehavior('myBlogNumbersWidgetInit', $w);

        # widget option - page to show on
        $w->myblognumbers
            ->addHomeOnly()
            ->addContentOnly()
            ->addClass()
            ->addOffline();
    }

    public static function frontendWidget(WidgetsElement $w): string
    {
        if (is_null(dcCore::app()->blog) || $w->offline || !$w->checkHomeOnly(dcCore::app()->url->type)) {
            return '';
        }

        $content = $addons = '';
        $s_line  = '<li>%s%s</li>';
        $s_title = '<strong>%s</strong> ';

        # Entry
        if ($w->entry_show) {
            $title = $w->entry_title ?
                sprintf($s_title, Html::escapeHTML($w->entry_title)) : '';

            $count = (int) dcCore::app()->blog->getPosts([], true)->f(0);

            $text = $count == 0 ?
                sprintf(__('no entries'), $count) :
                sprintf(__('one entry', '%s entries', $count), $count);

            $content .= sprintf($s_line, $title, $text);
        }

        # Cat
        if ($w->cat_show) {
            $title = $w->cat_title ?
                sprintf($s_title, Html::escapeHTML($w->cat_title)) : '';

            $count = dcCore::app()->blog->getCategories([])->count();

            $text = $count == 0 ?
                sprintf(__('no categories'), $count) :
                sprintf(__('one category', '%s categories', $count), $count);

            $content .= sprintf($s_line, $title, $text);
        }

        # Comment
        if ($w->comment_show) {
            $title = $w->comment_title ?
                sprintf($s_title, Html::escapeHTML($w->comment_title)) : '';

            $params = [
                'post_type'         => 'post',
                'comment_status'    => 1,
                'comment_trackback' => 0,
            ];
            $count = (int) dcCore::app()->blog->getComments($params, true)->f(0);

            $text = $count == 0 ?
                sprintf(__('no comments'), $count) :
                sprintf(__('one comment', '%s comments', $count), $count);

            $content .= sprintf($s_line, $title, $text);
        }

        # Trackback
        if ($w->trackback_show) {
            $title = $w->trackback_title ?
                sprintf($s_title, Html::escapeHTML($w->trackback_title)) : '';

            $params = [
                'post_type'         => 'post',
                'comment_status'    => 1,
                'comment_trackback' => 1,
            ];
            $count = (int) dcCore::app()->blog->getComments($params, true)->f(0);

            $text = $count == 0 ?
                sprintf(__('no trackbacks'), $count) :
                sprintf(__('one trackback', '%s trackbacks', $count), $count);

            $content .= sprintf($s_line, $title, $text);
        }

        # Tag
        if (dcCore::app()->plugins->moduleExists('tags') && $w->tag_show) {
            $title = $w->tag_title ?
                sprintf($s_title, Html::escapeHTML($w->tag_title)) : '';

            $count = (int) dcCore::app()->con->select(
                'SELECT count(M.meta_id) AS count ' .
                'FROM ' . dcCore::app()->prefix . dcMeta::META_TABLE_NAME . ' M ' .
                'LEFT JOIN ' . dcCore::app()->prefix . 'post P ON P.post_id=M.post_id ' .
                "WHERE M.meta_type='tag' " .
                "AND P.blog_id='" . dcCore::app()->blog->id . "' "
            )->f(0);

            $text = $count == 0 ?
                sprintf(__('no tags'), $count) :
                sprintf(__('one tag', '%s tags', $count), $count);

            $content .= sprintf($s_line, $title, $text);
        }

        # User (that post)
        if ($w->user_show) {
            $title = $w->user_title ?
                sprintf($s_title, Html::escapeHTML($w->user_title)) : '';

            $count = dcCore::app()->blog->getPostsUsers('post')->count();

            $text = $count == 0 ?
                sprintf(__('no author'), $count) :
                sprintf(__('one author', '%s authors', $count), $count);

            $content .= sprintf($s_line, $title, $text);
        }

        # --BEHAVIOR-- myBlogNumbersWidgetParse
        $addons = dcCore::app()->callBehavior('myBlogNumbersWidgetParse', $w);

        # Nothing to display
        if (!$content && !$addons) {
            return '';
        }

        # Display
        return $w->renderDiv(
            (bool) $w->content_only,
            'myblognumbers ' . $w->class,
            '',
            ($w->title ? $w->renderTitle(Html::escapeHTML($w->title)) : '') .
                sprintf('<ul>%s</ul>', $content . $addons)
        );
    }
}

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

require __DIR__ . '/_widgets.php';

function myBlogNumbersWidgetPublic($w)
{
    $content = $addons = '';
    $s_line  = '<li>%s%s</li>';
    $s_title = '<strong>%s</strong> ';

    if ($w->offline) {
        return;
    }

    if (($w->homeonly == 1 && !dcCore::app()->url->isHome(dcCore::app()->url->type))
     || ($w->homeonly == 2 && dcCore::app()->url->isHome(dcCore::app()->url->type))) {
        return null;
    }

    # Entry
    if ($w->entry_show) {
        $title = $w->entry_title ?
            sprintf($s_title, html::escapeHTML($w->entry_title)) : '';

        $count = dcCore::app()->blog->getPosts([], true)->f(0);

        $text = $count == 0 ?
            sprintf(__('no entries'), $count) :
            sprintf(__('one entry', '%s entries', $count), $count);

        $content .= sprintf($s_line, $title, $text);
    }

    # Cat
    if ($w->cat_show) {
        $title = $w->cat_title ?
            sprintf($s_title, html::escapeHTML($w->cat_title)) : '';

        $count = dcCore::app()->blog->getCategories([])->count();

        $text = $count == 0 ?
            sprintf(__('no categories'), $count) :
            sprintf(__('one category', '%s categories', $count), $count);

        $content .= sprintf($s_line, $title, $text);
    }

    # Comment
    if ($w->comment_show) {
        $title = $w->comment_title ?
            sprintf($s_title, html::escapeHTML($w->comment_title)) : '';

        $params = [
            'post_type'         => 'post',
            'comment_status'    => 1,
            'comment_trackback' => 0,
        ];
        $count = dcCore::app()->blog->getComments($params, true)->f(0);

        $text = $count == 0 ?
            sprintf(__('no comments'), $count) :
            sprintf(__('one comment', '%s comments', $count), $count);

        $content .= sprintf($s_line, $title, $text);
    }

    # Trackback
    if ($w->trackback_show) {
        $title = $w->trackback_title ?
            sprintf($s_title, html::escapeHTML($w->trackback_title)) : '';

        $params = [
            'post_type'         => 'post',
            'comment_status'    => 1,
            'comment_trackback' => 1,
        ];
        $count = dcCore::app()->blog->getComments($params, true)->f(0);

        $text = $count == 0 ?
            sprintf(__('no trackbacks'), $count) :
            sprintf(__('one trackback', '%s trackbacks', $count), $count);

        $content .= sprintf($s_line, $title, $text);
    }

    # Tag
    if (dcCore::app()->plugins->moduleExists('tags') && $w->tag_show) {
        $title = $w->tag_title ?
            sprintf($s_title, html::escapeHTML($w->tag_title)) : '';

        $count = dcCore::app()->con->select(
            'SELECT count(M.meta_id) AS count ' .
            'FROM ' . dcCore::app()->prefix . 'meta M ' .
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
            sprintf($s_title, html::escapeHTML($w->user_title)) : '';

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
        return null;
    }

    # Display
    return $w->renderDiv(
        $w->content_only,
        'myblognumbers ' . $w->class,
        '',
        ($w->title ? $w->renderTitle(html::escapeHTML($w->title)) : '') .
            sprintf('<ul>%s</ul>', $content . $addons)
    );
}

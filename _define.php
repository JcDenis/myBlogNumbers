<?php
/**
 * @file
 * @brief       The plugin myBlogNumbers definition
 * @ingroup     myBlogNumbers
 *
 * @defgroup    myBlogNumbers Plugin myBlogNumbers.
 *
 * Show some figures of your blog.
 *
 * @author      Jean-Christian Denis (author)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
declare(strict_types=1);

$this->registerModule(
    'My blog numbers',
    'Show some figures of your blog',
    'Jean-Christian Denis, Pierre Van Glabeke',
    '2025.09.13',
    [
        'requires'    => [['core', '2.36']],
        'permissions' => 'My',
        'type'        => 'plugin',
        'support'     => 'https://github.com/JcDenis/' . $this->id . '/issues',
        'details'     => 'https://github.com/JcDenis/' . $this->id . '/',
        'repository'  => 'https://raw.githubusercontent.com/JcDenis/' . $this->id . '/master/dcstore.xml',
        'date'        => '2025-09-13T15:15:06+00:00',
    ]
);

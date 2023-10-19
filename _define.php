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
    '2023.10.19',
    [
        'requires'    => [['core', '2.28']],
        'permissions' => 'My',
        'type'        => 'plugin',
        'support'     => 'https://git.dotclear.watch/JcDenis/' . basename(__DIR__) . '/issues',
        'details'     => 'https://git.dotclear.watch/JcDenis/' . basename(__DIR__) . '/src/branch/master/README.md',
        'repository'  => 'https://git.dotclear.watch/JcDenis/' . basename(__DIR__) . '/raw/branch/master/dcstore.xml',
    ]
);

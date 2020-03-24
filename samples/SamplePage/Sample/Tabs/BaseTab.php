<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Samples;

/**
 * Class BaseTab
 */
abstract class BaseTab
{
    public $title;
    public $text;
    public $isFirst = false;

    /**
     * SampleTab constructor.
     * @param string $title
     * @param string $text
     */
    public function __construct($title = 'title', $text = 'text')
    {
        $this->title = $title;
        $this->text  = $text;
    }

    public function getTabPill()
    {
        $class = 'url-tab nav-link';
        if ($this->isFirst) {
            $class .= ' active';
        }
        return '
            <li class="nav-item">
                <a
                 class="' . $class . '"
                 role="tab"
                 aria-controls="' . $this->title . '"
                 aria-selected="false">' .
            strtoupper($this->title) .
            '</a>            
            </li>
        ';
    }

    public function getTabContent()
    {
        return '';
    }
}

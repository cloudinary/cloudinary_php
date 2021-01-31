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

require_once __DIR__ . '/BaseTab.php';

// Set PHP code colors
ini_set('highlight.comment', '#008000');
ini_set('highlight.default', '#FFFFFF');
ini_set('highlight.html', '#808080');
ini_set('highlight.keyword', '#87BDFD; font-weight: bold');
ini_set('highlight.string', '#F2D864');

/**
 * Class CodeTab
 */
class CodeTab extends BaseTab
{
    protected $keepSpaces;

    /**
     * CodeTab constructor.
     * @param $code
     * @param bool $keepSpaces
     * @param string $title
     */
    public function __construct($code, $keepSpaces = true, $title = 'PHP')
    {
        parent::__construct($title);
        $this->text = $code;
        $this->isFirst = true;
        $this->keepSpaces = $keepSpaces;
    }

    public function getTabContent()
    {
        return '
        <div class="row no-gutters tab-content align-items-center rounded" >
          <div
           class="php-content ml-3 tab-pane fade show active"
            role="tabpanel"
             aria-labelledby="php-tab">
             <code>' . getColoredPhp($this->text, $this->keepSpaces) . '</code>
          </div>
        ';
    }
}

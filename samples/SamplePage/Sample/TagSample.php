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

use Cloudinary\Tag\BaseTag;

require_once __DIR__ . '/Sample.php';
require_once __DIR__ . '/Tabs/CodeTab.php';
require_once __DIR__ . '/Tabs/TagTab.php';

/**
 * Class TagSample
 */
class TagSample extends Sample
{
    /**
     * @var BaseTag
     */
    private $tag;

    /**
     * TagSample constructor.
     * @param $tag
     * @param $code
     * @param string $name
     */
    public function __construct($tag, $code, $name = 'sample')
    {
        parent::__construct($name, $code);

        $this->tag = $tag;
    }

    /**
     * @return array
     */
    protected function createTabs()
    {
        return [
            $this->createCodeTab(),
            new TagTab(
                $this->code,
                $this->tag
            )
        ];
    }

    protected function getExample()
    {
        return $this->getExampleHtml($this->getUrl(), $this->tag);
    }

    protected function getUrl()
    {
        // TODO: return actual url from tag
        return 'http://cloudinary.com';
    }

    /**
     * @param string  $url
     * @param BaseTag $tag
     *
     * @return string
     */
    protected function getExampleHtml($url = '', $tag = null)
    {
        return '
            <div class="col-12 d-flex mt-1" >' .
               $tag->addClass('z - depth - 1')->setAttribute('style', 'margin:auto; max-width: 900px;') . '
            </div>
        </div>
        ';
    }
}

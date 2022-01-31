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
use Cloudinary\Tag\ImageTag;

/**
 * Class Sample
 */
abstract class Sample
{
    /**
     * @var bool
     */
    public $keepSpaces = true;

    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $code;
    /**
     * @var array
     */
    private $tabs = [];

    /**
     * @var string
     */
    protected $tabsWrapperStart = '
        <div class="row pb-5" >
            <div class="col-12 tabs" >
                <!-- Nav pills -->
                    <div>
                        <ul class="nav nav-pills" role="tablist">';

    /**
     * @var string
     */
    protected $tabsWrapperEnd = '</ul></div>';

    /**
     * Sample constructor.
     * @param string $name
     * @param string $code
     */
    public function __construct($name = 'sample', $code = '')
    {
        $this->name = $name;
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTabs() . $this->getExample();
    }

    protected function createTabs()
    {
        return [];
    }

    /**
     * Gets this sample tabs
     */
    protected function getTabs()
    {
        $this->tabs = $this->createTabs();
        return $this->getTabPills() . $this->getTabContents();
    }

    /**
     * @return string
     */
    protected function getTabPills()
    {
        $result = $this->tabsWrapperStart;

        foreach ($this->tabs as $tab) {
            $result .= $tab->getTabPill();
        }

        $result .= $this->tabsWrapperEnd;

        return $result;
    }

    /**
     * @return string
     */
    protected function getTabContents()
    {
        $result = '';
        foreach ($this->tabs as $tab) {
            $result .= $tab->getTabContent();
        }

        return $result;
    }


    /**
     * @return string
     */
    protected function getExample()
    {
        return $this->getExampleHtml('', new ImageTag(''));
    }

    /**
     * @param string $url
     * @param BaseTag $tag
     * @return string
     */
    protected function getExampleHtml($url, $tag)
    {
        return '
            <div class="col-12 d-flex mt-1" >
                <a
                 style="margin:auto"
                 href="' . $url . '"
                 target="_blank">' .
                 $tag->addClass('z - depth - 1') . '
                </a>
            </div>
        </div>
        ';
    }

    protected function createCodeTab()
    {
        return new CodeTab($this->code, $this->keepSpaces);
    }
}

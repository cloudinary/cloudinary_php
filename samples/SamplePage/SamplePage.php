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

use Exception;

require_once __DIR__ . '/SamplePageUtils.php';

/**
 * Class SamplePage
 */
class SamplePage
{
    public $title;
    public $description;
    public $htmlContent = '<h2>Overview</h2>
        <p>
        Cloudinary PHP SDK v2 is the first SDK v2 release. <br>
        Designed to change the way developers interact with our service,
        it brings a more language oriented approach and improved developer experience.
        There\'s increased discoverability to help developers figure out what can be done
        and to keep mistakes to a minimum.<br>
        This page is part of the Cloudinary PHP SDK v2. You can install the new SDK,
        play around with the syntax and see the results in live view.
        </p>
        <p>
        Below you will find the code to write in your application, the corresponding generated URL and the asset itself.
        </p>
        ';

    protected $sampleGroups;
    public $navLinks = [
        ['url' => 'transformation-samples.php', 'text' => 'Transformations'],
        ['url' => 'tag-samples.php', 'text' => 'Tags'],
        ['url' => 'edit-me.php', 'text' => 'Edit Me']
    ];
    public $currentNavLink = 0;

    /**
     * SamplePage constructor.
     *
     * @param string $title
     * @param string $description
     * @param array  $sampleGroups
     * @param null   $htmlContent
     */
    public function __construct(
        $title = 'Sample Page',
        $description = 'Sample Page Description',
        $sampleGroups = [],
        $htmlContent = null
    ) {
        $this->title        = $title;
        $this->description  = $description;
        $this->sampleGroups = $sampleGroups;
        $this->htmlContent  = $htmlContent ?: $this->htmlContent;
    }

    /**
     * @param array $group
     */
    public function addGroup($group = [])
    {
        $this->sampleGroups[] = $group;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $result = '';
        try {
            $result = getPageStart($this->title, $this->description);
            $result .= getNavBar($this->navLinks, $this->currentNavLink);
            $result .= getSideBar($this->sampleGroups);
            $result .= getContent($this->title, $this->sampleGroups, $this->htmlContent);
            $result .= getPageScripts();
            $result .= getPageEnd();
        } catch (Exception $e) {
            echo $e;
        }

        return $result;
    }
}

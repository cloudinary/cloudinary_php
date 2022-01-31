<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Tag;

use Cloudinary\Configuration\Configuration;
use Cloudinary\Tag\SrcSet;

/**
 * Class GetBreakpointsTest
 */
final class GetBreakpointsTest extends TagTestCase
{
    const CUSTOM_BREAKPOINTS       = [500, 1000, 1500];
    const AUTO_OPTIMAL_BREAKPOINTS = [828, 1366, 1536, 1920, 3840];

    public function testCustomBreakpoints()
    {
        $c = new Configuration(Configuration::instance());

        $c->responsiveBreakpoints->breakpoints = self::CUSTOM_BREAKPOINTS;

        self::assertEquals(self::CUSTOM_BREAKPOINTS, (new SrcSet(self::IMAGE_NAME, $c))->getBreakpoints());
    }

    public function testDefaultNoBreakpoints()
    {
        $c = new Configuration(Configuration::instance());

        self::assertEquals([], (new SrcSet(self::IMAGE_NAME, $c))->getBreakpoints());
    }

    public function testAutoOptimalBreakpoints()
    {
        $c = new Configuration(Configuration::instance());

        $c->responsiveBreakpoints->autoOptimalBreakpoints = true;

        self::assertEquals(self::AUTO_OPTIMAL_BREAKPOINTS, (new SrcSet(self::IMAGE_NAME, $c))->getBreakpoints());
    }

    public function testCustomBreakpointsAndAutoOptimal()
    {
        $c = new Configuration(Configuration::instance());

        $c->responsiveBreakpoints->breakpoints            = self::CUSTOM_BREAKPOINTS;
        $c->responsiveBreakpoints->autoOptimalBreakpoints = true;

        self::assertEquals(self::CUSTOM_BREAKPOINTS, (new SrcSet(self::IMAGE_NAME, $c))->getBreakpoints());
    }

    public function testAutoOptimalBreakpointsMinMaxAboveThreshold()
    {
        $c = new Configuration(Configuration::instance());

        $c->responsiveBreakpoints->autoOptimalBreakpoints = true;
        $c->responsiveBreakpoints->minWidth               = 1000;
        $c->responsiveBreakpoints->maxWidth               = 2000;

        self::assertEquals([1280, 1366, 1536, 1600, 1920], (new SrcSet(self::IMAGE_NAME, $c))->getBreakpoints());
    }

    public function testAutoOptimalBreakpointsMinBelowThreshold()
    {
        $c = new Configuration(Configuration::instance());

        $c->responsiveBreakpoints->autoOptimalBreakpoints = true;
        $c->responsiveBreakpoints->minWidth               = 360;
        $c->responsiveBreakpoints->maxWidth               = 1700;

        self::assertEquals([750, 828, 1280, 1366, 1536], (new SrcSet(self::IMAGE_NAME, $c))->getBreakpoints());
    }

    public function testAutoOptimalBreakpointsBasic()
    {
        $c = new Configuration(Configuration::instance());

        $c->responsiveBreakpoints->autoOptimalBreakpoints = true;
        $c->responsiveBreakpoints->minWidth               = 360;
        $c->responsiveBreakpoints->maxWidth               = 1000;

        self::assertEquals([750, 828], (new SrcSet(self::IMAGE_NAME, $c))->getBreakpoints());
    }

    public function testAutoOptimalBreakpointsRelativeWidth()
    {
        $c = new Configuration(Configuration::instance());

        $c->responsiveBreakpoints->autoOptimalBreakpoints = true;
        $c->responsiveBreakpoints->minWidth               = 360;
        $c->responsiveBreakpoints->maxWidth               = 1000;
        $c->tag->relativeWidth                            = 0.2;

        self::assertEquals([150, 166], (new SrcSet(self::IMAGE_NAME, $c))->getBreakpoints());
    }

    public function testAutoOptimalBreakpointsPhysicalMinWidthGreaterThanPhysicalMaxWidth()
    {
        $c = new Configuration(Configuration::instance());

        $c->responsiveBreakpoints->autoOptimalBreakpoints = true;
        $c->responsiveBreakpoints->minWidth               = 750;
        $c->responsiveBreakpoints->maxWidth               = 1000;

        self::assertEquals([1280, 1366, 1440], (new SrcSet(self::IMAGE_NAME, $c))->getBreakpoints());
    }

    public function testAutoOptimalBreakpointsMinEqMax()
    {
        $c = new Configuration(Configuration::instance());

        $c->responsiveBreakpoints->autoOptimalBreakpoints = true;
        $c->responsiveBreakpoints->minWidth               = 750;
        $c->responsiveBreakpoints->maxWidth               = 750;

        self::assertEquals([1500], (new SrcSet(self::IMAGE_NAME, $c))->getBreakpoints());
    }

    public function testAutoOptimalBreakpointsCustomMaxImages()
    {
        $c = new Configuration(Configuration::instance());

        $c->responsiveBreakpoints->autoOptimalBreakpoints = true;
        $c->responsiveBreakpoints->maxImages              = 3;

        self::assertEquals([828, 1366, 1920], (new SrcSet(self::IMAGE_NAME, $c))->getBreakpoints());
    }

    public function testAutoOptimalBreakpointsInvalid()
    {
        $c = new Configuration(Configuration::instance());

        $c->responsiveBreakpoints->autoOptimalBreakpoints = true;
        $c->responsiveBreakpoints->minWidth               = 2000;
        $c->responsiveBreakpoints->maxWidth               = 1000;

        $this->expectExceptionMessage('minWidth must be less than maxWidth');

        (new SrcSet(self::IMAGE_NAME, $c))->getBreakpoints();
    }
}

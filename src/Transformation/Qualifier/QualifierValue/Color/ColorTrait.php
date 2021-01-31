<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Argument;

use Cloudinary\StringUtils;

/**
 * Trait NamedColorTrait
 *
 * @api
 */
trait ColorTrait
{
    /**
     * Color snow.
     *
     * @return static
     */
    public static function snow()
    {
        return new static(Color::SNOW);
    }

    /**
     * Color snow1.
     *
     * @return static
     */
    public static function snow1()
    {
        return new static(Color::SNOW1);
    }

    /**
     * Color snow2.
     *
     * @return static
     */
    public static function snow2()
    {
        return new static(Color::SNOW2);
    }

    /**
     * Color rosybrown1.
     *
     * @return static
     */
    public static function rosybrown1()
    {
        return new static(Color::ROSYBROWN1);
    }

    /**
     * Color rosybrown2.
     *
     * @return static
     */
    public static function rosybrown2()
    {
        return new static(Color::ROSYBROWN2);
    }

    /**
     * Color snow3.
     *
     * @return static
     */
    public static function snow3()
    {
        return new static(Color::SNOW3);
    }

    /**
     * Color lightcoral.
     *
     * @return static
     */
    public static function lightcoral()
    {
        return new static(Color::LIGHTCORAL);
    }

    /**
     * Color indianred1.
     *
     * @return static
     */
    public static function indianred1()
    {
        return new static(Color::INDIANRED1);
    }

    /**
     * Color rosybrown3.
     *
     * @return static
     */
    public static function rosybrown3()
    {
        return new static(Color::ROSYBROWN3);
    }

    /**
     * Color indianred2.
     *
     * @return static
     */
    public static function indianred2()
    {
        return new static(Color::INDIANRED2);
    }

    /**
     * Color rosybrown.
     *
     * @return static
     */
    public static function rosybrown()
    {
        return new static(Color::ROSYBROWN);
    }

    /**
     * Color brown1.
     *
     * @return static
     */
    public static function brown1()
    {
        return new static(Color::BROWN1);
    }

    /**
     * Color firebrick1.
     *
     * @return static
     */
    public static function firebrick1()
    {
        return new static(Color::FIREBRICK1);
    }

    /**
     * Color brown2.
     *
     * @return static
     */
    public static function brown2()
    {
        return new static(Color::BROWN2);
    }

    /**
     * Color indianred.
     *
     * @return static
     */
    public static function indianred()
    {
        return new static(Color::INDIANRED);
    }

    /**
     * Color indianred3.
     *
     * @return static
     */
    public static function indianred3()
    {
        return new static(Color::INDIANRED3);
    }

    /**
     * Color firebrick2.
     *
     * @return static
     */
    public static function firebrick2()
    {
        return new static(Color::FIREBRICK2);
    }

    /**
     * Color snow4.
     *
     * @return static
     */
    public static function snow4()
    {
        return new static(Color::SNOW4);
    }

    /**
     * Color brown3.
     *
     * @return static
     */
    public static function brown3()
    {
        return new static(Color::BROWN3);
    }

    /**
     * Color red.
     *
     * @return static
     */
    public static function red()
    {
        return new static(Color::RED);
    }

    /**
     * Color red1.
     *
     * @return static
     */
    public static function red1()
    {
        return new static(Color::RED1);
    }

    /**
     * Color rosybrown4.
     *
     * @return static
     */
    public static function rosybrown4()
    {
        return new static(Color::ROSYBROWN4);
    }

    /**
     * Color firebrick3.
     *
     * @return static
     */
    public static function firebrick3()
    {
        return new static(Color::FIREBRICK3);
    }

    /**
     * Color red2.
     *
     * @return static
     */
    public static function red2()
    {
        return new static(Color::RED2);
    }

    /**
     * Color firebrick.
     *
     * @return static
     */
    public static function firebrick()
    {
        return new static(Color::FIREBRICK);
    }

    /**
     * Color brown.
     *
     * @return static
     */
    public static function brown()
    {
        return new static(Color::BROWN);
    }

    /**
     * Color red3.
     *
     * @return static
     */
    public static function red3()
    {
        return new static(Color::RED3);
    }

    /**
     * Color indianred4.
     *
     * @return static
     */
    public static function indianred4()
    {
        return new static(Color::INDIANRED4);
    }

    /**
     * Color brown4.
     *
     * @return static
     */
    public static function brown4()
    {
        return new static(Color::BROWN4);
    }

    /**
     * Color firebrick4.
     *
     * @return static
     */
    public static function firebrick4()
    {
        return new static(Color::FIREBRICK4);
    }

    /**
     * Color darkred.
     *
     * @return static
     */
    public static function darkred()
    {
        return new static(Color::DARKRED);
    }

    /**
     * Color red4.
     *
     * @return static
     */
    public static function red4()
    {
        return new static(Color::RED4);
    }

    /**
     * Color lightpink1.
     *
     * @return static
     */
    public static function lightpink1()
    {
        return new static(Color::LIGHTPINK1);
    }

    /**
     * Color lightpink3.
     *
     * @return static
     */
    public static function lightpink3()
    {
        return new static(Color::LIGHTPINK3);
    }

    /**
     * Color lightpink4.
     *
     * @return static
     */
    public static function lightpink4()
    {
        return new static(Color::LIGHTPINK4);
    }

    /**
     * Color lightpink2.
     *
     * @return static
     */
    public static function lightpink2()
    {
        return new static(Color::LIGHTPINK2);
    }

    /**
     * Color lightpink.
     *
     * @return static
     */
    public static function lightpink()
    {
        return new static(Color::LIGHTPINK);
    }

    /**
     * Color pink.
     *
     * @return static
     */
    public static function pink()
    {
        return new static(Color::PINK);
    }

    /**
     * Color crimson.
     *
     * @return static
     */
    public static function crimson()
    {
        return new static(Color::CRIMSON);
    }

    /**
     * Color pink1.
     *
     * @return static
     */
    public static function pink1()
    {
        return new static(Color::PINK1);
    }

    /**
     * Color pink2.
     *
     * @return static
     */
    public static function pink2()
    {
        return new static(Color::PINK2);
    }

    /**
     * Color pink3.
     *
     * @return static
     */
    public static function pink3()
    {
        return new static(Color::PINK3);
    }

    /**
     * Color pink4.
     *
     * @return static
     */
    public static function pink4()
    {
        return new static(Color::PINK4);
    }

    /**
     * Color palevioletred4.
     *
     * @return static
     */
    public static function palevioletred4()
    {
        return new static(Color::PALEVIOLETRED4);
    }

    /**
     * Color palevioletred.
     *
     * @return static
     */
    public static function palevioletred()
    {
        return new static(Color::PALEVIOLETRED);
    }

    /**
     * Color palevioletred2.
     *
     * @return static
     */
    public static function palevioletred2()
    {
        return new static(Color::PALEVIOLETRED2);
    }

    /**
     * Color palevioletred1.
     *
     * @return static
     */
    public static function palevioletred1()
    {
        return new static(Color::PALEVIOLETRED1);
    }

    /**
     * Color palevioletred3.
     *
     * @return static
     */
    public static function palevioletred3()
    {
        return new static(Color::PALEVIOLETRED3);
    }

    /**
     * Color lavenderblush.
     *
     * @return static
     */
    public static function lavenderblush()
    {
        return new static(Color::LAVENDERBLUSH);
    }

    /**
     * Color lavenderblush1.
     *
     * @return static
     */
    public static function lavenderblush1()
    {
        return new static(Color::LAVENDERBLUSH1);
    }

    /**
     * Color lavenderblush3.
     *
     * @return static
     */
    public static function lavenderblush3()
    {
        return new static(Color::LAVENDERBLUSH3);
    }

    /**
     * Color lavenderblush2.
     *
     * @return static
     */
    public static function lavenderblush2()
    {
        return new static(Color::LAVENDERBLUSH2);
    }

    /**
     * Color lavenderblush4.
     *
     * @return static
     */
    public static function lavenderblush4()
    {
        return new static(Color::LAVENDERBLUSH4);
    }

    /**
     * Color maroon.
     *
     * @return static
     */
    public static function maroon()
    {
        return new static(Color::MAROON);
    }

    /**
     * Color hotpink3.
     *
     * @return static
     */
    public static function hotpink3()
    {
        return new static(Color::HOTPINK3);
    }

    /**
     * Color violetred3.
     *
     * @return static
     */
    public static function violetred3()
    {
        return new static(Color::VIOLETRED3);
    }

    /**
     * Color violetred1.
     *
     * @return static
     */
    public static function violetred1()
    {
        return new static(Color::VIOLETRED1);
    }

    /**
     * Color violetred2.
     *
     * @return static
     */
    public static function violetred2()
    {
        return new static(Color::VIOLETRED2);
    }

    /**
     * Color violetred4.
     *
     * @return static
     */
    public static function violetred4()
    {
        return new static(Color::VIOLETRED4);
    }

    /**
     * Color hotpink2.
     *
     * @return static
     */
    public static function hotpink2()
    {
        return new static(Color::HOTPINK2);
    }

    /**
     * Color hotpink1.
     *
     * @return static
     */
    public static function hotpink1()
    {
        return new static(Color::HOTPINK1);
    }

    /**
     * Color hotpink4.
     *
     * @return static
     */
    public static function hotpink4()
    {
        return new static(Color::HOTPINK4);
    }

    /**
     * Color hotpink.
     *
     * @return static
     */
    public static function hotpink()
    {
        return new static(Color::HOTPINK);
    }

    /**
     * Color deeppink.
     *
     * @return static
     */
    public static function deeppink()
    {
        return new static(Color::DEEPPINK);
    }

    /**
     * Color deeppink1.
     *
     * @return static
     */
    public static function deeppink1()
    {
        return new static(Color::DEEPPINK1);
    }

    /**
     * Color deeppink2.
     *
     * @return static
     */
    public static function deeppink2()
    {
        return new static(Color::DEEPPINK2);
    }

    /**
     * Color deeppink3.
     *
     * @return static
     */
    public static function deeppink3()
    {
        return new static(Color::DEEPPINK3);
    }

    /**
     * Color deeppink4.
     *
     * @return static
     */
    public static function deeppink4()
    {
        return new static(Color::DEEPPINK4);
    }

    /**
     * Color maroon1.
     *
     * @return static
     */
    public static function maroon1()
    {
        return new static(Color::MAROON1);
    }

    /**
     * Color maroon2.
     *
     * @return static
     */
    public static function maroon2()
    {
        return new static(Color::MAROON2);
    }

    /**
     * Color maroon3.
     *
     * @return static
     */
    public static function maroon3()
    {
        return new static(Color::MAROON3);
    }

    /**
     * Color maroon4.
     *
     * @return static
     */
    public static function maroon4()
    {
        return new static(Color::MAROON4);
    }

    /**
     * Color mediumvioletred.
     *
     * @return static
     */
    public static function mediumvioletred()
    {
        return new static(Color::MEDIUMVIOLETRED);
    }

    /**
     * Color violetred.
     *
     * @return static
     */
    public static function violetred()
    {
        return new static(Color::VIOLETRED);
    }

    /**
     * Color orchid2.
     *
     * @return static
     */
    public static function orchid2()
    {
        return new static(Color::ORCHID2);
    }

    /**
     * Color orchid.
     *
     * @return static
     */
    public static function orchid()
    {
        return new static(Color::ORCHID);
    }

    /**
     * Color orchid1.
     *
     * @return static
     */
    public static function orchid1()
    {
        return new static(Color::ORCHID1);
    }

    /**
     * Color orchid3.
     *
     * @return static
     */
    public static function orchid3()
    {
        return new static(Color::ORCHID3);
    }

    /**
     * Color orchid4.
     *
     * @return static
     */
    public static function orchid4()
    {
        return new static(Color::ORCHID4);
    }

    /**
     * Color thistle1.
     *
     * @return static
     */
    public static function thistle1()
    {
        return new static(Color::THISTLE1);
    }

    /**
     * Color thistle2.
     *
     * @return static
     */
    public static function thistle2()
    {
        return new static(Color::THISTLE2);
    }

    /**
     * Color plum1.
     *
     * @return static
     */
    public static function plum1()
    {
        return new static(Color::PLUM1);
    }

    /**
     * Color plum2.
     *
     * @return static
     */
    public static function plum2()
    {
        return new static(Color::PLUM2);
    }

    /**
     * Color thistle.
     *
     * @return static
     */
    public static function thistle()
    {
        return new static(Color::THISTLE);
    }

    /**
     * Color thistle3.
     *
     * @return static
     */
    public static function thistle3()
    {
        return new static(Color::THISTLE3);
    }

    /**
     * Color plum.
     *
     * @return static
     */
    public static function plum()
    {
        return new static(Color::PLUM);
    }

    /**
     * Color violet.
     *
     * @return static
     */
    public static function violet()
    {
        return new static(Color::VIOLET);
    }

    /**
     * Color plum3.
     *
     * @return static
     */
    public static function plum3()
    {
        return new static(Color::PLUM3);
    }

    /**
     * Color thistle4.
     *
     * @return static
     */
    public static function thistle4()
    {
        return new static(Color::THISTLE4);
    }

    /**
     * Color fuchsia.
     *
     * @return static
     */
    public static function fuchsia()
    {
        return new static(Color::FUCHSIA);
    }

    /**
     * Color magenta.
     *
     * @return static
     */
    public static function magenta()
    {
        return new static(Color::MAGENTA);
    }

    /**
     * Color magenta1.
     *
     * @return static
     */
    public static function magenta1()
    {
        return new static(Color::MAGENTA1);
    }

    /**
     * Color plum4.
     *
     * @return static
     */
    public static function plum4()
    {
        return new static(Color::PLUM4);
    }

    /**
     * Color magenta2.
     *
     * @return static
     */
    public static function magenta2()
    {
        return new static(Color::MAGENTA2);
    }

    /**
     * Color magenta3.
     *
     * @return static
     */
    public static function magenta3()
    {
        return new static(Color::MAGENTA3);
    }

    /**
     * Color darkmagenta.
     *
     * @return static
     */
    public static function darkmagenta()
    {
        return new static(Color::DARKMAGENTA);
    }

    /**
     * Color magenta4.
     *
     * @return static
     */
    public static function magenta4()
    {
        return new static(Color::MAGENTA4);
    }

    /**
     * Color purple.
     *
     * @return static
     */
    public static function purple()
    {
        return new static(Color::PURPLE);
    }

    /**
     * Color mediumorchid.
     *
     * @return static
     */
    public static function mediumorchid()
    {
        return new static(Color::MEDIUMORCHID);
    }

    /**
     * Color mediumorchid1.
     *
     * @return static
     */
    public static function mediumorchid1()
    {
        return new static(Color::MEDIUMORCHID1);
    }

    /**
     * Color mediumorchid2.
     *
     * @return static
     */
    public static function mediumorchid2()
    {
        return new static(Color::MEDIUMORCHID2);
    }

    /**
     * Color mediumorchid3.
     *
     * @return static
     */
    public static function mediumorchid3()
    {
        return new static(Color::MEDIUMORCHID3);
    }

    /**
     * Color mediumorchid4.
     *
     * @return static
     */
    public static function mediumorchid4()
    {
        return new static(Color::MEDIUMORCHID4);
    }

    /**
     * Color darkviolet.
     *
     * @return static
     */
    public static function darkviolet()
    {
        return new static(Color::DARKVIOLET);
    }

    /**
     * Color darkorchid.
     *
     * @return static
     */
    public static function darkorchid()
    {
        return new static(Color::DARKORCHID);
    }

    /**
     * Color darkorchid1.
     *
     * @return static
     */
    public static function darkorchid1()
    {
        return new static(Color::DARKORCHID1);
    }

    /**
     * Color darkorchid3.
     *
     * @return static
     */
    public static function darkorchid3()
    {
        return new static(Color::DARKORCHID3);
    }

    /**
     * Color darkorchid2.
     *
     * @return static
     */
    public static function darkorchid2()
    {
        return new static(Color::DARKORCHID2);
    }

    /**
     * Color darkorchid4.
     *
     * @return static
     */
    public static function darkorchid4()
    {
        return new static(Color::DARKORCHID4);
    }

    /**
     * Color indigo.
     *
     * @return static
     */
    public static function indigo()
    {
        return new static(Color::INDIGO);
    }

    /**
     * Color blueviolet.
     *
     * @return static
     */
    public static function blueviolet()
    {
        return new static(Color::BLUEVIOLET);
    }

    /**
     * Color purple2.
     *
     * @return static
     */
    public static function purple2()
    {
        return new static(Color::PURPLE2);
    }

    /**
     * Color purple3.
     *
     * @return static
     */
    public static function purple3()
    {
        return new static(Color::PURPLE3);
    }

    /**
     * Color purple4.
     *
     * @return static
     */
    public static function purple4()
    {
        return new static(Color::PURPLE4);
    }

    /**
     * Color purple1.
     *
     * @return static
     */
    public static function purple1()
    {
        return new static(Color::PURPLE1);
    }

    /**
     * Color mediumpurple.
     *
     * @return static
     */
    public static function mediumpurple()
    {
        return new static(Color::MEDIUMPURPLE);
    }

    /**
     * Color mediumpurple1.
     *
     * @return static
     */
    public static function mediumpurple1()
    {
        return new static(Color::MEDIUMPURPLE1);
    }

    /**
     * Color mediumpurple2.
     *
     * @return static
     */
    public static function mediumpurple2()
    {
        return new static(Color::MEDIUMPURPLE2);
    }

    /**
     * Color mediumpurple3.
     *
     * @return static
     */
    public static function mediumpurple3()
    {
        return new static(Color::MEDIUMPURPLE3);
    }

    /**
     * Color mediumpurple4.
     *
     * @return static
     */
    public static function mediumpurple4()
    {
        return new static(Color::MEDIUMPURPLE4);
    }

    /**
     * Color darkslateblue.
     *
     * @return static
     */
    public static function darkslateblue()
    {
        return new static(Color::DARKSLATEBLUE);
    }

    /**
     * Color lightslateblue.
     *
     * @return static
     */
    public static function lightslateblue()
    {
        return new static(Color::LIGHTSLATEBLUE);
    }

    /**
     * Color mediumslateblue.
     *
     * @return static
     */
    public static function mediumslateblue()
    {
        return new static(Color::MEDIUMSLATEBLUE);
    }

    /**
     * Color slateblue.
     *
     * @return static
     */
    public static function slateblue()
    {
        return new static(Color::SLATEBLUE);
    }

    /**
     * Color slateblue1.
     *
     * @return static
     */
    public static function slateblue1()
    {
        return new static(Color::SLATEBLUE1);
    }

    /**
     * Color slateblue2.
     *
     * @return static
     */
    public static function slateblue2()
    {
        return new static(Color::SLATEBLUE2);
    }

    /**
     * Color slateblue3.
     *
     * @return static
     */
    public static function slateblue3()
    {
        return new static(Color::SLATEBLUE3);
    }

    /**
     * Color slateblue4.
     *
     * @return static
     */
    public static function slateblue4()
    {
        return new static(Color::SLATEBLUE4);
    }

    /**
     * Color ghostwhite.
     *
     * @return static
     */
    public static function ghostwhite()
    {
        return new static(Color::GHOSTWHITE);
    }

    /**
     * Color lavender.
     *
     * @return static
     */
    public static function lavender()
    {
        return new static(Color::LAVENDER);
    }

    /**
     * Color blue.
     *
     * @return static
     */
    public static function blue()
    {
        return new static(Color::BLUE);
    }

    /**
     * Color blue1.
     *
     * @return static
     */
    public static function blue1()
    {
        return new static(Color::BLUE1);
    }

    /**
     * Color blue2.
     *
     * @return static
     */
    public static function blue2()
    {
        return new static(Color::BLUE2);
    }

    /**
     * Color blue3.
     *
     * @return static
     */
    public static function blue3()
    {
        return new static(Color::BLUE3);
    }

    /**
     * Color mediumblue.
     *
     * @return static
     */
    public static function mediumblue()
    {
        return new static(Color::MEDIUMBLUE);
    }

    /**
     * Color blue4.
     *
     * @return static
     */
    public static function blue4()
    {
        return new static(Color::BLUE4);
    }

    /**
     * Color darkblue.
     *
     * @return static
     */
    public static function darkblue()
    {
        return new static(Color::DARKBLUE);
    }

    /**
     * Color midnightblue.
     *
     * @return static
     */
    public static function midnightblue()
    {
        return new static(Color::MIDNIGHTBLUE);
    }

    /**
     * Color navy.
     *
     * @return static
     */
    public static function navy()
    {
        return new static(Color::NAVY);
    }

    /**
     * Color navyblue.
     *
     * @return static
     */
    public static function navyblue()
    {
        return new static(Color::NAVYBLUE);
    }

    /**
     * Color royalblue.
     *
     * @return static
     */
    public static function royalblue()
    {
        return new static(Color::ROYALBLUE);
    }

    /**
     * Color royalblue1.
     *
     * @return static
     */
    public static function royalblue1()
    {
        return new static(Color::ROYALBLUE1);
    }

    /**
     * Color royalblue2.
     *
     * @return static
     */
    public static function royalblue2()
    {
        return new static(Color::ROYALBLUE2);
    }

    /**
     * Color royalblue3.
     *
     * @return static
     */
    public static function royalblue3()
    {
        return new static(Color::ROYALBLUE3);
    }

    /**
     * Color royalblue4.
     *
     * @return static
     */
    public static function royalblue4()
    {
        return new static(Color::ROYALBLUE4);
    }

    /**
     * Color cornflowerblue.
     *
     * @return static
     */
    public static function cornflowerblue()
    {
        return new static(Color::CORNFLOWERBLUE);
    }

    /**
     * Color lightsteelblue.
     *
     * @return static
     */
    public static function lightsteelblue()
    {
        return new static(Color::LIGHTSTEELBLUE);
    }

    /**
     * Color lightsteelblue1.
     *
     * @return static
     */
    public static function lightsteelblue1()
    {
        return new static(Color::LIGHTSTEELBLUE1);
    }

    /**
     * Color lightsteelblue2.
     *
     * @return static
     */
    public static function lightsteelblue2()
    {
        return new static(Color::LIGHTSTEELBLUE2);
    }

    /**
     * Color lightsteelblue3.
     *
     * @return static
     */
    public static function lightsteelblue3()
    {
        return new static(Color::LIGHTSTEELBLUE3);
    }

    /**
     * Color lightsteelblue4.
     *
     * @return static
     */
    public static function lightsteelblue4()
    {
        return new static(Color::LIGHTSTEELBLUE4);
    }

    /**
     * Color slategray4.
     *
     * @return static
     */
    public static function slategray4()
    {
        return new static(Color::SLATEGRAY4);
    }

    /**
     * Color slategray1.
     *
     * @return static
     */
    public static function slategray1()
    {
        return new static(Color::SLATEGRAY1);
    }

    /**
     * Color slategray2.
     *
     * @return static
     */
    public static function slategray2()
    {
        return new static(Color::SLATEGRAY2);
    }

    /**
     * Color slategray3.
     *
     * @return static
     */
    public static function slategray3()
    {
        return new static(Color::SLATEGRAY3);
    }

    /**
     * Color lightslategray.
     *
     * @return static
     */
    public static function lightslategray()
    {
        return new static(Color::LIGHTSLATEGRAY);
    }

    /**
     * Color lightslategrey.
     *
     * @return static
     */
    public static function lightslategrey()
    {
        return new static(Color::LIGHTSLATEGREY);
    }

    /**
     * Color slategray.
     *
     * @return static
     */
    public static function slategray()
    {
        return new static(Color::SLATEGRAY);
    }

    /**
     * Color slategrey.
     *
     * @return static
     */
    public static function slategrey()
    {
        return new static(Color::SLATEGREY);
    }

    /**
     * Color dodgerblue.
     *
     * @return static
     */
    public static function dodgerblue()
    {
        return new static(Color::DODGERBLUE);
    }

    /**
     * Color dodgerblue1.
     *
     * @return static
     */
    public static function dodgerblue1()
    {
        return new static(Color::DODGERBLUE1);
    }

    /**
     * Color dodgerblue2.
     *
     * @return static
     */
    public static function dodgerblue2()
    {
        return new static(Color::DODGERBLUE2);
    }

    /**
     * Color dodgerblue4.
     *
     * @return static
     */
    public static function dodgerblue4()
    {
        return new static(Color::DODGERBLUE4);
    }

    /**
     * Color dodgerblue3.
     *
     * @return static
     */
    public static function dodgerblue3()
    {
        return new static(Color::DODGERBLUE3);
    }

    /**
     * Color aliceblue.
     *
     * @return static
     */
    public static function aliceblue()
    {
        return new static(Color::ALICEBLUE);
    }

    /**
     * Color steelblue4.
     *
     * @return static
     */
    public static function steelblue4()
    {
        return new static(Color::STEELBLUE4);
    }

    /**
     * Color steelblue.
     *
     * @return static
     */
    public static function steelblue()
    {
        return new static(Color::STEELBLUE);
    }

    /**
     * Color steelblue1.
     *
     * @return static
     */
    public static function steelblue1()
    {
        return new static(Color::STEELBLUE1);
    }

    /**
     * Color steelblue2.
     *
     * @return static
     */
    public static function steelblue2()
    {
        return new static(Color::STEELBLUE2);
    }

    /**
     * Color steelblue3.
     *
     * @return static
     */
    public static function steelblue3()
    {
        return new static(Color::STEELBLUE3);
    }

    /**
     * Color skyblue4.
     *
     * @return static
     */
    public static function skyblue4()
    {
        return new static(Color::SKYBLUE4);
    }

    /**
     * Color skyblue1.
     *
     * @return static
     */
    public static function skyblue1()
    {
        return new static(Color::SKYBLUE1);
    }

    /**
     * Color skyblue2.
     *
     * @return static
     */
    public static function skyblue2()
    {
        return new static(Color::SKYBLUE2);
    }

    /**
     * Color skyblue3.
     *
     * @return static
     */
    public static function skyblue3()
    {
        return new static(Color::SKYBLUE3);
    }

    /**
     * Color lightskyblue.
     *
     * @return static
     */
    public static function lightskyblue()
    {
        return new static(Color::LIGHTSKYBLUE);
    }

    /**
     * Color lightskyblue4.
     *
     * @return static
     */
    public static function lightskyblue4()
    {
        return new static(Color::LIGHTSKYBLUE4);
    }

    /**
     * Color lightskyblue1.
     *
     * @return static
     */
    public static function lightskyblue1()
    {
        return new static(Color::LIGHTSKYBLUE1);
    }

    /**
     * Color lightskyblue2.
     *
     * @return static
     */
    public static function lightskyblue2()
    {
        return new static(Color::LIGHTSKYBLUE2);
    }

    /**
     * Color lightskyblue3.
     *
     * @return static
     */
    public static function lightskyblue3()
    {
        return new static(Color::LIGHTSKYBLUE3);
    }

    /**
     * Color skyblue.
     *
     * @return static
     */
    public static function skyblue()
    {
        return new static(Color::SKYBLUE);
    }

    /**
     * Color lightblue3.
     *
     * @return static
     */
    public static function lightblue3()
    {
        return new static(Color::LIGHTBLUE3);
    }

    /**
     * Color deepskyblue.
     *
     * @return static
     */
    public static function deepskyblue()
    {
        return new static(Color::DEEPSKYBLUE);
    }

    /**
     * Color deepskyblue1.
     *
     * @return static
     */
    public static function deepskyblue1()
    {
        return new static(Color::DEEPSKYBLUE1);
    }

    /**
     * Color deepskyblue2.
     *
     * @return static
     */
    public static function deepskyblue2()
    {
        return new static(Color::DEEPSKYBLUE2);
    }

    /**
     * Color deepskyblue4.
     *
     * @return static
     */
    public static function deepskyblue4()
    {
        return new static(Color::DEEPSKYBLUE4);
    }

    /**
     * Color deepskyblue3.
     *
     * @return static
     */
    public static function deepskyblue3()
    {
        return new static(Color::DEEPSKYBLUE3);
    }

    /**
     * Color lightblue1.
     *
     * @return static
     */
    public static function lightblue1()
    {
        return new static(Color::LIGHTBLUE1);
    }

    /**
     * Color lightblue2.
     *
     * @return static
     */
    public static function lightblue2()
    {
        return new static(Color::LIGHTBLUE2);
    }

    /**
     * Color lightblue.
     *
     * @return static
     */
    public static function lightblue()
    {
        return new static(Color::LIGHTBLUE);
    }

    /**
     * Color lightblue4.
     *
     * @return static
     */
    public static function lightblue4()
    {
        return new static(Color::LIGHTBLUE4);
    }

    /**
     * Color powderblue.
     *
     * @return static
     */
    public static function powderblue()
    {
        return new static(Color::POWDERBLUE);
    }

    /**
     * Color cadetblue1.
     *
     * @return static
     */
    public static function cadetblue1()
    {
        return new static(Color::CADETBLUE1);
    }

    /**
     * Color cadetblue2.
     *
     * @return static
     */
    public static function cadetblue2()
    {
        return new static(Color::CADETBLUE2);
    }

    /**
     * Color cadetblue3.
     *
     * @return static
     */
    public static function cadetblue3()
    {
        return new static(Color::CADETBLUE3);
    }

    /**
     * Color cadetblue4.
     *
     * @return static
     */
    public static function cadetblue4()
    {
        return new static(Color::CADETBLUE4);
    }

    /**
     * Color turquoise1.
     *
     * @return static
     */
    public static function turquoise1()
    {
        return new static(Color::TURQUOISE1);
    }

    /**
     * Color turquoise2.
     *
     * @return static
     */
    public static function turquoise2()
    {
        return new static(Color::TURQUOISE2);
    }

    /**
     * Color turquoise3.
     *
     * @return static
     */
    public static function turquoise3()
    {
        return new static(Color::TURQUOISE3);
    }

    /**
     * Color turquoise4.
     *
     * @return static
     */
    public static function turquoise4()
    {
        return new static(Color::TURQUOISE4);
    }

    /**
     * Color cadetblue.
     *
     * @return static
     */
    public static function cadetblue()
    {
        return new static(Color::CADETBLUE);
    }

    /**
     * Color darkturquoise.
     *
     * @return static
     */
    public static function darkturquoise()
    {
        return new static(Color::DARKTURQUOISE);
    }

    /**
     * Color azure.
     *
     * @return static
     */
    public static function azure()
    {
        return new static(Color::AZURE);
    }

    /**
     * Color azure1.
     *
     * @return static
     */
    public static function azure1()
    {
        return new static(Color::AZURE1);
    }

    /**
     * Color lightcyan1.
     *
     * @return static
     */
    public static function lightcyan1()
    {
        return new static(Color::LIGHTCYAN1);
    }

    /**
     * Color lightcyan.
     *
     * @return static
     */
    public static function lightcyan()
    {
        return new static(Color::LIGHTCYAN);
    }

    /**
     * Color azure2.
     *
     * @return static
     */
    public static function azure2()
    {
        return new static(Color::AZURE2);
    }

    /**
     * Color lightcyan2.
     *
     * @return static
     */
    public static function lightcyan2()
    {
        return new static(Color::LIGHTCYAN2);
    }

    /**
     * Color paleturquoise1.
     *
     * @return static
     */
    public static function paleturquoise1()
    {
        return new static(Color::PALETURQUOISE1);
    }

    /**
     * Color paleturquoise.
     *
     * @return static
     */
    public static function paleturquoise()
    {
        return new static(Color::PALETURQUOISE);
    }

    /**
     * Color paleturquoise2.
     *
     * @return static
     */
    public static function paleturquoise2()
    {
        return new static(Color::PALETURQUOISE2);
    }

    /**
     * Color darkslategray1.
     *
     * @return static
     */
    public static function darkslategray1()
    {
        return new static(Color::DARKSLATEGRAY1);
    }

    /**
     * Color azure3.
     *
     * @return static
     */
    public static function azure3()
    {
        return new static(Color::AZURE3);
    }

    /**
     * Color lightcyan3.
     *
     * @return static
     */
    public static function lightcyan3()
    {
        return new static(Color::LIGHTCYAN3);
    }

    /**
     * Color darkslategray2.
     *
     * @return static
     */
    public static function darkslategray2()
    {
        return new static(Color::DARKSLATEGRAY2);
    }

    /**
     * Color paleturquoise3.
     *
     * @return static
     */
    public static function paleturquoise3()
    {
        return new static(Color::PALETURQUOISE3);
    }

    /**
     * Color darkslategray3.
     *
     * @return static
     */
    public static function darkslategray3()
    {
        return new static(Color::DARKSLATEGRAY3);
    }

    /**
     * Color azure4.
     *
     * @return static
     */
    public static function azure4()
    {
        return new static(Color::AZURE4);
    }

    /**
     * Color lightcyan4.
     *
     * @return static
     */
    public static function lightcyan4()
    {
        return new static(Color::LIGHTCYAN4);
    }

    /**
     * Color aqua.
     *
     * @return static
     */
    public static function aqua()
    {
        return new static(Color::AQUA);
    }

    /**
     * Color cyan.
     *
     * @return static
     */
    public static function cyan()
    {
        return new static(Color::CYAN);
    }

    /**
     * Color cyan1.
     *
     * @return static
     */
    public static function cyan1()
    {
        return new static(Color::CYAN1);
    }

    /**
     * Color paleturquoise4.
     *
     * @return static
     */
    public static function paleturquoise4()
    {
        return new static(Color::PALETURQUOISE4);
    }

    /**
     * Color cyan2.
     *
     * @return static
     */
    public static function cyan2()
    {
        return new static(Color::CYAN2);
    }

    /**
     * Color darkslategray4.
     *
     * @return static
     */
    public static function darkslategray4()
    {
        return new static(Color::DARKSLATEGRAY4);
    }

    /**
     * Color cyan3.
     *
     * @return static
     */
    public static function cyan3()
    {
        return new static(Color::CYAN3);
    }

    /**
     * Color cyan4.
     *
     * @return static
     */
    public static function cyan4()
    {
        return new static(Color::CYAN4);
    }

    /**
     * Color darkcyan.
     *
     * @return static
     */
    public static function darkcyan()
    {
        return new static(Color::DARKCYAN);
    }

    /**
     * Color teal.
     *
     * @return static
     */
    public static function teal()
    {
        return new static(Color::TEAL);
    }

    /**
     * Color darkslategray.
     *
     * @return static
     */
    public static function darkslategray()
    {
        return new static(Color::DARKSLATEGRAY);
    }

    /**
     * Color darkslategrey.
     *
     * @return static
     */
    public static function darkslategrey()
    {
        return new static(Color::DARKSLATEGREY);
    }

    /**
     * Color mediumturquoise.
     *
     * @return static
     */
    public static function mediumturquoise()
    {
        return new static(Color::MEDIUMTURQUOISE);
    }

    /**
     * Color lightseagreen.
     *
     * @return static
     */
    public static function lightseagreen()
    {
        return new static(Color::LIGHTSEAGREEN);
    }

    /**
     * Color turquoise.
     *
     * @return static
     */
    public static function turquoise()
    {
        return new static(Color::TURQUOISE);
    }

    /**
     * Color aquamarine4.
     *
     * @return static
     */
    public static function aquamarine4()
    {
        return new static(Color::AQUAMARINE4);
    }

    /**
     * Color aquamarine.
     *
     * @return static
     */
    public static function aquamarine()
    {
        return new static(Color::AQUAMARINE);
    }

    /**
     * Color aquamarine1.
     *
     * @return static
     */
    public static function aquamarine1()
    {
        return new static(Color::AQUAMARINE1);
    }

    /**
     * Color aquamarine2.
     *
     * @return static
     */
    public static function aquamarine2()
    {
        return new static(Color::AQUAMARINE2);
    }

    /**
     * Color aquamarine3.
     *
     * @return static
     */
    public static function aquamarine3()
    {
        return new static(Color::AQUAMARINE3);
    }

    /**
     * Color mediumaquamarine.
     *
     * @return static
     */
    public static function mediumaquamarine()
    {
        return new static(Color::MEDIUMAQUAMARINE);
    }

    /**
     * Color mediumspringgreen.
     *
     * @return static
     */
    public static function mediumspringgreen()
    {
        return new static(Color::MEDIUMSPRINGGREEN);
    }

    /**
     * Color mintcream.
     *
     * @return static
     */
    public static function mintcream()
    {
        return new static(Color::MINTCREAM);
    }

    /**
     * Color springgreen.
     *
     * @return static
     */
    public static function springgreen()
    {
        return new static(Color::SPRINGGREEN);
    }

    /**
     * Color springgreen1.
     *
     * @return static
     */
    public static function springgreen1()
    {
        return new static(Color::SPRINGGREEN1);
    }

    /**
     * Color springgreen2.
     *
     * @return static
     */
    public static function springgreen2()
    {
        return new static(Color::SPRINGGREEN2);
    }

    /**
     * Color springgreen3.
     *
     * @return static
     */
    public static function springgreen3()
    {
        return new static(Color::SPRINGGREEN3);
    }

    /**
     * Color springgreen4.
     *
     * @return static
     */
    public static function springgreen4()
    {
        return new static(Color::SPRINGGREEN4);
    }

    /**
     * Color mediumseagreen.
     *
     * @return static
     */
    public static function mediumseagreen()
    {
        return new static(Color::MEDIUMSEAGREEN);
    }

    /**
     * Color seagreen.
     *
     * @return static
     */
    public static function seagreen()
    {
        return new static(Color::SEAGREEN);
    }

    /**
     * Color seagreen3.
     *
     * @return static
     */
    public static function seagreen3()
    {
        return new static(Color::SEAGREEN3);
    }

    /**
     * Color seagreen1.
     *
     * @return static
     */
    public static function seagreen1()
    {
        return new static(Color::SEAGREEN1);
    }

    /**
     * Color seagreen4.
     *
     * @return static
     */
    public static function seagreen4()
    {
        return new static(Color::SEAGREEN4);
    }

    /**
     * Color seagreen2.
     *
     * @return static
     */
    public static function seagreen2()
    {
        return new static(Color::SEAGREEN2);
    }

    /**
     * Color mediumforestgreen.
     *
     * @return static
     */
    public static function mediumforestgreen()
    {
        return new static(Color::MEDIUMFORESTGREEN);
    }

    /**
     * Color honeydew.
     *
     * @return static
     */
    public static function honeydew()
    {
        return new static(Color::HONEYDEW);
    }

    /**
     * Color honeydew1.
     *
     * @return static
     */
    public static function honeydew1()
    {
        return new static(Color::HONEYDEW1);
    }

    /**
     * Color honeydew2.
     *
     * @return static
     */
    public static function honeydew2()
    {
        return new static(Color::HONEYDEW2);
    }

    /**
     * Color darkseagreen1.
     *
     * @return static
     */
    public static function darkseagreen1()
    {
        return new static(Color::DARKSEAGREEN1);
    }

    /**
     * Color darkseagreen2.
     *
     * @return static
     */
    public static function darkseagreen2()
    {
        return new static(Color::DARKSEAGREEN2);
    }

    /**
     * Color palegreen1.
     *
     * @return static
     */
    public static function palegreen1()
    {
        return new static(Color::PALEGREEN1);
    }

    /**
     * Color palegreen.
     *
     * @return static
     */
    public static function palegreen()
    {
        return new static(Color::PALEGREEN);
    }

    /**
     * Color honeydew3.
     *
     * @return static
     */
    public static function honeydew3()
    {
        return new static(Color::HONEYDEW3);
    }

    /**
     * Color lightgreen.
     *
     * @return static
     */
    public static function lightgreen()
    {
        return new static(Color::LIGHTGREEN);
    }

    /**
     * Color palegreen2.
     *
     * @return static
     */
    public static function palegreen2()
    {
        return new static(Color::PALEGREEN2);
    }

    /**
     * Color darkseagreen3.
     *
     * @return static
     */
    public static function darkseagreen3()
    {
        return new static(Color::DARKSEAGREEN3);
    }

    /**
     * Color darkseagreen.
     *
     * @return static
     */
    public static function darkseagreen()
    {
        return new static(Color::DARKSEAGREEN);
    }

    /**
     * Color palegreen3.
     *
     * @return static
     */
    public static function palegreen3()
    {
        return new static(Color::PALEGREEN3);
    }

    /**
     * Color honeydew4.
     *
     * @return static
     */
    public static function honeydew4()
    {
        return new static(Color::HONEYDEW4);
    }

    /**
     * Color green1.
     *
     * @return static
     */
    public static function green1()
    {
        return new static(Color::GREEN1);
    }

    /**
     * Color lime.
     *
     * @return static
     */
    public static function lime()
    {
        return new static(Color::LIME);
    }

    /**
     * Color limegreen.
     *
     * @return static
     */
    public static function limegreen()
    {
        return new static(Color::LIMEGREEN);
    }

    /**
     * Color darkseagreen4.
     *
     * @return static
     */
    public static function darkseagreen4()
    {
        return new static(Color::DARKSEAGREEN4);
    }

    /**
     * Color green2.
     *
     * @return static
     */
    public static function green2()
    {
        return new static(Color::GREEN2);
    }

    /**
     * Color palegreen4.
     *
     * @return static
     */
    public static function palegreen4()
    {
        return new static(Color::PALEGREEN4);
    }

    /**
     * Color green3.
     *
     * @return static
     */
    public static function green3()
    {
        return new static(Color::GREEN3);
    }

    /**
     * Color forestgreen.
     *
     * @return static
     */
    public static function forestgreen()
    {
        return new static(Color::FORESTGREEN);
    }

    /**
     * Color green4.
     *
     * @return static
     */
    public static function green4()
    {
        return new static(Color::GREEN4);
    }

    /**
     * Color green.
     *
     * @return static
     */
    public static function green()
    {
        return new static(Color::GREEN);
    }

    /**
     * Color darkgreen.
     *
     * @return static
     */
    public static function darkgreen()
    {
        return new static(Color::DARKGREEN);
    }

    /**
     * Color lawngreen.
     *
     * @return static
     */
    public static function lawngreen()
    {
        return new static(Color::LAWNGREEN);
    }

    /**
     * Color chartreuse.
     *
     * @return static
     */
    public static function chartreuse()
    {
        return new static(Color::CHARTREUSE);
    }

    /**
     * Color chartreuse1.
     *
     * @return static
     */
    public static function chartreuse1()
    {
        return new static(Color::CHARTREUSE1);
    }

    /**
     * Color chartreuse2.
     *
     * @return static
     */
    public static function chartreuse2()
    {
        return new static(Color::CHARTREUSE2);
    }

    /**
     * Color chartreuse3.
     *
     * @return static
     */
    public static function chartreuse3()
    {
        return new static(Color::CHARTREUSE3);
    }

    /**
     * Color chartreuse4.
     *
     * @return static
     */
    public static function chartreuse4()
    {
        return new static(Color::CHARTREUSE4);
    }

    /**
     * Color greenyellow.
     *
     * @return static
     */
    public static function greenyellow()
    {
        return new static(Color::GREENYELLOW);
    }

    /**
     * Color darkolivegreen3.
     *
     * @return static
     */
    public static function darkolivegreen3()
    {
        return new static(Color::DARKOLIVEGREEN3);
    }

    /**
     * Color darkolivegreen1.
     *
     * @return static
     */
    public static function darkolivegreen1()
    {
        return new static(Color::DARKOLIVEGREEN1);
    }

    /**
     * Color darkolivegreen2.
     *
     * @return static
     */
    public static function darkolivegreen2()
    {
        return new static(Color::DARKOLIVEGREEN2);
    }

    /**
     * Color darkolivegreen4.
     *
     * @return static
     */
    public static function darkolivegreen4()
    {
        return new static(Color::DARKOLIVEGREEN4);
    }

    /**
     * Color darkolivegreen.
     *
     * @return static
     */
    public static function darkolivegreen()
    {
        return new static(Color::DARKOLIVEGREEN);
    }

    /**
     * Color olivedrab.
     *
     * @return static
     */
    public static function olivedrab()
    {
        return new static(Color::OLIVEDRAB);
    }

    /**
     * Color olivedrab1.
     *
     * @return static
     */
    public static function olivedrab1()
    {
        return new static(Color::OLIVEDRAB1);
    }

    /**
     * Color olivedrab2.
     *
     * @return static
     */
    public static function olivedrab2()
    {
        return new static(Color::OLIVEDRAB2);
    }

    /**
     * Color olivedrab3.
     *
     * @return static
     */
    public static function olivedrab3()
    {
        return new static(Color::OLIVEDRAB3);
    }

    /**
     * Color yellowgreen.
     *
     * @return static
     */
    public static function yellowgreen()
    {
        return new static(Color::YELLOWGREEN);
    }

    /**
     * Color olivedrab4.
     *
     * @return static
     */
    public static function olivedrab4()
    {
        return new static(Color::OLIVEDRAB4);
    }

    /**
     * Color ivory.
     *
     * @return static
     */
    public static function ivory()
    {
        return new static(Color::IVORY);
    }

    /**
     * Color ivory1.
     *
     * @return static
     */
    public static function ivory1()
    {
        return new static(Color::IVORY1);
    }

    /**
     * Color lightyellow.
     *
     * @return static
     */
    public static function lightyellow()
    {
        return new static(Color::LIGHTYELLOW);
    }

    /**
     * Color lightyellow1.
     *
     * @return static
     */
    public static function lightyellow1()
    {
        return new static(Color::LIGHTYELLOW1);
    }

    /**
     * Color beige.
     *
     * @return static
     */
    public static function beige()
    {
        return new static(Color::BEIGE);
    }

    /**
     * Color ivory2.
     *
     * @return static
     */
    public static function ivory2()
    {
        return new static(Color::IVORY2);
    }

    /**
     * Color lightgoldenrodyellow.
     *
     * @return static
     */
    public static function lightgoldenrodyellow()
    {
        return new static(Color::LIGHTGOLDENRODYELLOW);
    }

    /**
     * Color lightyellow2.
     *
     * @return static
     */
    public static function lightyellow2()
    {
        return new static(Color::LIGHTYELLOW2);
    }

    /**
     * Color ivory3.
     *
     * @return static
     */
    public static function ivory3()
    {
        return new static(Color::IVORY3);
    }

    /**
     * Color lightyellow3.
     *
     * @return static
     */
    public static function lightyellow3()
    {
        return new static(Color::LIGHTYELLOW3);
    }

    /**
     * Color ivory4.
     *
     * @return static
     */
    public static function ivory4()
    {
        return new static(Color::IVORY4);
    }

    /**
     * Color lightyellow4.
     *
     * @return static
     */
    public static function lightyellow4()
    {
        return new static(Color::LIGHTYELLOW4);
    }

    /**
     * Color yellow.
     *
     * @return static
     */
    public static function yellow()
    {
        return new static(Color::YELLOW);
    }

    /**
     * Color yellow1.
     *
     * @return static
     */
    public static function yellow1()
    {
        return new static(Color::YELLOW1);
    }

    /**
     * Color yellow2.
     *
     * @return static
     */
    public static function yellow2()
    {
        return new static(Color::YELLOW2);
    }

    /**
     * Color yellow3.
     *
     * @return static
     */
    public static function yellow3()
    {
        return new static(Color::YELLOW3);
    }

    /**
     * Color yellow4.
     *
     * @return static
     */
    public static function yellow4()
    {
        return new static(Color::YELLOW4);
    }

    /**
     * Color olive.
     *
     * @return static
     */
    public static function olive()
    {
        return new static(Color::OLIVE);
    }

    /**
     * Color darkkhaki.
     *
     * @return static
     */
    public static function darkkhaki()
    {
        return new static(Color::DARKKHAKI);
    }

    /**
     * Color khaki2.
     *
     * @return static
     */
    public static function khaki2()
    {
        return new static(Color::KHAKI2);
    }

    /**
     * Color lemonchiffon4.
     *
     * @return static
     */
    public static function lemonchiffon4()
    {
        return new static(Color::LEMONCHIFFON4);
    }

    /**
     * Color khaki1.
     *
     * @return static
     */
    public static function khaki1()
    {
        return new static(Color::KHAKI1);
    }

    /**
     * Color khaki3.
     *
     * @return static
     */
    public static function khaki3()
    {
        return new static(Color::KHAKI3);
    }

    /**
     * Color khaki4.
     *
     * @return static
     */
    public static function khaki4()
    {
        return new static(Color::KHAKI4);
    }

    /**
     * Color palegoldenrod.
     *
     * @return static
     */
    public static function palegoldenrod()
    {
        return new static(Color::PALEGOLDENROD);
    }

    /**
     * Color lemonchiffon.
     *
     * @return static
     */
    public static function lemonchiffon()
    {
        return new static(Color::LEMONCHIFFON);
    }

    /**
     * Color lemonchiffon1.
     *
     * @return static
     */
    public static function lemonchiffon1()
    {
        return new static(Color::LEMONCHIFFON1);
    }

    /**
     * Color khaki.
     *
     * @return static
     */
    public static function khaki()
    {
        return new static(Color::KHAKI);
    }

    /**
     * Color lemonchiffon3.
     *
     * @return static
     */
    public static function lemonchiffon3()
    {
        return new static(Color::LEMONCHIFFON3);
    }

    /**
     * Color lemonchiffon2.
     *
     * @return static
     */
    public static function lemonchiffon2()
    {
        return new static(Color::LEMONCHIFFON2);
    }

    /**
     * Color mediumgoldenrod.
     *
     * @return static
     */
    public static function mediumgoldenrod()
    {
        return new static(Color::MEDIUMGOLDENROD);
    }

    /**
     * Color cornsilk4.
     *
     * @return static
     */
    public static function cornsilk4()
    {
        return new static(Color::CORNSILK4);
    }

    /**
     * Color gold.
     *
     * @return static
     */
    public static function gold()
    {
        return new static(Color::GOLD);
    }

    /**
     * Color gold1.
     *
     * @return static
     */
    public static function gold1()
    {
        return new static(Color::GOLD1);
    }

    /**
     * Color gold2.
     *
     * @return static
     */
    public static function gold2()
    {
        return new static(Color::GOLD2);
    }

    /**
     * Color gold3.
     *
     * @return static
     */
    public static function gold3()
    {
        return new static(Color::GOLD3);
    }

    /**
     * Color gold4.
     *
     * @return static
     */
    public static function gold4()
    {
        return new static(Color::GOLD4);
    }

    /**
     * Color lightgoldenrod.
     *
     * @return static
     */
    public static function lightgoldenrod()
    {
        return new static(Color::LIGHTGOLDENROD);
    }

    /**
     * Color lightgoldenrod4.
     *
     * @return static
     */
    public static function lightgoldenrod4()
    {
        return new static(Color::LIGHTGOLDENROD4);
    }

    /**
     * Color lightgoldenrod1.
     *
     * @return static
     */
    public static function lightgoldenrod1()
    {
        return new static(Color::LIGHTGOLDENROD1);
    }

    /**
     * Color lightgoldenrod3.
     *
     * @return static
     */
    public static function lightgoldenrod3()
    {
        return new static(Color::LIGHTGOLDENROD3);
    }

    /**
     * Color lightgoldenrod2.
     *
     * @return static
     */
    public static function lightgoldenrod2()
    {
        return new static(Color::LIGHTGOLDENROD2);
    }

    /**
     * Color cornsilk3.
     *
     * @return static
     */
    public static function cornsilk3()
    {
        return new static(Color::CORNSILK3);
    }

    /**
     * Color cornsilk2.
     *
     * @return static
     */
    public static function cornsilk2()
    {
        return new static(Color::CORNSILK2);
    }

    /**
     * Color cornsilk.
     *
     * @return static
     */
    public static function cornsilk()
    {
        return new static(Color::CORNSILK);
    }

    /**
     * Color cornsilk1.
     *
     * @return static
     */
    public static function cornsilk1()
    {
        return new static(Color::CORNSILK1);
    }

    /**
     * Color goldenrod.
     *
     * @return static
     */
    public static function goldenrod()
    {
        return new static(Color::GOLDENROD);
    }

    /**
     * Color goldenrod1.
     *
     * @return static
     */
    public static function goldenrod1()
    {
        return new static(Color::GOLDENROD1);
    }

    /**
     * Color goldenrod2.
     *
     * @return static
     */
    public static function goldenrod2()
    {
        return new static(Color::GOLDENROD2);
    }

    /**
     * Color goldenrod3.
     *
     * @return static
     */
    public static function goldenrod3()
    {
        return new static(Color::GOLDENROD3);
    }

    /**
     * Color goldenrod4.
     *
     * @return static
     */
    public static function goldenrod4()
    {
        return new static(Color::GOLDENROD4);
    }

    /**
     * Color darkgoldenrod.
     *
     * @return static
     */
    public static function darkgoldenrod()
    {
        return new static(Color::DARKGOLDENROD);
    }

    /**
     * Color darkgoldenrod1.
     *
     * @return static
     */
    public static function darkgoldenrod1()
    {
        return new static(Color::DARKGOLDENROD1);
    }

    /**
     * Color darkgoldenrod2.
     *
     * @return static
     */
    public static function darkgoldenrod2()
    {
        return new static(Color::DARKGOLDENROD2);
    }

    /**
     * Color darkgoldenrod3.
     *
     * @return static
     */
    public static function darkgoldenrod3()
    {
        return new static(Color::DARKGOLDENROD3);
    }

    /**
     * Color darkgoldenrod4.
     *
     * @return static
     */
    public static function darkgoldenrod4()
    {
        return new static(Color::DARKGOLDENROD4);
    }

    /**
     * Color floralwhite.
     *
     * @return static
     */
    public static function floralwhite()
    {
        return new static(Color::FLORALWHITE);
    }

    /**
     * Color wheat2.
     *
     * @return static
     */
    public static function wheat2()
    {
        return new static(Color::WHEAT2);
    }

    /**
     * Color oldlace.
     *
     * @return static
     */
    public static function oldlace()
    {
        return new static(Color::OLDLACE);
    }

    /**
     * Color wheat.
     *
     * @return static
     */
    public static function wheat()
    {
        return new static(Color::WHEAT);
    }

    /**
     * Color wheat1.
     *
     * @return static
     */
    public static function wheat1()
    {
        return new static(Color::WHEAT1);
    }

    /**
     * Color wheat3.
     *
     * @return static
     */
    public static function wheat3()
    {
        return new static(Color::WHEAT3);
    }

    /**
     * Color orange.
     *
     * @return static
     */
    public static function orange()
    {
        return new static(Color::ORANGE);
    }

    /**
     * Color orange1.
     *
     * @return static
     */
    public static function orange1()
    {
        return new static(Color::ORANGE1);
    }

    /**
     * Color orange2.
     *
     * @return static
     */
    public static function orange2()
    {
        return new static(Color::ORANGE2);
    }

    /**
     * Color orange3.
     *
     * @return static
     */
    public static function orange3()
    {
        return new static(Color::ORANGE3);
    }

    /**
     * Color orange4.
     *
     * @return static
     */
    public static function orange4()
    {
        return new static(Color::ORANGE4);
    }

    /**
     * Color wheat4.
     *
     * @return static
     */
    public static function wheat4()
    {
        return new static(Color::WHEAT4);
    }

    /**
     * Color moccasin.
     *
     * @return static
     */
    public static function moccasin()
    {
        return new static(Color::MOCCASIN);
    }

    /**
     * Color papayawhip.
     *
     * @return static
     */
    public static function papayawhip()
    {
        return new static(Color::PAPAYAWHIP);
    }

    /**
     * Color navajowhite3.
     *
     * @return static
     */
    public static function navajowhite3()
    {
        return new static(Color::NAVAJOWHITE3);
    }

    /**
     * Color blanchedalmond.
     *
     * @return static
     */
    public static function blanchedalmond()
    {
        return new static(Color::BLANCHEDALMOND);
    }

    /**
     * Color navajowhite.
     *
     * @return static
     */
    public static function navajowhite()
    {
        return new static(Color::NAVAJOWHITE);
    }

    /**
     * Color navajowhite1.
     *
     * @return static
     */
    public static function navajowhite1()
    {
        return new static(Color::NAVAJOWHITE1);
    }

    /**
     * Color navajowhite2.
     *
     * @return static
     */
    public static function navajowhite2()
    {
        return new static(Color::NAVAJOWHITE2);
    }

    /**
     * Color navajowhite4.
     *
     * @return static
     */
    public static function navajowhite4()
    {
        return new static(Color::NAVAJOWHITE4);
    }

    /**
     * Color antiquewhite4.
     *
     * @return static
     */
    public static function antiquewhite4()
    {
        return new static(Color::ANTIQUEWHITE4);
    }

    /**
     * Color antiquewhite.
     *
     * @return static
     */
    public static function antiquewhite()
    {
        return new static(Color::ANTIQUEWHITE);
    }

    /**
     * Color tan.
     *
     * @return static
     */
    public static function tan()
    {
        return new static(Color::TAN);
    }

    /**
     * Color bisque4.
     *
     * @return static
     */
    public static function bisque4()
    {
        return new static(Color::BISQUE4);
    }

    /**
     * Color burlywood.
     *
     * @return static
     */
    public static function burlywood()
    {
        return new static(Color::BURLYWOOD);
    }

    /**
     * Color antiquewhite2.
     *
     * @return static
     */
    public static function antiquewhite2()
    {
        return new static(Color::ANTIQUEWHITE2);
    }

    /**
     * Color burlywood1.
     *
     * @return static
     */
    public static function burlywood1()
    {
        return new static(Color::BURLYWOOD1);
    }

    /**
     * Color burlywood3.
     *
     * @return static
     */
    public static function burlywood3()
    {
        return new static(Color::BURLYWOOD3);
    }

    /**
     * Color burlywood2.
     *
     * @return static
     */
    public static function burlywood2()
    {
        return new static(Color::BURLYWOOD2);
    }

    /**
     * Color antiquewhite1.
     *
     * @return static
     */
    public static function antiquewhite1()
    {
        return new static(Color::ANTIQUEWHITE1);
    }

    /**
     * Color burlywood4.
     *
     * @return static
     */
    public static function burlywood4()
    {
        return new static(Color::BURLYWOOD4);
    }

    /**
     * Color antiquewhite3.
     *
     * @return static
     */
    public static function antiquewhite3()
    {
        return new static(Color::ANTIQUEWHITE3);
    }

    /**
     * Color darkorange.
     *
     * @return static
     */
    public static function darkorange()
    {
        return new static(Color::DARKORANGE);
    }

    /**
     * Color bisque2.
     *
     * @return static
     */
    public static function bisque2()
    {
        return new static(Color::BISQUE2);
    }

    /**
     * Color bisque.
     *
     * @return static
     */
    public static function bisque()
    {
        return new static(Color::BISQUE);
    }

    /**
     * Color bisque1.
     *
     * @return static
     */
    public static function bisque1()
    {
        return new static(Color::BISQUE1);
    }

    /**
     * Color bisque3.
     *
     * @return static
     */
    public static function bisque3()
    {
        return new static(Color::BISQUE3);
    }

    /**
     * Color darkorange1.
     *
     * @return static
     */
    public static function darkorange1()
    {
        return new static(Color::DARKORANGE1);
    }

    /**
     * Color linen.
     *
     * @return static
     */
    public static function linen()
    {
        return new static(Color::LINEN);
    }

    /**
     * Color darkorange2.
     *
     * @return static
     */
    public static function darkorange2()
    {
        return new static(Color::DARKORANGE2);
    }

    /**
     * Color darkorange3.
     *
     * @return static
     */
    public static function darkorange3()
    {
        return new static(Color::DARKORANGE3);
    }

    /**
     * Color darkorange4.
     *
     * @return static
     */
    public static function darkorange4()
    {
        return new static(Color::DARKORANGE4);
    }

    /**
     * Color peru.
     *
     * @return static
     */
    public static function peru()
    {
        return new static(Color::PERU);
    }

    /**
     * Color tan1.
     *
     * @return static
     */
    public static function tan1()
    {
        return new static(Color::TAN1);
    }

    /**
     * Color tan2.
     *
     * @return static
     */
    public static function tan2()
    {
        return new static(Color::TAN2);
    }

    /**
     * Color tan3.
     *
     * @return static
     */
    public static function tan3()
    {
        return new static(Color::TAN3);
    }

    /**
     * Color tan4.
     *
     * @return static
     */
    public static function tan4()
    {
        return new static(Color::TAN4);
    }

    /**
     * Color peachpuff.
     *
     * @return static
     */
    public static function peachpuff()
    {
        return new static(Color::PEACHPUFF);
    }

    /**
     * Color peachpuff1.
     *
     * @return static
     */
    public static function peachpuff1()
    {
        return new static(Color::PEACHPUFF1);
    }

    /**
     * Color peachpuff4.
     *
     * @return static
     */
    public static function peachpuff4()
    {
        return new static(Color::PEACHPUFF4);
    }

    /**
     * Color peachpuff2.
     *
     * @return static
     */
    public static function peachpuff2()
    {
        return new static(Color::PEACHPUFF2);
    }

    /**
     * Color peachpuff3.
     *
     * @return static
     */
    public static function peachpuff3()
    {
        return new static(Color::PEACHPUFF3);
    }

    /**
     * Color sandybrown.
     *
     * @return static
     */
    public static function sandybrown()
    {
        return new static(Color::SANDYBROWN);
    }

    /**
     * Color seashell4.
     *
     * @return static
     */
    public static function seashell4()
    {
        return new static(Color::SEASHELL4);
    }

    /**
     * Color seashell2.
     *
     * @return static
     */
    public static function seashell2()
    {
        return new static(Color::SEASHELL2);
    }

    /**
     * Color seashell3.
     *
     * @return static
     */
    public static function seashell3()
    {
        return new static(Color::SEASHELL3);
    }

    /**
     * Color chocolate.
     *
     * @return static
     */
    public static function chocolate()
    {
        return new static(Color::CHOCOLATE);
    }

    /**
     * Color chocolate1.
     *
     * @return static
     */
    public static function chocolate1()
    {
        return new static(Color::CHOCOLATE1);
    }

    /**
     * Color chocolate2.
     *
     * @return static
     */
    public static function chocolate2()
    {
        return new static(Color::CHOCOLATE2);
    }

    /**
     * Color chocolate3.
     *
     * @return static
     */
    public static function chocolate3()
    {
        return new static(Color::CHOCOLATE3);
    }

    /**
     * Color chocolate4.
     *
     * @return static
     */
    public static function chocolate4()
    {
        return new static(Color::CHOCOLATE4);
    }

    /**
     * Color saddlebrown.
     *
     * @return static
     */
    public static function saddlebrown()
    {
        return new static(Color::SADDLEBROWN);
    }

    /**
     * Color seashell.
     *
     * @return static
     */
    public static function seashell()
    {
        return new static(Color::SEASHELL);
    }

    /**
     * Color seashell1.
     *
     * @return static
     */
    public static function seashell1()
    {
        return new static(Color::SEASHELL1);
    }

    /**
     * Color sienna4.
     *
     * @return static
     */
    public static function sienna4()
    {
        return new static(Color::SIENNA4);
    }

    /**
     * Color sienna.
     *
     * @return static
     */
    public static function sienna()
    {
        return new static(Color::SIENNA);
    }

    /**
     * Color sienna1.
     *
     * @return static
     */
    public static function sienna1()
    {
        return new static(Color::SIENNA1);
    }

    /**
     * Color sienna2.
     *
     * @return static
     */
    public static function sienna2()
    {
        return new static(Color::SIENNA2);
    }

    /**
     * Color sienna3.
     *
     * @return static
     */
    public static function sienna3()
    {
        return new static(Color::SIENNA3);
    }

    /**
     * Color lightsalmon3.
     *
     * @return static
     */
    public static function lightsalmon3()
    {
        return new static(Color::LIGHTSALMON3);
    }

    /**
     * Color lightsalmon.
     *
     * @return static
     */
    public static function lightsalmon()
    {
        return new static(Color::LIGHTSALMON);
    }

    /**
     * Color lightsalmon1.
     *
     * @return static
     */
    public static function lightsalmon1()
    {
        return new static(Color::LIGHTSALMON1);
    }

    /**
     * Color lightsalmon4.
     *
     * @return static
     */
    public static function lightsalmon4()
    {
        return new static(Color::LIGHTSALMON4);
    }

    /**
     * Color lightsalmon2.
     *
     * @return static
     */
    public static function lightsalmon2()
    {
        return new static(Color::LIGHTSALMON2);
    }

    /**
     * Color coral.
     *
     * @return static
     */
    public static function coral()
    {
        return new static(Color::CORAL);
    }

    /**
     * Color orangered.
     *
     * @return static
     */
    public static function orangered()
    {
        return new static(Color::ORANGERED);
    }

    /**
     * Color orangered1.
     *
     * @return static
     */
    public static function orangered1()
    {
        return new static(Color::ORANGERED1);
    }

    /**
     * Color orangered2.
     *
     * @return static
     */
    public static function orangered2()
    {
        return new static(Color::ORANGERED2);
    }

    /**
     * Color orangered3.
     *
     * @return static
     */
    public static function orangered3()
    {
        return new static(Color::ORANGERED3);
    }

    /**
     * Color orangered4.
     *
     * @return static
     */
    public static function orangered4()
    {
        return new static(Color::ORANGERED4);
    }

    /**
     * Color darksalmon.
     *
     * @return static
     */
    public static function darksalmon()
    {
        return new static(Color::DARKSALMON);
    }

    /**
     * Color salmon1.
     *
     * @return static
     */
    public static function salmon1()
    {
        return new static(Color::SALMON1);
    }

    /**
     * Color salmon2.
     *
     * @return static
     */
    public static function salmon2()
    {
        return new static(Color::SALMON2);
    }

    /**
     * Color salmon3.
     *
     * @return static
     */
    public static function salmon3()
    {
        return new static(Color::SALMON3);
    }

    /**
     * Color salmon4.
     *
     * @return static
     */
    public static function salmon4()
    {
        return new static(Color::SALMON4);
    }

    /**
     * Color coral1.
     *
     * @return static
     */
    public static function coral1()
    {
        return new static(Color::CORAL1);
    }

    /**
     * Color coral2.
     *
     * @return static
     */
    public static function coral2()
    {
        return new static(Color::CORAL2);
    }

    /**
     * Color coral3.
     *
     * @return static
     */
    public static function coral3()
    {
        return new static(Color::CORAL3);
    }

    /**
     * Color coral4.
     *
     * @return static
     */
    public static function coral4()
    {
        return new static(Color::CORAL4);
    }

    /**
     * Color tomato4.
     *
     * @return static
     */
    public static function tomato4()
    {
        return new static(Color::TOMATO4);
    }

    /**
     * Color tomato.
     *
     * @return static
     */
    public static function tomato()
    {
        return new static(Color::TOMATO);
    }

    /**
     * Color tomato1.
     *
     * @return static
     */
    public static function tomato1()
    {
        return new static(Color::TOMATO1);
    }

    /**
     * Color tomato2.
     *
     * @return static
     */
    public static function tomato2()
    {
        return new static(Color::TOMATO2);
    }

    /**
     * Color tomato3.
     *
     * @return static
     */
    public static function tomato3()
    {
        return new static(Color::TOMATO3);
    }

    /**
     * Color mistyrose4.
     *
     * @return static
     */
    public static function mistyrose4()
    {
        return new static(Color::MISTYROSE4);
    }

    /**
     * Color mistyrose2.
     *
     * @return static
     */
    public static function mistyrose2()
    {
        return new static(Color::MISTYROSE2);
    }

    /**
     * Color mistyrose.
     *
     * @return static
     */
    public static function mistyrose()
    {
        return new static(Color::MISTYROSE);
    }

    /**
     * Color mistyrose1.
     *
     * @return static
     */
    public static function mistyrose1()
    {
        return new static(Color::MISTYROSE1);
    }

    /**
     * Color salmon.
     *
     * @return static
     */
    public static function salmon()
    {
        return new static(Color::SALMON);
    }

    /**
     * Color mistyrose3.
     *
     * @return static
     */
    public static function mistyrose3()
    {
        return new static(Color::MISTYROSE3);
    }

    /**
     * Color white.
     *
     * @return static
     */
    public static function white()
    {
        return new static(Color::WHITE);
    }

    /**
     * Color gray100.
     *
     * @return static
     */
    public static function gray100()
    {
        return new static(Color::GRAY100);
    }

    /**
     * Color grey100.
     *
     * @return static
     */
    public static function grey100()
    {
        return new static(Color::GREY100);
    }

    /**
     * Color gray99.
     *
     * @return static
     */
    public static function gray99()
    {
        return new static(Color::GRAY99);
    }

    /**
     * Color grey99.
     *
     * @return static
     */
    public static function grey99()
    {
        return new static(Color::GREY99);
    }

    /**
     * Color gray98.
     *
     * @return static
     */
    public static function gray98()
    {
        return new static(Color::GRAY98);
    }

    /**
     * Color grey98.
     *
     * @return static
     */
    public static function grey98()
    {
        return new static(Color::GREY98);
    }

    /**
     * Color gray97.
     *
     * @return static
     */
    public static function gray97()
    {
        return new static(Color::GRAY97);
    }

    /**
     * Color grey97.
     *
     * @return static
     */
    public static function grey97()
    {
        return new static(Color::GREY97);
    }

    /**
     * Color gray96.
     *
     * @return static
     */
    public static function gray96()
    {
        return new static(Color::GRAY96);
    }

    /**
     * Color grey96.
     *
     * @return static
     */
    public static function grey96()
    {
        return new static(Color::GREY96);
    }

    /**
     * Color whitesmoke.
     *
     * @return static
     */
    public static function whitesmoke()
    {
        return new static(Color::WHITESMOKE);
    }

    /**
     * Color gray95.
     *
     * @return static
     */
    public static function gray95()
    {
        return new static(Color::GRAY95);
    }

    /**
     * Color grey95.
     *
     * @return static
     */
    public static function grey95()
    {
        return new static(Color::GREY95);
    }

    /**
     * Color gray94.
     *
     * @return static
     */
    public static function gray94()
    {
        return new static(Color::GRAY94);
    }

    /**
     * Color grey94.
     *
     * @return static
     */
    public static function grey94()
    {
        return new static(Color::GREY94);
    }

    /**
     * Color gray93.
     *
     * @return static
     */
    public static function gray93()
    {
        return new static(Color::GRAY93);
    }

    /**
     * Color grey93.
     *
     * @return static
     */
    public static function grey93()
    {
        return new static(Color::GREY93);
    }

    /**
     * Color gray92.
     *
     * @return static
     */
    public static function gray92()
    {
        return new static(Color::GRAY92);
    }

    /**
     * Color grey92.
     *
     * @return static
     */
    public static function grey92()
    {
        return new static(Color::GREY92);
    }

    /**
     * Color gray91.
     *
     * @return static
     */
    public static function gray91()
    {
        return new static(Color::GRAY91);
    }

    /**
     * Color grey91.
     *
     * @return static
     */
    public static function grey91()
    {
        return new static(Color::GREY91);
    }

    /**
     * Color gray90.
     *
     * @return static
     */
    public static function gray90()
    {
        return new static(Color::GRAY90);
    }

    /**
     * Color grey90.
     *
     * @return static
     */
    public static function grey90()
    {
        return new static(Color::GREY90);
    }

    /**
     * Color gray89.
     *
     * @return static
     */
    public static function gray89()
    {
        return new static(Color::GRAY89);
    }

    /**
     * Color grey89.
     *
     * @return static
     */
    public static function grey89()
    {
        return new static(Color::GREY89);
    }

    /**
     * Color gray88.
     *
     * @return static
     */
    public static function gray88()
    {
        return new static(Color::GRAY88);
    }

    /**
     * Color grey88.
     *
     * @return static
     */
    public static function grey88()
    {
        return new static(Color::GREY88);
    }

    /**
     * Color gray87.
     *
     * @return static
     */
    public static function gray87()
    {
        return new static(Color::GRAY87);
    }

    /**
     * Color grey87.
     *
     * @return static
     */
    public static function grey87()
    {
        return new static(Color::GREY87);
    }

    /**
     * Color gainsboro.
     *
     * @return static
     */
    public static function gainsboro()
    {
        return new static(Color::GAINSBORO);
    }

    /**
     * Color gray86.
     *
     * @return static
     */
    public static function gray86()
    {
        return new static(Color::GRAY86);
    }

    /**
     * Color grey86.
     *
     * @return static
     */
    public static function grey86()
    {
        return new static(Color::GREY86);
    }

    /**
     * Color gray85.
     *
     * @return static
     */
    public static function gray85()
    {
        return new static(Color::GRAY85);
    }

    /**
     * Color grey85.
     *
     * @return static
     */
    public static function grey85()
    {
        return new static(Color::GREY85);
    }

    /**
     * Color gray84.
     *
     * @return static
     */
    public static function gray84()
    {
        return new static(Color::GRAY84);
    }

    /**
     * Color grey84.
     *
     * @return static
     */
    public static function grey84()
    {
        return new static(Color::GREY84);
    }

    /**
     * Color gray83.
     *
     * @return static
     */
    public static function gray83()
    {
        return new static(Color::GRAY83);
    }

    /**
     * Color grey83.
     *
     * @return static
     */
    public static function grey83()
    {
        return new static(Color::GREY83);
    }

    /**
     * Color lightgray.
     *
     * @return static
     */
    public static function lightgray()
    {
        return new static(Color::LIGHTGRAY);
    }

    /**
     * Color lightgrey.
     *
     * @return static
     */
    public static function lightgrey()
    {
        return new static(Color::LIGHTGREY);
    }

    /**
     * Color gray82.
     *
     * @return static
     */
    public static function gray82()
    {
        return new static(Color::GRAY82);
    }

    /**
     * Color grey82.
     *
     * @return static
     */
    public static function grey82()
    {
        return new static(Color::GREY82);
    }

    /**
     * Color gray81.
     *
     * @return static
     */
    public static function gray81()
    {
        return new static(Color::GRAY81);
    }

    /**
     * Color grey81.
     *
     * @return static
     */
    public static function grey81()
    {
        return new static(Color::GREY81);
    }

    /**
     * Color gray80.
     *
     * @return static
     */
    public static function gray80()
    {
        return new static(Color::GRAY80);
    }

    /**
     * Color grey80.
     *
     * @return static
     */
    public static function grey80()
    {
        return new static(Color::GREY80);
    }

    /**
     * Color gray79.
     *
     * @return static
     */
    public static function gray79()
    {
        return new static(Color::GRAY79);
    }

    /**
     * Color grey79.
     *
     * @return static
     */
    public static function grey79()
    {
        return new static(Color::GREY79);
    }

    /**
     * Color gray78.
     *
     * @return static
     */
    public static function gray78()
    {
        return new static(Color::GRAY78);
    }

    /**
     * Color grey78.
     *
     * @return static
     */
    public static function grey78()
    {
        return new static(Color::GREY78);
    }

    /**
     * Color gray77.
     *
     * @return static
     */
    public static function gray77()
    {
        return new static(Color::GRAY77);
    }

    /**
     * Color grey77.
     *
     * @return static
     */
    public static function grey77()
    {
        return new static(Color::GREY77);
    }

    /**
     * Color gray76.
     *
     * @return static
     */
    public static function gray76()
    {
        return new static(Color::GRAY76);
    }

    /**
     * Color grey76.
     *
     * @return static
     */
    public static function grey76()
    {
        return new static(Color::GREY76);
    }

    /**
     * Color silver.
     *
     * @return static
     */
    public static function silver()
    {
        return new static(Color::SILVER);
    }

    /**
     * Color gray75.
     *
     * @return static
     */
    public static function gray75()
    {
        return new static(Color::GRAY75);
    }

    /**
     * Color grey75.
     *
     * @return static
     */
    public static function grey75()
    {
        return new static(Color::GREY75);
    }

    /**
     * Color gray74.
     *
     * @return static
     */
    public static function gray74()
    {
        return new static(Color::GRAY74);
    }

    /**
     * Color grey74.
     *
     * @return static
     */
    public static function grey74()
    {
        return new static(Color::GREY74);
    }

    /**
     * Color gray73.
     *
     * @return static
     */
    public static function gray73()
    {
        return new static(Color::GRAY73);
    }

    /**
     * Color grey73.
     *
     * @return static
     */
    public static function grey73()
    {
        return new static(Color::GREY73);
    }

    /**
     * Color gray72.
     *
     * @return static
     */
    public static function gray72()
    {
        return new static(Color::GRAY72);
    }

    /**
     * Color grey72.
     *
     * @return static
     */
    public static function grey72()
    {
        return new static(Color::GREY72);
    }

    /**
     * Color gray71.
     *
     * @return static
     */
    public static function gray71()
    {
        return new static(Color::GRAY71);
    }

    /**
     * Color grey71.
     *
     * @return static
     */
    public static function grey71()
    {
        return new static(Color::GREY71);
    }

    /**
     * Color gray70.
     *
     * @return static
     */
    public static function gray70()
    {
        return new static(Color::GRAY70);
    }

    /**
     * Color grey70.
     *
     * @return static
     */
    public static function grey70()
    {
        return new static(Color::GREY70);
    }

    /**
     * Color gray69.
     *
     * @return static
     */
    public static function gray69()
    {
        return new static(Color::GRAY69);
    }

    /**
     * Color grey69.
     *
     * @return static
     */
    public static function grey69()
    {
        return new static(Color::GREY69);
    }

    /**
     * Color gray68.
     *
     * @return static
     */
    public static function gray68()
    {
        return new static(Color::GRAY68);
    }

    /**
     * Color grey68.
     *
     * @return static
     */
    public static function grey68()
    {
        return new static(Color::GREY68);
    }

    /**
     * Color gray67.
     *
     * @return static
     */
    public static function gray67()
    {
        return new static(Color::GRAY67);
    }

    /**
     * Color grey67.
     *
     * @return static
     */
    public static function grey67()
    {
        return new static(Color::GREY67);
    }

    /**
     * Color darkgray.
     *
     * @return static
     */
    public static function darkgray()
    {
        return new static(Color::DARKGRAY);
    }

    /**
     * Color darkgrey.
     *
     * @return static
     */
    public static function darkgrey()
    {
        return new static(Color::DARKGREY);
    }

    /**
     * Color gray66.
     *
     * @return static
     */
    public static function gray66()
    {
        return new static(Color::GRAY66);
    }

    /**
     * Color grey66.
     *
     * @return static
     */
    public static function grey66()
    {
        return new static(Color::GREY66);
    }

    /**
     * Color gray65.
     *
     * @return static
     */
    public static function gray65()
    {
        return new static(Color::GRAY65);
    }

    /**
     * Color grey65.
     *
     * @return static
     */
    public static function grey65()
    {
        return new static(Color::GREY65);
    }

    /**
     * Color gray64.
     *
     * @return static
     */
    public static function gray64()
    {
        return new static(Color::GRAY64);
    }

    /**
     * Color grey64.
     *
     * @return static
     */
    public static function grey64()
    {
        return new static(Color::GREY64);
    }

    /**
     * Color gray63.
     *
     * @return static
     */
    public static function gray63()
    {
        return new static(Color::GRAY63);
    }

    /**
     * Color grey63.
     *
     * @return static
     */
    public static function grey63()
    {
        return new static(Color::GREY63);
    }

    /**
     * Color gray62.
     *
     * @return static
     */
    public static function gray62()
    {
        return new static(Color::GRAY62);
    }

    /**
     * Color grey62.
     *
     * @return static
     */
    public static function grey62()
    {
        return new static(Color::GREY62);
    }

    /**
     * Color gray61.
     *
     * @return static
     */
    public static function gray61()
    {
        return new static(Color::GRAY61);
    }

    /**
     * Color grey61.
     *
     * @return static
     */
    public static function grey61()
    {
        return new static(Color::GREY61);
    }

    /**
     * Color gray60.
     *
     * @return static
     */
    public static function gray60()
    {
        return new static(Color::GRAY60);
    }

    /**
     * Color grey60.
     *
     * @return static
     */
    public static function grey60()
    {
        return new static(Color::GREY60);
    }

    /**
     * Color gray59.
     *
     * @return static
     */
    public static function gray59()
    {
        return new static(Color::GRAY59);
    }

    /**
     * Color grey59.
     *
     * @return static
     */
    public static function grey59()
    {
        return new static(Color::GREY59);
    }

    /**
     * Color gray58.
     *
     * @return static
     */
    public static function gray58()
    {
        return new static(Color::GRAY58);
    }

    /**
     * Color grey58.
     *
     * @return static
     */
    public static function grey58()
    {
        return new static(Color::GREY58);
    }

    /**
     * Color gray57.
     *
     * @return static
     */
    public static function gray57()
    {
        return new static(Color::GRAY57);
    }

    /**
     * Color grey57.
     *
     * @return static
     */
    public static function grey57()
    {
        return new static(Color::GREY57);
    }

    /**
     * Color gray56.
     *
     * @return static
     */
    public static function gray56()
    {
        return new static(Color::GRAY56);
    }

    /**
     * Color grey56.
     *
     * @return static
     */
    public static function grey56()
    {
        return new static(Color::GREY56);
    }

    /**
     * Color gray55.
     *
     * @return static
     */
    public static function gray55()
    {
        return new static(Color::GRAY55);
    }

    /**
     * Color grey55.
     *
     * @return static
     */
    public static function grey55()
    {
        return new static(Color::GREY55);
    }

    /**
     * Color gray54.
     *
     * @return static
     */
    public static function gray54()
    {
        return new static(Color::GRAY54);
    }

    /**
     * Color grey54.
     *
     * @return static
     */
    public static function grey54()
    {
        return new static(Color::GREY54);
    }

    /**
     * Color gray53.
     *
     * @return static
     */
    public static function gray53()
    {
        return new static(Color::GRAY53);
    }

    /**
     * Color grey53.
     *
     * @return static
     */
    public static function grey53()
    {
        return new static(Color::GREY53);
    }

    /**
     * Color gray52.
     *
     * @return static
     */
    public static function gray52()
    {
        return new static(Color::GRAY52);
    }

    /**
     * Color grey52.
     *
     * @return static
     */
    public static function grey52()
    {
        return new static(Color::GREY52);
    }

    /**
     * Color gray51.
     *
     * @return static
     */
    public static function gray51()
    {
        return new static(Color::GRAY51);
    }

    /**
     * Color grey51.
     *
     * @return static
     */
    public static function grey51()
    {
        return new static(Color::GREY51);
    }

    /**
     * Color fractal.
     *
     * @return static
     */
    public static function fractal()
    {
        return new static(Color::FRACTAL);
    }

    /**
     * Color gray50.
     *
     * @return static
     */
    public static function gray50()
    {
        return new static(Color::GRAY50);
    }

    /**
     * Color grey50.
     *
     * @return static
     */
    public static function grey50()
    {
        return new static(Color::GREY50);
    }

    /**
     * Color gray.
     *
     * @return static
     */
    public static function gray()
    {
        return new static(Color::GRAY);
    }

    /**
     * Color grey.
     *
     * @return static
     */
    public static function grey()
    {
        return new static(Color::GREY);
    }

    /**
     * Color gray49.
     *
     * @return static
     */
    public static function gray49()
    {
        return new static(Color::GRAY49);
    }

    /**
     * Color grey49.
     *
     * @return static
     */
    public static function grey49()
    {
        return new static(Color::GREY49);
    }

    /**
     * Color gray48.
     *
     * @return static
     */
    public static function gray48()
    {
        return new static(Color::GRAY48);
    }

    /**
     * Color grey48.
     *
     * @return static
     */
    public static function grey48()
    {
        return new static(Color::GREY48);
    }

    /**
     * Color gray47.
     *
     * @return static
     */
    public static function gray47()
    {
        return new static(Color::GRAY47);
    }

    /**
     * Color grey47.
     *
     * @return static
     */
    public static function grey47()
    {
        return new static(Color::GREY47);
    }

    /**
     * Color gray46.
     *
     * @return static
     */
    public static function gray46()
    {
        return new static(Color::GRAY46);
    }

    /**
     * Color grey46.
     *
     * @return static
     */
    public static function grey46()
    {
        return new static(Color::GREY46);
    }

    /**
     * Color gray45.
     *
     * @return static
     */
    public static function gray45()
    {
        return new static(Color::GRAY45);
    }

    /**
     * Color grey45.
     *
     * @return static
     */
    public static function grey45()
    {
        return new static(Color::GREY45);
    }

    /**
     * Color gray44.
     *
     * @return static
     */
    public static function gray44()
    {
        return new static(Color::GRAY44);
    }

    /**
     * Color grey44.
     *
     * @return static
     */
    public static function grey44()
    {
        return new static(Color::GREY44);
    }

    /**
     * Color gray43.
     *
     * @return static
     */
    public static function gray43()
    {
        return new static(Color::GRAY43);
    }

    /**
     * Color grey43.
     *
     * @return static
     */
    public static function grey43()
    {
        return new static(Color::GREY43);
    }

    /**
     * Color gray42.
     *
     * @return static
     */
    public static function gray42()
    {
        return new static(Color::GRAY42);
    }

    /**
     * Color grey42.
     *
     * @return static
     */
    public static function grey42()
    {
        return new static(Color::GREY42);
    }

    /**
     * Color dimgray.
     *
     * @return static
     */
    public static function dimgray()
    {
        return new static(Color::DIMGRAY);
    }

    /**
     * Color dimgrey.
     *
     * @return static
     */
    public static function dimgrey()
    {
        return new static(Color::DIMGREY);
    }

    /**
     * Color gray41.
     *
     * @return static
     */
    public static function gray41()
    {
        return new static(Color::GRAY41);
    }

    /**
     * Color grey41.
     *
     * @return static
     */
    public static function grey41()
    {
        return new static(Color::GREY41);
    }

    /**
     * Color gray40.
     *
     * @return static
     */
    public static function gray40()
    {
        return new static(Color::GRAY40);
    }

    /**
     * Color grey40.
     *
     * @return static
     */
    public static function grey40()
    {
        return new static(Color::GREY40);
    }

    /**
     * Color gray39.
     *
     * @return static
     */
    public static function gray39()
    {
        return new static(Color::GRAY39);
    }

    /**
     * Color grey39.
     *
     * @return static
     */
    public static function grey39()
    {
        return new static(Color::GREY39);
    }

    /**
     * Color gray38.
     *
     * @return static
     */
    public static function gray38()
    {
        return new static(Color::GRAY38);
    }

    /**
     * Color grey38.
     *
     * @return static
     */
    public static function grey38()
    {
        return new static(Color::GREY38);
    }

    /**
     * Color gray37.
     *
     * @return static
     */
    public static function gray37()
    {
        return new static(Color::GRAY37);
    }

    /**
     * Color grey37.
     *
     * @return static
     */
    public static function grey37()
    {
        return new static(Color::GREY37);
    }

    /**
     * Color gray36.
     *
     * @return static
     */
    public static function gray36()
    {
        return new static(Color::GRAY36);
    }

    /**
     * Color grey36.
     *
     * @return static
     */
    public static function grey36()
    {
        return new static(Color::GREY36);
    }

    /**
     * Color gray35.
     *
     * @return static
     */
    public static function gray35()
    {
        return new static(Color::GRAY35);
    }

    /**
     * Color grey35.
     *
     * @return static
     */
    public static function grey35()
    {
        return new static(Color::GREY35);
    }

    /**
     * Color gray34.
     *
     * @return static
     */
    public static function gray34()
    {
        return new static(Color::GRAY34);
    }

    /**
     * Color grey34.
     *
     * @return static
     */
    public static function grey34()
    {
        return new static(Color::GREY34);
    }

    /**
     * Color gray33.
     *
     * @return static
     */
    public static function gray33()
    {
        return new static(Color::GRAY33);
    }

    /**
     * Color grey33.
     *
     * @return static
     */
    public static function grey33()
    {
        return new static(Color::GREY33);
    }

    /**
     * Color gray32.
     *
     * @return static
     */
    public static function gray32()
    {
        return new static(Color::GRAY32);
    }

    /**
     * Color grey32.
     *
     * @return static
     */
    public static function grey32()
    {
        return new static(Color::GREY32);
    }

    /**
     * Color gray31.
     *
     * @return static
     */
    public static function gray31()
    {
        return new static(Color::GRAY31);
    }

    /**
     * Color grey31.
     *
     * @return static
     */
    public static function grey31()
    {
        return new static(Color::GREY31);
    }

    /**
     * Color gray30.
     *
     * @return static
     */
    public static function gray30()
    {
        return new static(Color::GRAY30);
    }

    /**
     * Color grey30.
     *
     * @return static
     */
    public static function grey30()
    {
        return new static(Color::GREY30);
    }

    /**
     * Color gray29.
     *
     * @return static
     */
    public static function gray29()
    {
        return new static(Color::GRAY29);
    }

    /**
     * Color grey29.
     *
     * @return static
     */
    public static function grey29()
    {
        return new static(Color::GREY29);
    }

    /**
     * Color gray28.
     *
     * @return static
     */
    public static function gray28()
    {
        return new static(Color::GRAY28);
    }

    /**
     * Color grey28.
     *
     * @return static
     */
    public static function grey28()
    {
        return new static(Color::GREY28);
    }

    /**
     * Color gray27.
     *
     * @return static
     */
    public static function gray27()
    {
        return new static(Color::GRAY27);
    }

    /**
     * Color grey27.
     *
     * @return static
     */
    public static function grey27()
    {
        return new static(Color::GREY27);
    }

    /**
     * Color gray26.
     *
     * @return static
     */
    public static function gray26()
    {
        return new static(Color::GRAY26);
    }

    /**
     * Color grey26.
     *
     * @return static
     */
    public static function grey26()
    {
        return new static(Color::GREY26);
    }

    /**
     * Color gray25.
     *
     * @return static
     */
    public static function gray25()
    {
        return new static(Color::GRAY25);
    }

    /**
     * Color grey25.
     *
     * @return static
     */
    public static function grey25()
    {
        return new static(Color::GREY25);
    }

    /**
     * Color gray24.
     *
     * @return static
     */
    public static function gray24()
    {
        return new static(Color::GRAY24);
    }

    /**
     * Color grey24.
     *
     * @return static
     */
    public static function grey24()
    {
        return new static(Color::GREY24);
    }

    /**
     * Color gray23.
     *
     * @return static
     */
    public static function gray23()
    {
        return new static(Color::GRAY23);
    }

    /**
     * Color grey23.
     *
     * @return static
     */
    public static function grey23()
    {
        return new static(Color::GREY23);
    }

    /**
     * Color gray22.
     *
     * @return static
     */
    public static function gray22()
    {
        return new static(Color::GRAY22);
    }

    /**
     * Color grey22.
     *
     * @return static
     */
    public static function grey22()
    {
        return new static(Color::GREY22);
    }

    /**
     * Color gray21.
     *
     * @return static
     */
    public static function gray21()
    {
        return new static(Color::GRAY21);
    }

    /**
     * Color grey21.
     *
     * @return static
     */
    public static function grey21()
    {
        return new static(Color::GREY21);
    }

    /**
     * Color gray20.
     *
     * @return static
     */
    public static function gray20()
    {
        return new static(Color::GRAY20);
    }

    /**
     * Color grey20.
     *
     * @return static
     */
    public static function grey20()
    {
        return new static(Color::GREY20);
    }

    /**
     * Color gray19.
     *
     * @return static
     */
    public static function gray19()
    {
        return new static(Color::GRAY19);
    }

    /**
     * Color grey19.
     *
     * @return static
     */
    public static function grey19()
    {
        return new static(Color::GREY19);
    }

    /**
     * Color gray18.
     *
     * @return static
     */
    public static function gray18()
    {
        return new static(Color::GRAY18);
    }

    /**
     * Color grey18.
     *
     * @return static
     */
    public static function grey18()
    {
        return new static(Color::GREY18);
    }

    /**
     * Color gray17.
     *
     * @return static
     */
    public static function gray17()
    {
        return new static(Color::GRAY17);
    }

    /**
     * Color grey17.
     *
     * @return static
     */
    public static function grey17()
    {
        return new static(Color::GREY17);
    }

    /**
     * Color gray16.
     *
     * @return static
     */
    public static function gray16()
    {
        return new static(Color::GRAY16);
    }

    /**
     * Color grey16.
     *
     * @return static
     */
    public static function grey16()
    {
        return new static(Color::GREY16);
    }

    /**
     * Color gray15.
     *
     * @return static
     */
    public static function gray15()
    {
        return new static(Color::GRAY15);
    }

    /**
     * Color grey15.
     *
     * @return static
     */
    public static function grey15()
    {
        return new static(Color::GREY15);
    }

    /**
     * Color gray14.
     *
     * @return static
     */
    public static function gray14()
    {
        return new static(Color::GRAY14);
    }

    /**
     * Color grey14.
     *
     * @return static
     */
    public static function grey14()
    {
        return new static(Color::GREY14);
    }

    /**
     * Color gray13.
     *
     * @return static
     */
    public static function gray13()
    {
        return new static(Color::GRAY13);
    }

    /**
     * Color grey13.
     *
     * @return static
     */
    public static function grey13()
    {
        return new static(Color::GREY13);
    }

    /**
     * Color gray12.
     *
     * @return static
     */
    public static function gray12()
    {
        return new static(Color::GRAY12);
    }

    /**
     * Color grey12.
     *
     * @return static
     */
    public static function grey12()
    {
        return new static(Color::GREY12);
    }

    /**
     * Color gray11.
     *
     * @return static
     */
    public static function gray11()
    {
        return new static(Color::GRAY11);
    }

    /**
     * Color grey11.
     *
     * @return static
     */
    public static function grey11()
    {
        return new static(Color::GREY11);
    }

    /**
     * Color gray10.
     *
     * @return static
     */
    public static function gray10()
    {
        return new static(Color::GRAY10);
    }

    /**
     * Color grey10.
     *
     * @return static
     */
    public static function grey10()
    {
        return new static(Color::GREY10);
    }

    /**
     * Color gray9.
     *
     * @return static
     */
    public static function gray9()
    {
        return new static(Color::GRAY9);
    }

    /**
     * Color grey9.
     *
     * @return static
     */
    public static function grey9()
    {
        return new static(Color::GREY9);
    }

    /**
     * Color gray8.
     *
     * @return static
     */
    public static function gray8()
    {
        return new static(Color::GRAY8);
    }

    /**
     * Color grey8.
     *
     * @return static
     */
    public static function grey8()
    {
        return new static(Color::GREY8);
    }

    /**
     * Color gray7.
     *
     * @return static
     */
    public static function gray7()
    {
        return new static(Color::GRAY7);
    }

    /**
     * Color grey7.
     *
     * @return static
     */
    public static function grey7()
    {
        return new static(Color::GREY7);
    }

    /**
     * Color gray6.
     *
     * @return static
     */
    public static function gray6()
    {
        return new static(Color::GRAY6);
    }

    /**
     * Color grey6.
     *
     * @return static
     */
    public static function grey6()
    {
        return new static(Color::GREY6);
    }

    /**
     * Color gray5.
     *
     * @return static
     */
    public static function gray5()
    {
        return new static(Color::GRAY5);
    }

    /**
     * Color grey5.
     *
     * @return static
     */
    public static function grey5()
    {
        return new static(Color::GREY5);
    }

    /**
     * Color gray4.
     *
     * @return static
     */
    public static function gray4()
    {
        return new static(Color::GRAY4);
    }

    /**
     * Color grey4.
     *
     * @return static
     */
    public static function grey4()
    {
        return new static(Color::GREY4);
    }

    /**
     * Color gray3.
     *
     * @return static
     */
    public static function gray3()
    {
        return new static(Color::GRAY3);
    }

    /**
     * Color grey3.
     *
     * @return static
     */
    public static function grey3()
    {
        return new static(Color::GREY3);
    }

    /**
     * Color gray2.
     *
     * @return static
     */
    public static function gray2()
    {
        return new static(Color::GRAY2);
    }

    /**
     * Color grey2.
     *
     * @return static
     */
    public static function grey2()
    {
        return new static(Color::GREY2);
    }

    /**
     * Color gray1.
     *
     * @return static
     */
    public static function gray1()
    {
        return new static(Color::GRAY1);
    }

    /**
     * Color grey1.
     *
     * @return static
     */
    public static function grey1()
    {
        return new static(Color::GREY1);
    }

    /**
     * Color black.
     *
     * @return static
     */
    public static function black()
    {
        return new static(Color::BLACK);
    }

    /**
     * Color gray0.
     *
     * @return static
     */
    public static function gray0()
    {
        return new static(Color::GRAY0);
    }

    /**
     * Color grey0.
     *
     * @return static
     */
    public static function grey0()
    {
        return new static(Color::GREY0);
    }

    /**
     * Color opaque.
     *
     * @return static
     */
    public static function opaque()
    {
        return new static(Color::OPAQUE);
    }

    /**
     * Color none.
     *
     * @return static
     */
    public static function none()
    {
        return new static(Color::NONE);
    }

    /**
     * Color transparent.
     *
     * @return static
     */
    public static function transparent()
    {
        return new static(Color::TRANSPARENT);
    }

    /**
     * Color in RGB.
     *
     * @param string $hexColor The hexadecimal color.
     *
     * @return static
     */
    public static function rgb($hexColor)
    {
        return new static(StringUtils::ensureStartsWith($hexColor, '#'));
    }
}

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

use Cloudinary\Asset\Image;
use Cloudinary\Transformation\Format;

/**
 * @param $url
 * @param $transformation
 *
 * @return string
 */
function getFormattedUrl($url, $transformation)
{
    $urlStart   = substr($url, 0, strPosX($url, '/', 4) + 1);
    $urlEnd     = str_replace($urlStart, '', $url);
    $urlFolders = substr($urlEnd, 0, strPosX($urlEnd, '/', 2) + 1);
    $urlEnd     = str_replace($urlStart . $urlFolders . $transformation, '', $url);

    return '<span class="url-start">' . $urlStart . '</span>' .
           '<span class="url-folders">' . $urlFolders . '</span>' .
           '<span class="url-transformation">' . $transformation . '</span>' .
           '<span class="url-end">' . $urlEnd . '</span>';
}

/**
 * @param $html
 *
 * @return string
 */
function getFormattedHtml($html)
{
    return '<span class="html-tag">' . htmlspecialchars($html) . '</span>';
}

/**
 * @param      $code
 *
 * @param bool $keepSpaces
 *
 * @return bool|mixed|string
 */
function getColoredPhp($code, $keepSpaces = true)
{
    $phpStr = [
        '<span style="color: #FFFFFF">&lt;?php</span>',
        '<span style="color: #FFFFFF">&lt;?php&nbsp;</span>',
        '&lt;?php&nbsp;',
    ];
    $result = highlight_string('<?php ' . $code, true);
    if (! $keepSpaces) {
        $result = strip_tags($result, '<span>');
        $result = removeDoubleSpaces($result);
    }
    $result = str_replace($phpStr, '', $result);

    if ($keepSpaces) {
        return '<pre>' . $result . '</pre>';
    }

    return $result;
}

/**
 * Find the position of the Xth occurrence of a substring in a string
 *
 * @param     $haystack
 * @param     $needle
 * @param int $number integer > 0
 *
 * @return int|false
 */
function strPosX($haystack, $needle, $number)
{
    if ($number == 1) {
        return strpos($haystack, $needle);
    }

    if ($number > 1) {
        return strpos($haystack, $needle, strPosX($haystack, $needle, $number - 1) + strlen($needle));
    }

    return false;
}


/**
 * @param $str
 *
 * @return mixed
 */
function removeDoubleSpaces($str)
{
    while (strpos($str, '&nbsp;&nbsp;') !== false) {
        $str = str_replace('&nbsp;&nbsp;', '&nbsp;', $str);
    }

    return $str;
}


/**
 * @param $title
 * @param $description
 *
 * @return string
 */
function getPageStart($title, $description)
{
    return '
        <html lang="en">
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                <meta name="description" content=' . $description . '>
                <meta name="author" content="Cloudinary">
                <title>' . $title . '</title>
                <meta content="Learn how to upload files with only a few lines of PHP code, including cloud storage,
                CDN delivery, and dynamic effects for images and media."
                      name="description">
                <link href="https://fonts.googleapis.com/css?family=Roboto:500,400italic,300,700,500italic,400" 
                      rel="stylesheet">
            
                <link href="https://fonts.googleapis.com/css?family=Montserrat:300,500,700" rel="stylesheet">
                <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
                <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
                      integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" 
                      crossorigin="anonymous">
                <link rel="stylesheet" href="styles.css">
                <link rel="shortcut icon"
                      href="' . Image::fetch('https://cloudinary.com/favicon.png')->format(Format::ico()) . '"/>
            </head>
            <body>
                <div class="d-flex" id="wrapper">
    ';
}

function getPageEnd()
{
    return '
                </div>
            </body>
        </html>
    ';
}

function getNavLinks($navLinks, $currentNavLink)
{
    $result = '<ul class="nav nav-tabs h-100 bg-light" style="border-bottom:0">';
    foreach ($navLinks as $index => $link) {
        $url    = $link['url'];
        $text   = $link['text'];
        $active = $index == $currentNavLink ? ' active' : '';
        $color  = $index == $currentNavLink ? '#FFF' : '#000';

        $result .= "<li class='nav-item'>
                        <a class='nav-link h-100 $active' style='color: $color; padding: .9rem 1rem' href='$url'>$text
                        </a>
                   </li>";
    }
    $result .= '</ul>';

    return $result;
}

function getNavBar($navLinks, $currentNavLink)
{
    return '
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom w-100 fixed-top pb-0">
            <div class="humburger">
                <button class="btn p-0 m-0" type="button" id="menu-toggle">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="col p-0 m-0" style="max-width:185px;">
                <a href="https://cloudinary.com">
                    <img
                     src="https://cloudinary-res.cloudinary.com/image/upload/cloudinary_logo_for_white_bg.svg"
                     alt="Cloudinary"
                    />
                </a>
            </div>
            <div class="col p-0 m-0">           
            ' . getNavLinks($navLinks, $currentNavLink) .
           '</div>
        </nav>';
}

function getContent($title, $groups, $htmlContent)
{
    return '
     <div id="page-content-wrapper" class="mt-5">
        <div id="top" class="container ml-4 mt-4">
            <div class="row">
            <span class="icon-holder">
                <span class="icon framework-php"></span>
            </span>
                <h1 class="mt-4 mb-5">' . $title . '</h1>
            </div>
            <main class="main">
                <div>' .
           $htmlContent . '
                </div>' .
           groupsToString($groups) . '
            </main>
        </div>
    </div>';
}

function getSideBar($groups)
{
    return '
            <!-- Sidebar -->
    <div class="border-right fixed" id="sidebar-wrapper">
        <div class="sidebar-heading bg-light mt-6">
            <a href="#top">
                Cloudinary PHP SDK v2
            </a>
        </div>
        <div class="sidebar-groups">' .
           getSideBarGroups($groups) .
           '</div>
    </div>
    <!-- /#sidebar-wrapper -->
        ';
}

function getSideBarGroups($groups)
{
    $result = '';
    foreach ($groups as $group) {
        $name = $group['name'];
        $icon = $group['iconClass'];
        //fas fa-camera
        //fas fa-video

        $result .= '
        <div class="d-flex border-top side-group-title-container align-items-center" >
            <a class="ml-2 side-group-title">' . $name . ' <i class="' . $icon . '"></i></a>
        </div >' .
                   getSideBarSubGroups($group['items']);
    }

    return $result;
}

/**
 * @param $subGroups
 *
 * @return string
 */
function getSideBarSubGroups($subGroups)
{
    $result = '';
    foreach ($subGroups as $subGroup) {
        $name        = $subGroup['name'];
        $trimmedName = getTrimmedStr($name);
        $result      .= '
        <div class="ml-4">
            <a href="#' . $trimmedName . '">
                ' . $name . '
            </a>
        </div>
        ';
    }

    return $result;
}

function getPageScripts($tabs = ['php', 'url'])
{
    $result = '
        <!-- Bootstrap core JavaScript -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous">
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>

<!-- Menu Toggle Script -->
<script>
    $("#menu-toggle").click(function (e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
        $("#page-content-wrapper").toggleClass("toggled");
    });';

    foreach ($tabs as $tab) {
        $name   = '.' . $tab . '-tab';
        $result .= '
                $(\'' . $name . '\').click(function (e) {
        e.preventDefault();
        $(".url-content").toggleClass("show active");
        $(".php-content").toggleClass("show active");
        $(".url-tab").toggleClass("active");
        $(".php-tab").toggleClass("active");
    });';
    }
    $result .= '
        </script >';

    return $result;
}

function groupsToString($groups)
{
    $result = '';
    foreach ($groups as $group) {
        $result .= groupToString($group);
    }

    return $result;
}

/**
 * @param $group
 *
 * @return string
 */
function groupToString($group)
{
    $result = '';
    foreach ($group['items'] as $subGroup) {
        $result .= subGroupToString($subGroup);
    }

    return $result;
}

/**
 * @param $str
 *
 * @return string|string[]|null
 */
function getTrimmedStr($str)
{
    return preg_replace('/\s+/', '', (preg_replace('/[^A-Za-z0-9 ]/', '', $str)));
}


/**
 * @param $subGroup
 *
 * @return string
 */
function subGroupToString($subGroup)
{
    $trimmedName = getTrimmedStr($subGroup['name']);

    $result = '<div>
                   <div id="' . $trimmedName . '" class="pt-5">
                        <h2 class="modal-title mb-0">
                            ' . $subGroup['name'] . '
                        </h2>
                    </div>
                    <div>';

    foreach ($subGroup['items'] as $sample) {
        $result .= $sample;
    }

    $result .= '
                   </div>
            </div>';

    return $result;
}

/**
 * @return string
 */
function getSampleTabs()
{
    $result = '
<div class="row pb-5" >
  <div class="col-12 tabs" >
<!-- Nav pills -->
  <div class="col-12 tabs">
  <div>
<ul class="nav nav-pills" role="tablist">';

    foreach ($this->tabs as $key => $value) {
        $result .= '
  <li class="nav-item">
    <a class="url-tab nav-link ' . ($key < 1 ? 'active' : '') . '" role="tab" aria-controls="' . $value .
                   '" aria-selected="' . ($key < 1 ? 'true' : 'false') . '">' . strtoupper($value) . '</a>
  </li>            
  ';
    }
    $result .= '

</ul>
</div>';

    return $result;
}

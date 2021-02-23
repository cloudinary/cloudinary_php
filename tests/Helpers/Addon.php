<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Helpers;

/**
 * Class Addon
 */
class Addon
{
    const ALL                         = 'all';                       // Test all addons.
    const ASPOSE                      = 'aspose';                    // Aspose document conversion.
    const AZURE                       = 'azure';                     // Microsoft azure video indexer.
    const BG_REMOVAL                  = 'bgremoval';                 // Cloudinary AI background removal.
    const FACIAL_ATTRIBUTES_DETECTION = 'facialattributesdetection'; // Advanced facial attributes detection.
    const GOOGLE                      = 'google';                    // Google AI video moderation, google AI
                                                                     // video transcription, google auto tagging,
                                                                     // google automatic video tagging,
                                                                     // google translation.
    const IMAGGA                      = 'imagga';                    // Imagga auto tagging, crop and scale.
    const JPEGMINI                    = 'jpegmini';                  // JPEGmini image optimization.
    const LIGHTROOM                   = 'lightroom';                 // Adobe photoshop lightroom (BETA).
    const METADEFENDER                = 'metadefender';              // MetaDefender anti-malware protection.
    const NEURAL_ARTWORK              = 'neuralartwork';             // Neural artwork style transfer.
    const OBJECT_AWARE_CROPPING       = 'objectawarecropping';       // Cloudinary object-aware cropping.
    const OCR                         = 'ocr';                       // OCR text detection and extraction.
    const PIXELZ                      = 'pixelz';                    // Remove the background.
    const REKOGNITION                 = 'rekognition';               // Amazon rekognition AI moderation,
                                                                     // amazon rekognition auto tagging,
                                                                     // amazon rekognition celebrity detection.
    const URL2PNG                     = 'url2png';                   // URL2PNG website screenshots.
    const VIESUS                      = 'viesus';                    // VIESUS automatic image enhancement.
    const WEBPURIFY                   = 'webpurify';                 // WebPurify image moderation.
}

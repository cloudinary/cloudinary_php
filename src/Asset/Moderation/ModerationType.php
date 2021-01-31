<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Asset;

/**
 * Class ModerationType
 *
 * Moderation types
 *
 * @api
 */
abstract class ModerationType
{
    /**
     * The moderation type key.
     *
     * @var string
     */
    const KEY = 'moderation';

    /**
     * Automatically moderate an uploaded image using the Amazon Rekognition AI Moderation add-on.
     *
     * Amazon Rekognition is a service that makes it easy to add image analysis to your applications.
     * Cloudinary provides an add-on for Amazon Rekognition's image moderation service based on Deep
     * Learning algorithms, fully integrated into Cloudinary's image management and manipulation pipeline.
     *
     * @var string
     *
     * @see https://cloudinary.com/documentation/aws_rekognition_ai_moderation_addon
     */
    const AWS_REKOGNITION = 'aws_rek';

    /**
     * Automatically moderate an uploaded asset of any type using the MetaDefender Anti-Malware Protection add-on.
     *
     * OPSWAT's MetaDefender detects and prevents advanced threats by incorporating multi-scanning and
     * controlled data workflows. Cloudinary provides an add-on that enables you to incorporate
     * MetaDefender's anti-malware detection into your media management activities. The MetaDefender add-on
     * leverages multiple antivirus engines simultaneously to prevent viruses and malware from infecting
     * your website or mobile application.
     *
     * @var string
     *
     * @see https://cloudinary.com/documentation/metadefender_anti_malware_protection_addon
     */
    const METASCAN = 'metascan';

    /**
     * Automatically moderate an uploaded image using the WebPurify Image Moderation add-on.
     *
     * WebPurify offers an image moderation service based on human moderator experts. Cloudinary provides an
     * add-on for using WebPurify's image moderation capabilities, fully integrated into Cloudinary's image
     * management and manipulation pipeline.
     *
     * @var string
     *
     * @see https://cloudinary.com/documentation/webpurify_image_moderation_addon
     */
    const WEBPURIFY = 'webpurify';

    /**
     * Add an uploaded asset of any type to a queue of pending assets that can be moderated using the
     * Admin API or the Cloudinary Management Console.
     *
     * @var string
     */
    const MANUAL = 'manual';
}

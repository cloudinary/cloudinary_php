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
 * Class ModerationStatus
 *
 * Moderation statuses
 *
 * @api
 */
abstract class ModerationStatus
{
    /**
     * The moderation status key.
     *
     * @var string
     */
    const KEY = 'moderation_status';

    /**
     * Asset is pending moderation.
     *
     * @var string
     */
    const PENDING = 'pending';

    /**
     * Asset has passed moderation and been approved.
     *
     * @var string
     */
    const APPROVED = 'approved';

    /**
     * Asset has passed moderation and been rejected.
     *
     * @var string
     */
    const REJECTED = 'rejected';
}

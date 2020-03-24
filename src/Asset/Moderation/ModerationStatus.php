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
 */
abstract class ModerationStatus
{
    /**
     * @var string The moderation status key.
     */
    const KEY = 'moderation_status';

    /**
     * @var string Asset is pending moderation.
     */
    const PENDING = 'pending';

    /**
     * @var string Asset has passed moderation and been approved.
     */
    const APPROVED = 'approved';

    /**
     * @var string Asset has passed moderation and been rejected.
     */
    const REJECTED = 'rejected';
}

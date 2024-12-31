<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Api\Provisioning;

/**
 * A user role to use in the user management API (create/update user)
 *
 * @api
 */
class UserRole
{
    public const MASTER_ADMIN = 'master_admin';
    public const ADMIN        = 'admin';
    public const TECHNICAL_ADMIN = 'technical_admin';
    public const BILLING         = 'billing';
    public const REPORTS  = 'reports';
    public const MEDIA_LIBRARY_ADMIN = 'media_library_admin';
    public const MEDIA_LIBRARY_USER  = 'media_library_user';
}

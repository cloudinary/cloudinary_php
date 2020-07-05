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
    const MASTER_ADMIN        = 'master_admin';
    const ADMIN               = 'admin';
    const TECHNICAL_ADMIN     = 'technical_admin';
    const BILLING             = 'billing';
    const REPORTS             = 'reports';
    const MEDIA_LIBRARY_ADMIN = 'media_library_admin';
    const MEDIA_LIBRARY_USER  = 'media_library_user';
}

<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Asset;

use Cloudinary\Asset\SearchAsset;


/**
 * Class SearchAssetTest
 */
final class SearchAssetTest extends AssetTestCase
{
    public function testSearchAsset()
    {
        $s = new SearchAsset();

        $s->expression("resource_type:image AND tags=kitten AND uploaded_at>1d AND bytes>1m")
          ->sortBy("public_id", "desc")
          ->maxResults(30);

        $b64Query = "eyJleHByZXNzaW9uIjoicmVzb3VyY2VfdHlwZTppbWFnZSBBTkQgdGFncz1raXR0ZW4gQU5EIHVwbG9hZGVkX2F0" .
                    "PjFkIEFORCBieXRlcz4xbSIsIm1heF9yZXN1bHRzIjozMCwic29ydF9ieSI6W3sicHVibGljX2lkIjoiZGVzYyJ9XX0=";

        $ttl300Sig  = "431454b74cefa342e2f03e2d589b2e901babb8db6e6b149abf25bc0dd7ab20b7";
        $ttl1000Sig = "25b91426a37d4f633a9b34383c63889ff8952e7ffecef29a17d600eeb3db0db7";

        $nextCursor = self::NEXT_CURSOR;

        # default usage
        self::assertAssetUrl(
            "search/{$ttl300Sig}/300/{$b64Query}",
            $s
        );

        # same signature with next cursor
        self::assertAssetUrl(
            "search/{$ttl300Sig}/300/{$b64Query}/{$nextCursor}",
            $s->toUrl(null, self::NEXT_CURSOR)
        );

        # with custom ttl and next cursor
        self::assertAssetUrl(
            "search/{$ttl1000Sig}/1000/{$b64Query}/{$nextCursor}",
            $s->toUrl(1000, self::NEXT_CURSOR)
        );

        # ttl and cursor are set from the class
        self::assertAssetUrl(
            "search/{$ttl1000Sig}/1000/{$b64Query}/{$nextCursor}",
            $s->ttl(1000)->nextCursor(self::NEXT_CURSOR)
        );

        # private cdn
        self::assertAssetUrl(
            "search/{$ttl1000Sig}/1000/{$b64Query}/{$nextCursor}",
            $s->privateCdn(),
            ['private_cdn' => true]
        );
    }
}

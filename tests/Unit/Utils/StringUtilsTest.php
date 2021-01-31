<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Utils;

use Cloudinary\StringUtils;
use PHPUnit\Framework\TestCase;

/**
 * Class StringUtilsTest
 */
final class StringUtilsTest extends TestCase
{
    const TEST_STRING = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit';

    const SHORT_STRING_LENGTH = 16;
    const TINY_STRING_LENGTH  = 6;


    public function testTruncateMiddle()
    {
        // Simple truncate
        self::assertEquals(
            'Lorem...ing elit',
            StringUtils::truncateMiddle(self::TEST_STRING, self::SHORT_STRING_LENGTH)
        );

        // Custom glue
        self::assertEquals(
            'Lorem **ing elit',
            StringUtils::truncateMiddle(self::TEST_STRING, self::SHORT_STRING_LENGTH, '**')
        );

        // Glue eats left half
        self::assertEquals(
            '...lit',
            StringUtils::truncateMiddle(self::TEST_STRING, self::TINY_STRING_LENGTH)
        );

        // Glue overflow, glue is truncated
        self::assertEquals(
            '!@#lit',
            StringUtils::truncateMiddle(self::TEST_STRING, self::TINY_STRING_LENGTH, '!@#$')
        );

        // No truncation
        self::assertEquals(
            self::TEST_STRING,
            StringUtils::truncateMiddle(self::TEST_STRING)
        );
    }

    public function testEscapeUnsafeChars()
    {
        $expectedEscaped = '\L\o\r\e\m ipsu\m d\ol\o\r sit a\m\et, c\ons\ect\etu\r adipiscing \elit';
        self::assertEquals(
            $expectedEscaped,
            StringUtils::escapeUnsafeChars(self::TEST_STRING, 'Lorem')
        );

        self::assertEquals(
            $expectedEscaped,
            StringUtils::escapeUnsafeChars(self::TEST_STRING, ['L', 'o', 'r', 'e', 'm'])
        );

        self::assertEquals(
            "$expectedEscaped\/",
            StringUtils::escapeUnsafeChars(self::TEST_STRING.'/', 'Lorem/')
        );

        self::assertEquals(
            self::TEST_STRING,
            StringUtils::escapeUnsafeChars(self::TEST_STRING, '')
        );
    }

    public function testCamelCaseToSnakeCase()
    {
        self::assertEquals(
            'test_string',
            StringUtils::camelCaseToSnakeCase('testString')
        );

        self::assertEquals(
            't_e_s_t_s_t_r_i_n_g',
            StringUtils::camelCaseToSnakeCase('TESTSTRING')
        );

        self::assertEquals(
            'test@string',
            StringUtils::camelCaseToSnakeCase('testString', '@')
        );
    }

    public function testToAcronym()
    {
        self::assertEquals(
            '',
            StringUtils::toAcronym('')
        );

        self::assertEquals(
            't',
            StringUtils::toAcronym('test')
        );

        self::assertEquals(
            'ta',
            StringUtils::toAcronym('test_acronym')
        );

        self::assertEquals(
            'ta',
            StringUtils::toAcronym('test_acronym_exclude_me', ['exclude', 'me'])
        );

        self::assertEquals(
            'tacd',
            StringUtils::toAcronym('test@acronym@custom@delimiter', [], '@')
        );
    }
}

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
        $this->assertEquals(
            'Lorem...ing elit',
            StringUtils::truncateMiddle(self::TEST_STRING, self::SHORT_STRING_LENGTH)
        );

        // Custom glue
        $this->assertEquals(
            'Lorem **ing elit',
            StringUtils::truncateMiddle(self::TEST_STRING, self::SHORT_STRING_LENGTH, '**')
        );

        // Glue eats left half
        $this->assertEquals(
            '...lit',
            StringUtils::truncateMiddle(self::TEST_STRING, self::TINY_STRING_LENGTH)
        );

        // Glue overflow, glue is truncated
        $this->assertEquals(
            '!@#lit',
            StringUtils::truncateMiddle(self::TEST_STRING, self::TINY_STRING_LENGTH, '!@#$')
        );

        // No truncation
        $this->assertEquals(
            self::TEST_STRING,
            StringUtils::truncateMiddle(self::TEST_STRING)
        );
    }

    public function testEscapeUnsafeChars()
    {
        $expectedEscaped = '\L\o\r\e\m ipsu\m d\ol\o\r sit a\m\et, c\ons\ect\etu\r adipiscing \elit';
        $this->assertEquals(
            $expectedEscaped,
            StringUtils::escapeUnsafeChars(self::TEST_STRING, 'Lorem')
        );

        $this->assertEquals(
            $expectedEscaped,
            StringUtils::escapeUnsafeChars(self::TEST_STRING, ['L', 'o', 'r', 'e', 'm'])
        );

        $this->assertEquals(
            "$expectedEscaped\/",
            StringUtils::escapeUnsafeChars(self::TEST_STRING.'/', 'Lorem/')
        );

        $this->assertEquals(
            self::TEST_STRING,
            StringUtils::escapeUnsafeChars(self::TEST_STRING, '')
        );
    }

    public function testCamelCaseToSnakeCase()
    {
        $this->assertEquals(
            'test_string',
            StringUtils::camelCaseToSnakeCase('testString')
        );

        $this->assertEquals(
            't_e_s_t_s_t_r_i_n_g',
            StringUtils::camelCaseToSnakeCase('TESTSTRING')
        );

        $this->assertEquals(
            'test@string',
            StringUtils::camelCaseToSnakeCase('testString', '@')
        );
    }

    public function testToAcronym()
    {
        $this->assertEquals(
            '',
            StringUtils::toAcronym('')
        );

        $this->assertEquals(
            't',
            StringUtils::toAcronym('test')
        );

        $this->assertEquals(
            'ta',
            StringUtils::toAcronym('test_acronym')
        );

        $this->assertEquals(
            'ta',
            StringUtils::toAcronym('test_acronym_exclude_me', ['exclude', 'me'])
        );

        $this->assertEquals(
            'tacd',
            StringUtils::toAcronym('test@acronym@custom@delimiter', [], '@')
        );
    }
}

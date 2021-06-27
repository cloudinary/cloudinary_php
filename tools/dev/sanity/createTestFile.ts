import {ITxResult} from "./index";

const fs = require('fs');

const prettier = require('prettier');

function createTestFile(txs: ITxResult[]) {
    let file = `<?php

namespace Cloudinary\\Transformation;

require_once getenv("HOME") ."/dev/cloudinary_php_v2/vendor/autoload.php";

use Cloudinary\\Asset\\Media;
use Cloudinary\\Asset\\Image;
use Cloudinary\\Asset\\Video;
use Cloudinary\\Test\\Unit\\UnitTestCase;
use Cloudinary\\Transformation\\Argument\\Color;
use Cloudinary\\Transformation\\Argument\\GradientDirection;
use Cloudinary\\Transformation\\Argument\\RotationMode;
use Cloudinary\\Transformation\\Argument\\Text\\FontStyle;
use Cloudinary\\Transformation\\Argument\\Text\\FontWeight;
use Cloudinary\\Transformation\\Argument\\Text\\FontAntialias;
use Cloudinary\\Transformation\\Argument\\Text\\Stroke;
use Cloudinary\\Transformation\\Argument\\Text\\TextAlignment;
use Cloudinary\\Transformation\\Argument\\Text\\TextDecoration;
use Cloudinary\\Transformation\\Codec\\VideoCodecLevel;
use Cloudinary\\Transformation\\Codec\\VideoCodecProfile;
use Cloudinary\\Transformation\\Qualifier\\Dimensions\\Dpr;
use Cloudinary\\Transformation\\Variable\\Variable;
use Cloudinary\\Transformation\\Expression\\Expression;

`;

    file += `/**
 * Class TransformationSanityTest
 */\n`;
    file += `final class TransformationSanityTest extends UnitTestCase
{\n`;

    file += txs.map((txResult, index) => {

        let realCode = txResult.parsedCode;

        realCode = realCode.replace("new ImageTag('sample')", "new Transformation()");

        let test = `    public function testTransformation${index}($tr='${txResult.txString}')
    {\n`;
        const qualifiers = txResult.txString.replace(/\//g, ',').split(',').filter(n => n);
        const qualifiersStr = JSON.stringify(qualifiers).replace(/"/g,"'");

        test += `        $qualifiers = ${qualifiersStr};`;
        test += `\n\n`;
        test += `        $tAsset = ${realCode};`;
        test += `\n\n`;

        test += `        foreach ($qualifiers as $qualifier) {\n`;
        test += `            self::assertContains(
                $qualifier,
                (string)$tAsset
            );
        }`

        test += '\n    }\n'; // Close it test

        try {
            return prettier.format(test, {parser: 'babel'});
        } catch (e) {
            return test;
        }
    }).join('\n');

    file += `\n}\n`;

    try {
        return prettier.format(file, {parser: 'babel'});
    } catch (e) {
        return file;
    }
}

module.exports = createTestFile;

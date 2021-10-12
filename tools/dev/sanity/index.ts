/* eslint-disable @typescript-eslint/no-var-requires */
/* eslint-disable require-jsdoc */

import {ISanityGeneratorResponse} from "sdk-sanity-generator";

const fs = require('fs');
const sanityGenerator = require('sdk-sanity-generator').sanityGenerator;
const createTestFile = require('./createTestFile');

sanityGenerator({
    framework: 'php_2',
    requestSpreading: 70
}).then((res: ISanityGeneratorResponse) => {
    console.log(res);
    console.log(`Successful transformations: ${res.success.length}`);
    console.log(`Failed transformations: ${res.error.length}`);
    fs.writeFileSync(`${__dirname}/results.json`, JSON.stringify(res.success));
    fs.writeFileSync(`${__dirname}/TransformationSanityTest.php`, createTestFile(res.success));
});



/* eslint-disable @typescript-eslint/no-var-requires */
/* eslint-disable require-jsdoc */
const querystring = require('querystring');
const fs = require('fs');
const nodeFetch = require('node-fetch');
const file = fs.readFileSync(`${__dirname}/txList`, 'utf-8');
const createTestFile = require('./createTestFile');

export interface ITxResult {
  error: string;
  txString: string;
  parsedCode: string;
  status: number;
}

export interface ITXResults {
  success: ITxResult[],
  fail: ITxResult[]
}

/*
 * Remove duplicates and clean lines starting with #
 * @type {string[]}
 */
const transformationStrings = ([...new Set(file.split('\n'))] as string[])
    .filter((a) => a[0] !== '#').filter((a) => a);

/*
 *  Set the SDK Code Snippets Service URL (Change domain/port only
 */
const baseURL = `https://staging-code-snippets.cloudinary.com/v1/generate-code`;

const results:ITXResults = {
  success:[],
  fail: []
};

console.log(`Attempting to generate code for ${transformationStrings.length} transformations\n`);


let counter = 0;
transformationStrings.forEach(async (txString, i) => {
  // Space requests apart
  await new Promise((res) => {
    setTimeout(() => {
      res();
    }, 30 * i + 1);
  });

  console.log('Processing transformation:', i);

  let url = `https://res.cloudinary.com/demo/image/upload/${txString}/sample`;
  if (txString.startsWith('http')) {
    url = txString;
  }

  const queryArgs = {
    frameworks: ['php_2'],
    url,
    hideActionGroups:0
  };

  const queryString = querystring.stringify(queryArgs, '&', '=', {
    encodeURIComponent(a: string) {
      return a;
    }
  });

  const URL = `${baseURL}?${queryString}`;

  const res = await nodeFetch(URL).catch((e: Error) => {
    console.log(e);
  });

  const frameworksWithCode = await res.json();

  // get JS snippets
  const JSSnippet = frameworksWithCode[0];

  // Fail, but not by design (11 = Expected unnsupported feature
  if (JSSnippet.error && JSSnippet.status !== 11) {
    results.fail.push({
      txString,
      error: JSSnippet.raw_code,
      parsedCode: null,
      status: JSSnippet.status
    });
  } else { // Success
    results.success.push({
      txString,
      parsedCode: JSSnippet.raw_code,
      error: null,
      status: JSSnippet.status
    });
  }

  /*
   *  Store the result in a file of your choosing
   */
  if (counter === transformationStrings.length - 1) {
    console.log (`Successful transformations: ${results.success.length}`);
    console.log (`Failed transformations: ${results.fail.length}`);
    fs.writeFileSync(`${__dirname}/results.json`, JSON.stringify(results));
    fs.writeFileSync(`${__dirname}/TransformationSanityTest.php`, createTestFile(results.success));
  }
  counter++;
});



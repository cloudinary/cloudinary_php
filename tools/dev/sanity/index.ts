const fs = require('fs');
const nodeFetch = require('node-fetch');
const file = fs.readFileSync(`${__dirname}/txList`, 'utf-8');
const createTestFile = require('./createTestFile');

export interface ITxResult {
  error: string;
  txString: string;
  parsedCode: string;
  actionsDTO:any;
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
const baseURL = `http://localhost:8000/dev/sdk-code-gen`;

const results:ITXResults = {
  success:[],
  fail: []
};

console.log(`Attempting to generate code for ${transformationStrings.length} transformations\n`);
transformationStrings.forEach(async (txString, i) => {
  // Space requests apart
  await new Promise((res) => {
    setTimeout(() => {
      res(5 * i + 1);
    }, 5 * i + 1);
  });

  console.log('Processing transformation:', i);

  const URL = `${baseURL}?language=php&hideActionGroups=0&url=https://res.cloudinary.com/demo/image/upload/${txString}/sample`;

  const res = await nodeFetch(URL).catch((e: Error) => {
    console.log(e);
  });

  const obj = await res.json();

  // Fail
  if (obj.parsedCode.indexOf('Cannot parse') >= 0) {
    results.fail.push({
      txString,
      error: obj.parsedCode,
      parsedCode: null,
      actionsDTO: obj.actionsDTO
    });
  } else { // Success
    results.success.push({
      txString,
      parsedCode: obj.parsedCode,
      error: null,
      actionsDTO: obj.actionsDTO
    });
  }

  /*
   *  Store the result in a file o fyour choosing
   */
  if (i === transformationStrings.length - 1) {
    fs.writeFileSync(`${__dirname}/results.json`, JSON.stringify(results));
    fs.writeFileSync(`${__dirname}/TransformationSanityTest.php`, createTestFile(results.success));
  }
});



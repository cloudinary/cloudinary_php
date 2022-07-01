#!/usr/bin/env bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"

PHP_VER=$(php -v | head -n 1 | cut -d ' ' -f 2);
SDK_VER=$(php -r "echo json_decode(file_get_contents('composer.json'))->version;")


bash ${DIR}/allocate_test_cloud.sh "PHP ${PHP_VER} SDK ${SDK_VER}"

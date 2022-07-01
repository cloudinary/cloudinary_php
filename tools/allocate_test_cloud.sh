#!/usr/bin/env bash

API_ENDPOINT="https://sub-account-testing.cloudinary.com/create_sub_account"

SDK_NAME="${1}"

CLOUD_DETAILS=$(curl -sS -d "{\"prefix\" : \"${SDK_NAME}\"}" "${API_ENDPOINT}")

echo ${CLOUD_DETAILS} | php -r '$c = json_decode(stream_get_contents(STDIN))->payload; printf("cloudinary://%s:%s@%s", $c->cloudApiKey, $c->cloudApiSecret, $c->cloudName);'

2.12.0 / 2023-12-03
==================

New functionality and features
------------------------------

  * Add support for access keys management in Account Provisioning API
  * Add support for `visualSearch` Admin API
  * Add support for `fields` parameter in Search and Admin APIs
  * Add support for Search URL
  * Add support for `useFetchFormat` in `VideoTag`
  * Add support for `on_success` upload parameter

Other Changes
-------------

  * Remove redundant `teapot/status-code` dependency

2.11.0 / 2023-05-23
==================

New functionality and features
------------------------------

  * Add support for related assets Admin APIs
  * Add support for `extra_headers` option in Upload and Admin API

Other Changes
-------------

  * Fix Guzzle compatibility issues
  * Update analytics token algorithm

2.10.2 / 2023-02-01
==================

* Expose analytics token setters

2.10.1 / 2023-01-28
==================

* Fix PHP 8.2 deprecation warnings

2.10.0 / 2023-01-12
==================

New functionality and features
------------------------------

  * Add support for `SearchFolders` API
  * Add support for `media_metadata` parameter

Other Changes
-------------

  * Fix `SetMetadataField` default value handling
  * Fix format for fetched assets
  * Fix error handling in URL signature generation

2.9.2 / 2022-11-13
==================

* Add version to the reference docs

2.9.1 / 2022-11-13
==================

* Use `teapot/status-code`

2.9.0 / 2022-09-18
==================

New functionality and features
------------------------------

  * Add support for `assetsByAssetFolder` Admin API

2.8.0 / 2022-08-23
==================

New functionality and features
------------------------------

  * Allow Monolog v3

Other Changes
-------------

  * Use PHP in scripts
  * Drop Travis in favor of GitHub Actions
  * Test on PHP 8.x

2.7.1 / 2022-05-26
==================

  * Fix deprecation warning
  * Extract `Transformation` to a separate package


2.7.0 / 2022-05-23
==================

New functionality and features
------------------------------

  * Add support for `TextFit` in text layers
  * Add support for multiple ACLs in `AuthToken`
  * Add support for `reorderMetadataFields` Admin API
  * Expose HTTP Client in APIs

Other Changes
-------------

  * Fix qualifier normalization
  * Fix analytics signature with query parameters

2.6.1 / 2022-02-01
==================

  * Fix support of the lowercase response headers

2.6.0 / 2022-01-10
==================

New functionality and features
------------------------------

  * Add `OAuth` support for Upload API
  * Add support for `psr/log` v3

Other Changes
-------------

  * Improve action from qualifiers tests
  * Improve transformations tests coverage

2.5.1 / 2021-11-24
==================

  * Fix PHP 8.1 deprecation warnings
  * Fix return type of `toUrl`
  * Add syntax to code blocks in `README`

2.5.0 / 2021-11-10
==================

New functionality and features
------------------------------

  * Add support for folder decoupling
  * Add support for `assetsByAssetIds` Admin API
  * Add support for `assetByAssetId` Admin API

Other Changes
-------------

  * Fix upload chunk size configuration handling

2.4.1 / 2021-10-15
==================

  * Limit `psr/log` to version 1 for backwards compatibility
  * Fix incoming transformation serialization
  * Fix border width with user variables

2.4.0 / 2021-10-01
==================

New functionality and features
------------------------------

  * Add support for `glb` format
  * Add support for `SHA-256` signature algorithm
  * Add support for `downloadGeneratedSprite` and `downloadMulti` helpers
  * Add support for `urls` in `multi` and `sprite` Upload APIs
  * Add support for `createSlideshow` Upload API
  * Add support for variables in text style
  * Add support for `theme` effect
  * Add support for `reorderMetadataFieldDatasource` Admin API
  * Add support for `metadata` in `update` Admin API
  * Add support for stroke manipulation in text source

Other Changes
-------------

  * Refactor integration tests
  * Improve auto optimal breakpoints generation
  * Remove duplicates in Search API fields
  * Add test for expression normalization
  * Fix grouping of the layer names

2.3.0 / 2021-07-04
==================

New functionality and features
------------------------------

  * Add support for `RemoveBackground` effect
  * Add support for `ignoreMaskChannels` flag
  * Add support for `Animated::edit` action
  * Add support for `USDZ` format
  * Add support for `AVIF` image format

Other Changes
-------------

  * Fix `GuzzleHttp\Psr7` deprecation errors
  * Fix video concatenation with `transition` transformation
  * Fix support of `resource_type` in `privateDownloadUrl`
  * Fix support of incoming transformation in `upload` API

2.2.0 / 2021-05-12
==================

New functionality and features
------------------------------

  * Add support for `context` and `metadata` in `rename` Upload API
  * Add `OAuth` support to Admin Api
  * Add support for `privateDownloadUrl`

Other Changes
-------------

  * Fix `StyleTransfer` effect
  * Fix `LoggerTest` class to work in PHP 8
  * Fix unit and integration tests
  * Fix return type in doc strings
  * Fix notice in configuration serialization
  * Update PHP SDK Reference copyright date


2.1.1 / 2021-03-17
==================

  * Fix video overlay
  * Improve `AuthToken` validation, require at least `url` or `acl`
  * Improve unit tests, use a mock client to check multipart options
  * Remove unused import in CreativeTest.php

2.1.0 / 2021-03-10
==================

New functionality and features
------------------------------

  * Add `filename_override` upload parameter

Other Changes
-------------

  * Fix Upload API signature
  * Fix expression normalisation

2.0.4 / 2021-02-25
==================

  * Fix handling of array parameters in APIs
  * Fix encoding of arrays in structured metadata
  * Improve test coverage
  * Fix `PositiveFloatValue` error when value cannot be cast to string
  * Fix unstable integration tests

2.0.3 / 2021-02-19
==================

  * Fix sorting of transformation parameters. 
    Important! This fix produces different URLs for affected actions (Not relevant for `Media::fromParams`)
  * Fix `Media::fromParams` configuration consumption
  * Fix flags serialisation
  * Improve tests of `users` Provisioning API method
  * Fix metadata fields deletion after tests

2.0.2 / 2021-02-11
==================

  * Fix `Configuration` initialisation
  * Update README

2.0.1 / 2021-01-31
==================

  * Fix travis
  * Files cleanup


2.0.0 / 2021-01-31
==================

New functionality and features
------------------------------

  * Add builders for tags
  * Add support for analytics
  * Add support for download backup version api
  * Add `Effect::lightroom`

Other Changes
 -------------

  * Multiple alignments of transformations
  * Rename `secure_distribution` to `secure_cname`
  * Fix PHP8 compatibility
  * Rename `AccountConfig` to `CloudConfig`
  * Rename `Parameter` to `Qualifier`
  * Finish `fromParams` for tags

2.0.0-beta8 / 2020-11-30
========================

New functionality and features
------------------------------

  * Add method `UploadApi::downloadFolder`
  * Add support for `cinemagraph_analysis` in `UploadApi` and `AdminApi`
  * Add support for `accessibility_analysis` 
  * Add `fileReference` user value to expressions
  * Add missing `derived_next_cursor` parameter
  * Add support for `teapot 2.x`

Other Changes
-------------

  * Reference docs branding changes
  * Fix `removeAllTags` with multiple public ids
  * Fix bugs in responsive breakpoints formatting in UploadAPI
  * Fix installation command in README
  * Fix `cutter` description
  * Fix test for `eval` upload parameter
  * Move files from `unit` and `integration` directories to `Unit` and `Integration`
  * Introduce constants for external add-on names in tests

2.0.0-beta7 / 2020-09-21
========================

New functionality and features
------------------------------

  * Add guzzle 7 support
  * Add doc strings for classes
  
Other Changes
-------------

  * Remove support for instagram profile picture
  * Fix bug in `ApiUtils::serializeArrayOfArrays`
  * Fix headers serialisation in Upload API

2.0.0-beta6 / 2020-07-14
========================

New functionality and features
------------------------------

  * Add Structured Metadata
  * Add Account Provisioning API
  * Add support for `eval` upload parameter
  * Add parameters to `Vectorize` effect constructor
  * Make `signParameters` function public
  * Make `ApiUtils` class public
  
Other Changes
-------------
  
  * Fix incoming transformation serialization in upload API
  * PHP doc strings for transformation arguments, codecs, expressions and variables
  * Add class doc strings
  * Fix `testLayerParamFactory` unit test
  * Expose `GradientFade` in documentation
  * Fix `AuthToken` doc strings
  * Fix namespaces, folders, and more in tests
  * Remove spare newline

2.0.0-beta5 / 2020-06-25
========================

  * Fix `videoTag` transformations support

2.0.0-beta4 / 2020-06-24
========================

  * Fix `videoTag` builder
  * Integrate with sub-account test service

2.0.0-beta3 / 2020-06-09
========================

New functionality and features
------------------------------

  * Add support for date in Usage API
  
Other Changes
-------------

  * Fix `monolog` version conflict in `composer.json`
  * Align Border
  * Fix README
  * Fix travis environment

2.0.0-beta2 / 2020-06-01
========================

  * The second public beta of Cloudinary PHP v2

2.0.0-beta / 2020-03-24
===================

  * The first public beta of Cloudinary PHP v2

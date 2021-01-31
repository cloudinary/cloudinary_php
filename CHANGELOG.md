
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

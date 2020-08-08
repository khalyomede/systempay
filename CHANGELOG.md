# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.3.0] 2020-08-08

### Breaking

- The method `PaymentNotification::getAuthorizationResult()` will now return an instance of `AuthorizationResult` instead of a string (so you do not have to create an instance from the string anymore).

## [0.2.0] 2020-08-01

### Added

- You can process payment notifications (IPN) with a new set of methods and helpers.

## [0.1.1] 2020-07-18

### Fixed

- The library will now produce a correct signature.

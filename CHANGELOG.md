# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).


## v3.0.0

### Added
- `detectBatch` method for batch detections

### Changed
- Switched to v3 API which uses updated language detection model
- ⚠️ `detect` method result fields are `language` and `score`
- ⚠️ `simpleDetect` deprecated, use `detectCode` instead
- ⚠️ `detect` for batch detection is deprecated, use `detectBatch` instead

### Removed
- Secure mode configuration. HTTPS is always used.

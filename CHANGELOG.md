# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2025-01-XX - LTS Release

### Added
- Initial LTS release
- Full documentation
- Cron scheduler
- Task scheduling system
- Multiple scheduling formats (cron, interval, at)
- Task execution tracking
- Task failure handling

### Changed
- Improved scheduler performance
- Enhanced task execution

---

## [Unreleased]

### Planned
- Task queuing
- Distributed task execution
- Task monitoring

---

## [2.0.0] - 2026-11-23

### Breaking
- Require Triangle Engine 9 and Localzet Server 7; Engine 8 and Server 4 applications must remain on `triangle/cron` 1.x.

### Changed
- Require `triangle/engine:^9.0` and `localzet/cron:^2.0`.
- Register the scheduler as a dedicated Server 7 process through Engine 9's managed startup hook.
- Load only cron tasks with both a callback and a rule.

### Removed
- Remove the unused `triangle/events` dependency.


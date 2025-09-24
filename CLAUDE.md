# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

BEAR.FastlyModule is a PHP library providing Fastly CDN integration for the BEAR.Sunday framework. It implements cache purging functionality through Fastly's API using a dependency injection module pattern.

## Development Commands

### Testing and Quality Assurance
- `composer test` - Run PHPUnit tests
- `composer coverage` - Generate test coverage with Xdebug
- `composer pcov` - Generate coverage with PCOV extension
- `composer cs` - Check coding standards (PHP_CodeSniffer)
- `composer cs-fix` - Fix coding standards violations
- `composer sa` - Run static analysis (PHPStan + Psalm)
- `composer metrics` - Generate code metrics report

### Composite Commands
- `composer tests` - Full QA suite (coding standards + static analysis + tests)
- `composer build` - Complete build process (clean + cs + sa + coverage + metrics)
- `composer clean` - Clear analysis caches

## Architecture

### Core Design Patterns
- **Dependency Injection**: Uses Ray.Di container with modules extending `AbstractModule`
- **Interface-Based Design**: `FastlyCachePurgerInterface` with concrete `FastlyCachePurger` implementation
- **Module Composition**: `FastlyPurgeModule` (core) + `FastlyEnableSoftPurgeModule` (feature toggle)
- **Callable Objects**: Cache purger implements `__invoke()` for functional interface

### Key Components
- `FastlyCachePurger`: Main purging implementation with `__invoke(string $tag)`
- `PurgeApi`: Fastly SDK wrapper for API communication
- DI Attributes: `#[ServiceId]`, `#[SoftPurge]`, `#[FastlyApi]` for dependency qualification

### Module Structure
```
src/
├── Attribute/           # DI qualifier attributes
├── Exception/          # Custom exceptions
├── FastlyPurgeModule.php        # Main DI module
├── FastlyEnableSoftPurgeModule.php  # Soft purge feature module
├── FastlyCachePurgerInterface.php   # Core contract
└── FastlyCachePurger.php           # Implementation
```

### Testing Structure
- **Test Doubles**: Extensive fakes in `tests/Fake/`
- **Integration Tests**: Uses actual DI container with module bindings
- **Fake Application**: Complete BEAR.Sunday app in `tests/Fake/fake-app/`
- **Module Override Pattern**: `$module->override(new FakeFastlyPurgeModule())` for testing

## PHP Requirements
- **PHP 8.0+** (uses modern attributes)
- **Strict Types**: All files use `declare(strict_types=1)`
- **Maximum Static Analysis**: PHPStan level max, Psalm strict mode

## Development Workflow
1. Write tests first using fake modules for isolation
2. Run `composer cs-fix` to fix coding standards
3. Run `composer sa` for static analysis
4. Run `composer test` for full test suite
5. Use `composer build` for complete verification before commits

## Integration Points
- **BEAR.Sunday**: Integrates with resource caching via `Header::SURROGATE_KEY`
- **Fastly SDK**: Supports versions 1.0-5.0 with flexible HTTP client configuration
- **DI Container**: Ray.Di with attribute-based dependency qualification
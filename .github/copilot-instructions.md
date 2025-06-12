# Laravel Discord Webhook Notification Package - Onboarding Guide

## Overview

This project is a Laravel package that extends Laravel's built-in notification system to support Discord webhooks. It enables Laravel applications to send structured messages, rich embeds, and file attachments to Discord channels through Discord's webhook API.

**Primary Users:** Laravel developers who want to integrate Discord notifications into their applications for alerts, logging, user communications, or bot-like functionality.

**Core Value Proposition:** The package abstracts the complexity of Discord's webhook API behind Laravel's familiar notification interface, allowing developers to send Discord messages using the same patterns they use for email, SMS, or other notification channels.

**Key Capabilities:**
- Send text messages to Discord channels via webhooks
- Create rich embeds with titles, descriptions, images, and thumbnails
- Attach files to Discord messages
- Integrate seamlessly with Laravel's notification routing (both on-demand and user-specific)
- Queue notifications for background processing
- Full compatibility with Laravel's notification event system

## Project Organization

### Core Architecture

The package follows Laravel's notification channel pattern, providing three main components that work together:

**Message Construction Layer:**
- `DiscordMessage` - Fluent builder for constructing Discord webhook payloads
- `DiscordEmbed` - Builder for rich embed objects with media and structured content
- `DiscordAttachment` - Data structure for file attachments

**Laravel Integration Layer:**
- `DiscordChannel` - Laravel notification channel that handles webhook dispatch
- Integration with Laravel's `Notifiable` trait and notification routing system

### Directory Structure

```
├── src/                          # Main package source code
│   ├── DiscordChannel.php        # Laravel notification channel implementation
│   ├── DiscordMessage.php        # Main message builder with fluent API
│   ├── DiscordEmbed.php          # Rich embed content builder
│   └── DiscordAttachment.php     # File attachment data structure
├── tests/                        # Test suite
│   ├── TestCase.php             # Base test configuration with Discord webhook setup
│   └── Feature/
│       └── NotificationTest.php  # Integration tests showing usage patterns
├── .github/workflows/            # CI/CD automation
│   ├── test.yml                 # PHPUnit testing with coverage reporting
│   ├── lint.yml                 # PHP Pint code style enforcement
│   └── copilot-setup-steps.yml  # Development environment setup
├── composer.json                # Package definition and dependencies
├── phpunit.xml                  # Test configuration
├── pint.json                    # Code style rules (Laravel preset)
└── README.md                    # Complete usage documentation
```

### Core Classes and Functions

**DiscordMessage** (`src/DiscordMessage.php`)
```php
class DiscordMessage implements Arrayable, Jsonable
{
    public static function create(?string $content = null): self
    public function content(string $content): self
    public function embed(array|Arrayable $embed): self
    public function file(DiscordAttachment $attachment): self
    public function with(array $options): self
    public function isValid(): bool
    public function toArray(): array
    public function toJson(): string
}
```

**DiscordChannel** (`src/DiscordChannel.php`)
```php
class DiscordChannel
{
    public function send(mixed $notifiable, Notification $notification): ?Response
}
```

**DiscordEmbed** (`src/DiscordEmbed.php`)
```php
class DiscordEmbed implements Arrayable
{
    public static function make(?string $title = null, ...): self
    public function with(array $options): self
    public function toArray(): array
}
```

### Development Practices

**Testing Strategy:**
- Feature tests in `tests/Feature/NotificationTest.php` demonstrate real-world usage patterns
- HTTP mocking using Laravel's `Http::fake()` for testing webhook dispatch
- Code coverage reporting via Clover XML format
- Base test case provides Discord webhook URL configuration

**Code Quality:**
- PHP Pint for code style enforcement using Laravel preset
- Automated linting in CI/CD pipeline with optional auto-commit
- PSR-4 autoloading following Laravel package conventions
- Comprehensive PHPUnit test suite with coverage reporting

**CI/CD Pipeline:**
- GitHub Actions workflow for automated testing on push/PR
- Multi-step process: checkout → PHP setup → dependencies → tests → coverage
- Separate linting workflow for code style validation
- Testing against PHP 8.4 with mbstring and xdebug extensions

## Glossary of Codebase-Specific Terms

**DiscordChannel** - Laravel notification channel class that handles webhook HTTP dispatch (`src/DiscordChannel.php`)

**DiscordMessage** - Main fluent builder class for constructing Discord webhook payloads (`src/DiscordMessage.php`)

**DiscordEmbed** - Builder class for Discord rich embed objects with media/structured content (`src/DiscordEmbed.php`)

**DiscordAttachment** - Immutable data structure for file attachments with content/metadata (`src/DiscordAttachment.php`)

**toDiscordWebhook()** - Required method in notification classes returning DiscordMessage instance

**discord-webhook** - String identifier for the notification channel routing system

**routeNotificationForDiscordWebhook()** - Method on Notifiable models returning webhook URL for that entity

**payload_json** - Discord webhook API parameter name for JSON message content in multipart requests

**export-ignore** - Git attribute excluding development files from package distribution archives

**services.discord.webhook** - Laravel config key for default Discord webhook URL

**DISCORD_WEBHOOK** - Environment variable for webhook URL configuration

**Revolution\Laravel\Notification\DiscordWebhook** - Primary namespace for all package classes

**embed()** - Method on DiscordMessage accepting DiscordEmbed or array for rich content

**file()** - Method on DiscordMessage accepting DiscordAttachment for file uploads

**with()** - Method on builders accepting array for custom Discord API parameters

**isValid()** - Validation method checking message has content/embeds/attachments before send

**getAttachments()** - Internal method returning attachment array for multipart HTTP construction

**attachment://filename** - Discord embed URL scheme referencing uploaded file attachments

**files[0], files[1]** - Discord webhook multipart field names for file attachment uploads

**make()** - Static factory method pattern used across DiscordEmbed and DiscordAttachment

**Orchestra\Testbench** - Laravel package testing framework providing isolated app environment

**Http::fake()** - Laravel testing method for mocking HTTP requests to Discord webhooks

**NotificationSent** - Laravel event fired after webhook dispatch with success/failure status

**Notifiable** - Laravel trait enabling models to receive notifications via various channels

**pint** - PHP code style fixer tool configured with Laravel preset rules

**clover.xml** - Code coverage report format output to build/logs/clover.xml

**copilot-setup-steps** - GitHub Actions job name required for Copilot agent integration

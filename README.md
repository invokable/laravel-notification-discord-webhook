# Laravel Notification for Discord(Webhook)

[![Maintainability](https://qlty.sh/badges/31f8a4d4-2dc7-4331-9b55-144df15fff85/maintainability.svg)](https://qlty.sh/gh/invokable/projects/laravel-notification-discord-webhook)
[![Code Coverage](https://qlty.sh/badges/31f8a4d4-2dc7-4331-9b55-144df15fff85/test_coverage.svg)](https://qlty.sh/gh/invokable/projects/laravel-notification-discord-webhook)

## Introduction

This package allows you to easily send notifications to Discord by simply specifying a webhook. With this Laravel notification channel, you can quickly integrate Discord notifications into your application without dealing with the complexities of Discord's API. Just configure your webhook URL and start sending notifications right away.

https://discord.com/developers/docs/resources/webhook#execute-webhook

## Requirements
- PHP >= 8.2
- Laravel >= 11.0

## Installation

```shell
composer require revolution/laravel-notification-discord-webhook
```

### Uninstall
```shell
composer remove revolution/laravel-notification-discord-webhook
```

## Config
Get the webhook url from your Discord server settings.  
https://support.discord.com/hc/en-us/articles/228383668-Intro-to-Webhooks

### config/services.php
```php
    'discord' => [
        'webhook' => env('DISCORD_WEBHOOK'),
    ],
```

### .env
```
DISCORD_WEBHOOK=https://discord.com/api/webhooks/...
```

## Usage

```php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Revolution\Laravel\Notification\DiscordWebhook\DiscordChannel;
use Revolution\Laravel\Notification\DiscordWebhook\DiscordMessage;

class DiscordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected string $content)
    {
        //
    }

    public function via($notifiable): array
    {
        return [DiscordChannel::class];
    }

    public function toDiscordWebhook(object $notifiable): DiscordMessage
    {
        return DiscordMessage::create(content: $this->content);
    }
}
```

### On-Demand Notification

```php
use Illuminate\Support\Facades\Notification;

Notification::route('discord-webhook', config('services.discord.webhook'))
            ->notify(new DiscordNotification('test'));
```

### User Notification

```php
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    public function routeNotificationForDiscordWebhook($notification): string
    {
        return $this->discord_webhook;
    }
}
```

```php
$user->notify(new DiscordNotification('test'));
```

### Send embeds

```php
use Revolution\Laravel\Notification\DiscordWebhook\DiscordMessage;
use Revolution\Laravel\Notification\DiscordWebhook\DiscordEmbed;

    public function toDiscordWebhook(object $notifiable): DiscordMessage
    {
        return DiscordMessage::create()
                              ->embed(
                                  DiscordEmbed::make(
                                      title: 'INFO',
                                      description: $this->content,
                                      url: route('home'),
                                  )
                              );
    }
```

### Send attachment files

Send only file. `content` and `filename` are required.
```php
use Revolution\Laravel\Notification\DiscordWebhook\DiscordMessage;
use Revolution\Laravel\Notification\DiscordWebhook\DiscordAttachment;
use Illuminate\Support\Facades\Storage;

    public function toDiscordWebhook(object $notifiable): DiscordMessage
    {
        return DiscordMessage::create()
            ->file(
                DiscordAttachment::make(
                    content: Storage::get('test.png'),
                    filename: 'test.png',
                    description: 'test',
                    filetype: 'image/png'
                ));
    }
```

Using files in embed.
```php
use Revolution\Laravel\Notification\DiscordWebhook\DiscordMessage;
use Revolution\Laravel\Notification\DiscordWebhook\DiscordAttachment;
use Revolution\Laravel\Notification\DiscordWebhook\DiscordEmbed;
use Illuminate\Support\Facades\Storage;

    public function toDiscordWebhook(object $notifiable): DiscordMessage
    {
        return DiscordMessage::create()
                              ->embed(
                                    DiscordEmbed::make(
                                        title: 'test',
                                        description: $this->content,
                                        image: 'attachment://test.jpg',
                                        thumbnail: 'attachment://test2.jpg',
                                    )
                              );
                              ->file(DiscordAttachment::make(
                                   content: Storage::get('test.jpg'),
                                   filename: 'test.jpg', 
                                   description: 'test', 
                                   filetype: 'image/jpg'
                              ))
                              ->file(new DiscordAttachment(
                                   content: Storage::get('test2.jpg'),
                                   filename: 'test2.jpg', 
                                   description: 'test2', 
                                   filetype: 'image/jpg'
                              ));
    }
```

### Send any message

```php
    public function toDiscordWebhook(object $notifiable): DiscordMessage
    {
        return DiscordMessage::create()
                              ->with([
                                  'content' => $this->content,
                                  'embeds' => [[]],
                               ]);
    }
```

## LICENSE
MIT    

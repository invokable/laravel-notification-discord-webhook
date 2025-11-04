<?php

namespace Revolution\Laravel\Notification\DiscordWebhook;

class DiscordAttachment
{
    public function __construct(
        public readonly string $content,
        public readonly string $filename,
        public readonly string $description = '',
        public readonly string $filetype = '',
    ) {
        //
    }

    public static function make(
        string $content,
        string $filename,
        string $description = '',
        string $filetype = '',
    ): static {
        return new static(...func_get_args());
    }
}

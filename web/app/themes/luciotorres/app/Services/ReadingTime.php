<?php

namespace App\Services;

class ReadingTime
{
    private const WORDS_PER_MINUTE = 200;

    public static function calculate(int $wordCount): int
    {
        return max(1, (int) ceil($wordCount / self::WORDS_PER_MINUTE));
    }

    public static function fromContent(string $content): int
    {
        $wordCount = str_word_count(strip_tags($content));

        return self::calculate($wordCount);
    }

    public static function fromPostMeta(int $postId): ?int
    {
        $minutes = get_post_meta($postId, 'vp_reading_time', true);

        return $minutes ? (int) $minutes : null;
    }
}

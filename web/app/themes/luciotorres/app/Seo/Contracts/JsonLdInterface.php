<?php

namespace App\Seo\Contracts;

interface JsonLdInterface
{
    public function organization(array $config, bool $includeContext = true): array;
    public function website(array $config, bool $includeContext = true): array;
    public function article(array $data, bool $includeContext = true): array;
    public function breadcrumbList(array $items, bool $includeContext = true): array;
    public static function toScript(array $schema): string;
}

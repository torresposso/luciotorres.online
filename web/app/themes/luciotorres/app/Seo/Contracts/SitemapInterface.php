<?php

namespace App\Seo\Contracts;

interface SitemapInterface
{
    public function toXml(): string;
    public function getLastModified(): ?string;
    public function getContentType(): string;
    public function getCacheControl(): string;
}

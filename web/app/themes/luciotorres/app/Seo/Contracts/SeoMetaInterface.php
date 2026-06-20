<?php

namespace App\Seo\Contracts;

interface SeoMetaInterface
{
    public function getMetaDescription(): ?string;
    public function getOgTitle(): ?string;
    public function getOgDescription(): ?string;
    public function getOgImageId(): int|string|null;
    public function getOgImageUrl(): ?string;
    public function getNoindex(): bool;
    public function getCanonical(): ?string;
    public function getPostType(): string;
    public function getPostTitle(): ?string;
    public function getPostUrl(): ?string;
    public function getDatePublished(): ?string;
    public function getDateModified(): ?string;
    public function getAuthorName(): ?string;
    public function isHome(): bool;
}

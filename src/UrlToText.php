<?php

declare(strict_types=1);

namespace Phikhi\UrlToText;

use DOMDocument;
use DOMXPath;
use Phikhi\UrlToText\Enums\AllowedTags;
use Phikhi\UrlToText\Enums\DeniedTags;
use Phikhi\UrlToText\Exceptions\TagsNotProvidedException;
use Phikhi\UrlToText\Exceptions\UrlNotProvidedException;

final class UrlToText
{
    private string $url;

    /**
     * @var mixed[]
     */
    private array $allowedTags = [];

    /**
     * @var mixed[]
     */
    private array $deniedTags = [];

    /**
     * @var mixed[]
     */
    private array $extractedTexts = [];

    public function from(string $url): static
    {
        if ($url === '' || $url === '0') {
            throw new UrlNotProvidedException();
        }

        $this->url = $url;

        return $this;
    }

    /**
     * @param  mixed[]  $tags
     */
    public function allow(array $tags, bool $overwrite = false): UrlToText
    {
        return $this->filterTags($tags, 'allowedTags', $overwrite);
    }

    /**
     * @param  mixed[]  $tags
     */
    public function deny(array $tags, bool $overwrite = false): UrlToText
    {
        return $this->filterTags($tags, 'deniedTags', $overwrite);
    }

    /**
     * @param  mixed[]  $tags
     */
    private function filterTags(array $tags, string $destinationArray, bool $overwrite = false): static
    {
        if ($tags === []) {
            throw new TagsNotProvidedException();
        }

        $this->{$destinationArray} = $overwrite
            ? $tags
            : array_merge(ucfirst($destinationArray)::values(), $tags);

        $this->{$destinationArray} = array_unique($this->{$destinationArray});

        return $this;
    }

    public function extract(): static
    {
        $dom = new DOMDocument();

        libxml_use_internal_errors(true);

        if (empty($this->allowedTags)) {
            $this->allow(AllowedTags::values());
        }

        if (empty($this->deniedTags)) {
            $this->deny(DeniedTags::values());
        }

        $dom->loadHTML($this->getPageContent());
        $dom = $this->cleanDom($dom);

        $xpath = new DOMXPath($dom);
        $tags = $xpath->query($this->getAllowedTags());

        if ($tags === false) {
            return $this;
        }

        foreach ($tags as $tag) {
            $this->extractedTexts[] = $this->cleanTextContent($tag->textContent);
        }

        return $this;
    }

    private function getPageContent(): string
    {
        $pageContent = file_get_contents(
            $this->url,
            false,
            stream_context_create(StreamContext::options())
        );

        return $pageContent === false ? '' : $pageContent;
    }

    private function cleanDom(DOMDocument $dom): DOMDocument
    {
        foreach ($dom->getElementsByTagName('*') as $element) {
            if ($element->parentNode !== null && in_array($element->nodeName, $this->deniedTags)) {
                $element->parentNode->removeChild($element);
            }
        }

        return $dom;
    }

    private function getAllowedTags(): string
    {
        $tagsArray = [];

        foreach ($this->allowedTags as $tag) {
            if (! is_string($tag)) {
                continue;
            }

            $tagsArray[] = "//{$tag}";
        }

        return implode(' | ', $tagsArray);
    }

    private function cleanTextContent(string $textContent): ?string
    {
        return preg_replace("/\s{2,}/", ' ', trim(str_replace("\n", '', $textContent)));
    }

    /**
     * @return mixed[]
     */
    public function toArray(): array
    {
        return $this->extractedTexts;
    }

    public function toJson(): string
    {
        return json_encode($this->extractedTexts, JSON_THROW_ON_ERROR);
    }

    public function toText(): string
    {
        return implode("\n", $this->extractedTexts);
    }
}

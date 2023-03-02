<?php

declare(strict_types=1);

namespace Phikhi\UrlToText;

use DOMDocument;
use DOMXPath;
use Phikhi\UrlToText\Exceptions\TagsNotProvidedException;
use Phikhi\UrlToText\Exceptions\UrlNotProvidedException;

final class UrlToText
{
    protected string $url;

    protected array $allowedTags = [
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'p', 'li', 'a',
    ];

    protected array $deniedTags = [
        'style', 'script',
    ];

    protected array $streamContextOptions;

    protected array $extractedTexts = [];

    public function __construct(?string $url = null, ?array $allowedTags = null, ?array $deniedTags = null, ?array $streamContextOptions = null)
    {
        if ($url) {
            $this->from($url);
        }

        if (is_array($allowedTags)) {
            $this->allow($allowedTags);
        }

        if (is_array($deniedTags)) {
            $this->deny($deniedTags);
        }

        if (is_array($streamContextOptions)) {
            $this->streamContextOptions = $streamContextOptions;
        }

        return $this;
    }

    public function from(string $url): UrlToText
    {
        if (! $url) {
            throw new UrlNotProvidedException();
        }

        $this->url = $url;

        return $this;
    }

    public function allow(array $tags, $overwrite = false): UrlToText
    {
        return $this->filterTags($tags, 'allowedTags', $overwrite);
    }

    public function deny(array $tags, $overwrite = false): UrlToText
    {
        return $this->filterTags($tags, 'deniedTags', $overwrite);
    }

    private function filterTags(array $tags, string $destinationArray, $overwrite = false): UrlToText
    {
        if (! $tags) {
            throw new TagsNotProvidedException();
        }

        if ($overwrite) {
            $this->{$destinationArray} = $tags;
        } else {
            $this->{$destinationArray} = array_merge($this->{$destinationArray}, $tags);
        }

        $this->{$destinationArray} = array_unique($this->{$destinationArray});

        return $this;
    }

    public function extract(): UrlToText
    {
        $dom = new DOMDocument();

        // Disable libxml errors
        libxml_use_internal_errors(true);

        $dom->loadHTML(file_get_contents($this->url, false, StreamContext::create($this->streamContextOptions)));
        $dom = $this->cleanDom($dom);

        $xpath = new DOMXPath($dom);
        $tags = $xpath->query($this->getAllowedTags());

        foreach ($tags as $tag) {
            $this->extractedTexts[] = $this->cleanTextContent($tag->textContent);
        }

        return $this;
    }

    private function cleanDom(DOMDocument $dom): DOMDocument
    {
        foreach ($dom->getElementsByTagName('*') as $element) {
            if (in_array($element->nodeName, $this->deniedTags)) {
                $element->parentNode->removeChild($element);
            }
        }

        return $dom;
    }

    private function getAllowedTags(): string
    {
        $tagsArray = [];

        foreach ($this->allowedTags as $tag) {
            $tagsArray[] = "//{$tag}";
        }

        return implode(' | ', $tagsArray);
    }

    private function cleanTextContent(string $textContent): string
    {
        return preg_replace("/\s{2,}/", ' ', trim(str_replace("\n", '', $textContent)));
    }

    public function toArray(): array
    {
        return $this->extractedTexts;
    }

    public function toJson(): string
    {
        return json_encode($this->extractedTexts);
    }

    public function toText(): string
    {
        return implode("\n", $this->extractedTexts);
    }
}

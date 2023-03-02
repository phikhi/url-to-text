<?php

use Phikhi\UrlToText\UrlToText;

it('can be instantiated', function () {
    $urlToText = new UrlToText();
    expect($urlToText)->toBeInstanceOf(UrlToText::class);
});

it('has default allowed tags', function () {
    $urlToText = new UrlToText();
    expect($urlToText->getAllowedTags())
        ->toBe(['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'li', 'a']);
});

it('has default denied tags', function () {
    $urlToText = new UrlToText();
    expect($urlToText->getDeniedTags())
        ->toBe(['script', 'style']);
});

it('extracts text from an url to an array', function () {
    $mockedContent = '<html><body><span>Unwanted text</span><p>Desired text</p></body></html>';
    $mock = Mockery::mock('overload:Phikhi\UrlToText\UrlContent');
    $mock->shouldReceive('fetch')->andReturn($mockedContent);

    $urlToText = new UrlToText();
    expect($urlToText->from('https://example.com')->extract()->toArray())
        ->toBe(['Desired text']);
});

it('extracts text from an url to json', function () {
    $mockedContent = '<html><body><span>Unwanted text</span><p>Desired text</p></body></html>';
    $mock = Mockery::mock('overload:Phikhi\UrlToText\UrlContent');
    $mock->shouldReceive('fetch')->andReturn($mockedContent);

    $urlToText = new UrlToText();
    expect($urlToText->from('https://example.com')->extract()->toJson())
        ->toBe('["Desired text"]');
});

it('extracts text from an url to text', function () {
    $mockedContent = '<html><body><span>Unwanted text</span><p>Desired text</p></body></html>';
    $mock = Mockery::mock('overload:Phikhi\UrlToText\UrlContent');
    $mock->shouldReceive('fetch')->andReturn($mockedContent);

    $urlToText = new UrlToText();
    expect($urlToText->from('https://example.com')->extract()->toText())
        ->toBe('Desired text');
});

it('extracts only span tags', function () {
    $mockedContent = '<html><body><span>Desired span text</span><p>Unwanted text</p></body></html>';
    $mock = Mockery::mock('overload:Phikhi\UrlToText\UrlContent');
    $mock->shouldReceive('fetch')->andReturn($mockedContent);

    $urlToText = new UrlToText();
    expect($urlToText->from('https://example.com')->allow(['span'], overwrite: true)->extract()->toArray())
        ->toBe(['Desired span text']);
});

it('extracts extended allowed tags', function () {
    $mockedContent = '<html><body><span>Desired span text</span><p>Desired p text</p></body></html>';
    $mock = Mockery::mock('overload:Phikhi\UrlToText\UrlContent');
    $mock->shouldReceive('fetch')->andReturn($mockedContent);

    $urlToText = new UrlToText();
    expect($urlToText->from('https://example.com')->allow(['span'])->extract()->toArray())
        ->toBe(['Desired span text', 'Desired p text']);
});

it('extracts allows nested style tags', function () {
    $mockedContent = '<html><body><a><style>.s{display:block}</style>Desired a text</a><span>Unwanted text</span></body></html>';
    $mock = Mockery::mock('overload:Phikhi\UrlToText\UrlContent');
    $mock->shouldReceive('fetch')->andReturn($mockedContent);

    $urlToText = new UrlToText();
    expect($urlToText->from('https://example.com')->deny(['script'], overwrite: true)->extract()->toArray())
        ->toBe(['.s{display:block}Desired a text']);
});

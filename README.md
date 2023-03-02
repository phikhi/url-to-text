# Url to Text

> Extract any texts from a distant HTML page
> 🚧 WORK IN PROGRESS (do not use) 🚧

## Installation

```bash
composer require phikhi/url-to-text
```

## Usage

### Basic usage
```php
use Phikhi\UrlToText\UrlToText;

$text = (new UrlToText())
    ->from('https://phikhi.com')
    ->toArray();
/*
[
    'lorem ipsum dolor sit amet',
    'non gloriam sine audentes',
    '...'
];
*/

$text = (new UrlToText())
    ->from('https://phikhi.com')
    ->toJson();
// ['lorem ipsum dolor sit amet', 'non gloriam sine audentes', '...'];

$text = (new UrlToText())
    ->from('https://phikhi.com')
    ->toText();
/*
lorem ipsum dolor sit amet
non gloriam sine audentes
...
*/
```

### Advanced usage

You can customize the tags you want to parse
```php
$text = (new UrlToText())
    ->from('https://phikhi.com')
    ->allow(['div', 'span']) // will add these tags to the existing allowed tags array (H*, p, li, a).
    ->toArray();
```

If you want to overwrite the allowed tags array instead of extending it, you can pass a second parameter to the `allow()` method
```php
$text = (new UrlToText())
    ->from('https://phikhi.com')
    ->allow(['div', 'span'], overwrite: true) // will replace the existing allowed tags array with this one.
    ->toArray();
```

By default, `script` and `style` tags are automatically stripped before extracting the allowed tags from the DOM, to prevent some weird behavior during extraction.
But you can still customize them if you need with the `deny()` method.
```php
$text = (new UrlToText())
    ->from('https://phikhi.com')
    ->deny(['svg']) // will add the `svg` tag to the existing denied tags array (script, style).
    ->toArray();
```

If you want to overwrite the denied tags array instead of extending it, you can pass a second parameter to the `deny()` method
```php
$text = (new UrlToText())
    ->from('https://phikhi.com')
    ->deny(['svg'], overwrite: true) // will replace the existing denied tags array with this one.
    ->toArray();
```

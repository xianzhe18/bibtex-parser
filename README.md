# BibTex Parser

[![Build Status](https://travis-ci.org/renanbr/bibtex-parser.svg?branch=master)](https://travis-ci.org/renanbr/bibtex-parser)

_Bibtex Parser_ is PHP library that provides an API to read [.bib](http://mirrors.ctan.org/biblio/bibtex/base/btxdoc.pdf) files programmatically.

## Install

`composer require renanbr/bibtex-parser`

## Usage

1. Create an instance of `RenanBr\BibTexParser\ListenerInterface`;
2. Create an instance of `RenanBr\BibTexParser\Parser`;
3. Attach the Listener to the Parser;
4. Parse a _file_ calling `parseFile()`, or a _string_ calling `parseString()`;
5. Get data from the Listener (it depends on the implementation).

Sample:

```php
$listener = new RenanBr\BibTexParser\Listener;
$parser = new RenanBr\BibTexParser\Parser;
$parser->addListener($listener);
$parser->parseFile('/path/to/example.bib');
$entries = $listener->export();

$entries[0]['type'];    // article
$entries[0]['title'];   // Managing Citations With Cost-Free Tools
$entries[0]['journal']; // Behavioral {\&} Social Sciences Librarian
```

Below we have the `example.bib` source file used in the sample above.

```bib
@article{Ovadia2011,
    author = {Ovadia, Steven},
    doi = {10.1080/01639269.2011.565408},
    issn = {0163-9269},
    journal = {Behavioral {\&} Social Sciences Librarian},
    month = {apr},
    number = {2},
    pages = {107--111},
    title = {Managing Citations With Cost-Free Tools},
    url = {http://www.tandfonline.com/doi/abs/10.1080/01639269.2011.565408},
    volume = {30},
    year = {2011}
}
```

### Listener

As you may noticed, this library provides `RenanBr\BibTexParser\Listener` as a `RenanBr\BibTexParser\ListenerInterface` implementation.
Its features are:
- It replaces raw values according to their [abbreviations](http://www.bibtex.org/Format/), when this exists.
- It [concatenates](http://www.bibtex.org/Format/) values when necessary;
- It provides the `export()` method, which returns all entries found;

## API

### Parser

```php
class RenanBr\BibTexParser\Parser
{
    public function parseFile(string $file);
    public function parseString(string $string);
    public function addListener(RenanBr\BibTexParser\ListenerInterface $listener);
}
```

Both `parseFile()` and `parseString()` may throw a `RenanBr\BibTexParser\ParseException`.
The `parseFile()` may even throw a native `ErrorException` if file given can't be read.

### ListenerInterface

```php
interface RenanBr\BibTexParser\ListenerInterface
{
    public function typeFound(string $type, array $context);
    public function keyFound(string $key, array $context);
    public function valueFound(string $value, array $context);
}
```

The `$context` variable gives informations about the text found.
The context entries are:
- __offset__: Text beginning position.
  May be useful for [seeking](https://php.net/fseek) a file, for example;
- __length__: Original text length.
  It may differ from string length sent to the listener because may there are escaped values.
- __state__: Current Parser's state.
  It may assume `Parser::RAW_VALUE`, `Parser::BRACED_VALUE`, `Parser::QUOTED_VALUE` or any other pre-defined state;

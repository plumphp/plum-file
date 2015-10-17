<h1 align="center">
    <img src="http://cdn.florian.ec/plum-logo.svg" alt="Plum" width="300">
</h1>

> PlumFile includes converters and filters to work with files in Plum. Plum is a data processing pipeline for PHP.

[![Build Status](https://travis-ci.org/plumphp/plum-file.svg)](https://travis-ci.org/plumphp/plum-file)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/plumphp/plum-file/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/plumphp/plum-file/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/plumphp/plum-file/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/plumphp/plum-file/?branch=master)

Developed by [Florian Eckerstorfer](https://florian.ec) in Vienna, Europe.


Installation
------------

You can install Plum using [Composer](http://getcomposer.org).

```shell
$ composer require plumphp/plum-file
```


Usage
-----

Please refer to the [Plum documentation](https://github.com/plumphp/plum/blob/master/docs/index.md) for more
information about Plum in general.


### `FileExtensionFilter`

`Plum\PlumFile\FileExtensionFilter` checks if the file extension of a file name matches.

```php
use Plum\PlumFile\FileExtensionFilter;

$filter = new FileExtensionFilter('md');
$filter->filter('README.md'); // -> true
$filter->filter('README.html'); // -> false
```

If the item is are more complex structure, for example, an array or an object `FileExtensionFilter` uses Symfonys
[PropertyAccess](http://symfony.com/doc/current/components/property_access/introduction.html) to retrieve the filename
from the item. You can pass the path to the property as the second argument to the constructor.

```php
use Plum\PlumFile\FileExtensionFilter;

$filterArray = new FileExtensionFilter('md', '[filename]');
$filterArray->filter(['filename' => 'README.md']); // -> true
$filterArray->filter(['filename' => 'README.html']); // -> false

$item = new stdClass();
$item->filename = 'README.md';
$filterObject = new FileExtensionFilter('md', 'filename');
$filterObject->filter($item); // -> true
$item->filename = 'README.html';
$filterObject->filter($item); // -> false
```

The extension passed to the constructor can also be an array. The filter returns `true` if the given item matches any
of the extensions in the array.

```php
$filter = new FileExtensionFilter(['md', 'html']);
$filter->filter('file.md');   // -> true
$filter->filter('file.html'); // -> false
$filter->filter('file.csv`);  // -> false
```

Just like the [`FileExtensionFilter`](#fileextensionfilter) the `ModificationTimeFilter` uses the Property Access
component to retrieve the filename. You can pass the path to the property as second argument to the constructor. The
file can be either a string or an instance of `SplFileInfo`.


# `ModificationTimeFilter`

The `Plum\PlumFile\ModificationTimeFilter` returns if a file was modified before and/or after a certain time.

```php
use Plum\PlumFile\ModificationTimeFilter;

$after = new ModificationTimeFilter(['after' => new DateTime('-3 days')]);
$after->filter('modified-2-days-ago.txt'); // -> true
$after->filter('modified-4-days-ago.txt'); // -> false

$before = new ModificationTimeFilter(['before' => new DateTime('-3 days')]);
$before->filter('modified-4-days-ago.txt'); // -> true
$before->filter('modified-2-days-ago.txt'); // -> false

$range = new ModificationTimeFilter(['after' => new DateTime('-6 days'), 'before' => new DateTime('-3 days')]);
$range->filter('modified-4-days-ago.txt'); // -> true
$range->filter('modified-8-days-ago.txt'); // -> false
$range->filter('modified-2-days-ago.txt'); // -> false
```

### `FileGetContentsConverter`

The `Plum\PlumFile\FileGetContentsConverter` takes a `SplFileInfo` object or a filename and returns the content of the
file.

```php
use Plum\PlumFile\FileGetContentsConverter;

$converter = new FileGetContentsConverter();
$converter->convert('foo.txt'); // -> 'content of foo.txt'
```

If the item is a more complex structure it is possible to define the [Vale](https://github.com/cocur/vale) accessor
property for both the filename and the content.

```php
use Plum\PlumFile\FileGetContentsConverter;

$converter = new FileGetContentsConverter('file', 'content');
$converter->convert(['file' => foo.txt']);
// -> ['file' => 'foo.txt', 'content' => content of foo.txt']
```


Change Log
----------

### Version 0.2.1 (28 April 2015)

- Fix Plum version

### Version 0.2 (22 April 2015)

- Set Plum version to 0.2

### Version 0.1 (18 March 2015)

- Initial release


License
-------

The MIT license applies to plumphp/plum-finder. For the full copyright and license information,
please view the LICENSE file distributed with this source code.

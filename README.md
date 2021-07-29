# Php Smart Search/replace Functionality
## It is much easier than using regex.

### Installation:

```
composer require imanghafoori/php-search-replace
```

### Usage:



1- Lets say you want to remove double semi-colon occurances like these:
```php
$user = 1;;
$user = 2; ;
$user = 3;
;

```
Then you can define a pattern like this:
```php
$pattern = [';;' => ['replace' => ';']];
```
This will catch all the 3 cases above since the neutral php whitespaces are ignored while searching.

-------------------

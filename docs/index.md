How to use SiriusFiltration
======

```php
require_once ('path/to/siriusfiltration/autoload.php');

$filtrator = new Sirius\Filtration\Filtrator;

// syntax for adding filters
$filtrator->add($selector, $callback, $parametersForTheCallback = null, $priority = 0, $recursive = false);


// trim all the elements of the array, only on the first level
$filtrator->add('*', 'trim');

// trim all the elements of the array, recursively
$filtrator->add('*', 'trim', null, 0, true);

// strip all but the P, DIV and BR tags from the content
$filtrator->add('content', 'strip_tags', array('<p><div><br><br/>'));

$filteredPostData = $filtrator->filter($_POST);
```

The `$callback` parameter can be any callable entity: a PHP function, a static method class etc. 
The only things to keep in mind are:

1. The first argument must be the value you want filtered. `trim`, `strtolower`, `ucwords` are good candidates, but not `str_replace`.
2. The parameters passed to the callback will be added one after the other

```php
function myFilter($value, $arg1, $arg2, $arg3) {
    // this is your filter function
}

$filtrator->add('selector', 'myFilter', array(1, 2, 3));
```

Removing filters
=====

Sometimes you may want to remove filters (if your app uses events to alter its functionality).
You can do that like this:

```php
// remove a single filter
$filtrator->remove('*', 'trim');

// remove all filters
$filtrator->remove('*', true);
```

Filtering only one array element
=====

Sometimes you may need to filter a single value. For example, you may have a filtrator object that you use for a form but you use AJAX to send a single value to the server; you still need to filter the value but you don't want to repeat yourself

```php
$filteredValue = $filtrator->applyFilters('key[subkey]', $_POST['key']['subkey']);
```
The code above will apply all the filters associated with the selector that match the `key[subkey]` (`key[*]` or `*[*]` but not `*`) to the value passed as the second parameter.

Get the list of your filters
=====

You may need to retrieve the list of filters for various reasons (eg: you need to converted into a list of javascript filters for the client side)
```php
$filters = $filtrator->getAll();
// returns an array
array(
    'selector' => array(
        0 => array(
            'callback' => 'filter_callback',
            'params' => array(1, 2, 3),
            'recursive' => true
        )
    )
);
```

Caveats
=====

1. You cannot filter single values... easily.

```php
// you cannot have something like
$filteredString = $filtrator->filter('single string');

// but you can filter fake it
$filteredString = $filtrator->filter(array('single_string'))[0];
```

2. You cannot add the same callback twice

```php
// you may want to do something like
$filtrator->add('selector', 'trim', array("\n\t"));
// and later on
$filtrator->add('selector', 'trim', array(" "));
// but the second add() will not add the filter on the stack
// you can however do it like this
$filtrator->add('selector', function($value) {
    return trim($value, ' ');
});
```
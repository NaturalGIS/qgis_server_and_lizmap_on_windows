
This is a simple class to load and give translated strings, for localization.

It was formerly used in the Jelix Framework.


# installation

You can install it from Composer. In your project:

```
composer require "jelix/simplelocalization"
```

# Usage

First create lang files. You can store strings of all lang into a single file, or
in one file per lang. Each file is a PHP array, and each strings have a key.

Example. Here is a file `translation.en.php` for english strings:

```php
<?php

return array(
    'welcome' => 'Hello world',
    'thank.you' => 'Thank you'
);
```

Now `translation.fr.php` for french strings:

```php
<?php

return array(
    'welcome' => 'Bonjour le monde',
    'thank.you' => 'Merci'
);
```

Or if you choose to have a single file `translation.php` :

```php
<?php

return array(
    'en' => array(
        'welcome' => 'Hello world',
        'thank.you' => 'Thank you'
    ),
    'fr' => array(
        'welcome' => 'Bonjour le monde',
        'thank.you' => 'Merci'
    )
);
```

Now in your PHP code, instanciate a container:

```php

// with multiple files
$locales = new \Jelix\SimpleLocalization\Container('translation.%LANG%.php');
// note that %LANG% in the path will be replace by a lang code

// with a single file
$locales = new \Jelix\SimpleLocalization\Container('translation.php');

// with an array of file name. You can mix path with `%LANG%` tag and path without it
$locales = new \Jelix\SimpleLocalization\Container(array('translation.%LANG%.php', 'other_translation.php'));


// with an array of strings
$locales = new \Jelix\SimpleLocalization\Container(array(
    'en' => array(
        'welcome' => 'Bonjour le monde',
        'thank.you' => 'Merci'
    ),
    'fr' => array(
        'welcome' => 'Bonjour le monde',
        'thank.you' => 'Merci'
    )
));
```

As second parameter to the constructor, you can give a lang code. If not given
the current lang will be guessed from `$_SERVER['HTTP_ACCEPT_LANGUAGE']`;


Now, to retrieve a string from its key:

```php

$str = $locales->get('welcome');

```

That's it...


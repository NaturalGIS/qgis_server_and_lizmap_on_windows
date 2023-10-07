Castor is a template engine for PHP, using syntax similar to PHP.

# Features

- A simple Api to inject data and to generate content
- A language with a syntax similar to PHP, but a bit simpler to ease learning
- Templates can be a file or a simple string
- Efficient generator: template files are "compiled" to PHP files
- A sandbox mode, to fetch untrusted templates (templates uploaded by a user in a CMS for example).
  This mode has less capabilities of course.
- A plugin system, similar to Smarty plugins.
- Plugins can be specific to a content type (HTML, XML, text…), so they produced right content.
- a system of “meta”: allow the template to expose data to PHP code. For example, a "meta"
  can be an url of a stylesheet to use with the generated content.

# History

Castor was formerly known as jTpl and was used in the [Jelix Framework](http://jelix.org)
since 2006. There was a specific version, "jTpl standalone", existing for years to
use jTpl without Jelix, but it was never released as a stable version.

In 2015, jTpl was completely "extracted" from Jelix (starting to Jelix 1.7), and is now
available as a standalone component under the name "Castor", with true stable releases. 

# installation

You can install it from Composer. In your project:

```
composer require "jelix/castor"
```

# Usage

A template file:

```
{! autoescape !}
<h1>{$titre|upper}</h1>
<ul>
{foreach $users as $user}
<li>{$user->name} ({$user->birthday|datetime:'d/m/Y'})
    <div>{$user->biography|raw}</div>
</li>
{/foreach}
</ul>
```

The PHP code:

```php

// directory where compiled templates are stored
$cachePath = realpath(__DIR__.'/temp/') . '/';

// directory where templates can be found
$templatePath = __DIR__.'/';

// create a configuration object. See its definition to learn about all of its options
$config = new \Jelix\Castor\Config($cachePath, $templatePath);

// let's create a template engine
$tpl = new \Jelix\Castor\Castor($config);

// assign some values, so they will be available for the template

$users = array(
    // User in an example class...
    new User('Tom', '2001-02-01'), 
    new User('Laurent', '1990-03-01'), 
    new User('Bob', '1970-05-25')
 );
$tpl->assign('users', $users);
$tpl->assign('titre', 'This is a test !');

// content is generated from the given template file and returned
$content = $tpl->fetch('test.tpl');

// or content is generated from the given template file and sent directly to the browser
$tpl->display('test.tpl');
```

To know more, see the docs/ directory.

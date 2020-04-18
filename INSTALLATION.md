# Installation

1. Extend the config.inc.php with the following code in `config.modules`
```php
'savas' => [
    'controller' => [
        'namespace'     => 'CMS\\Controllers\\Savas\\',
        'class_suffix'  => 'Controller',
        'method_suffix' => 'Action'
    ]
]
```
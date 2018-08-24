# Installation

1. Extend the config.inc.php with the following code in `config.modules`
```php
'frontend' => [
    'controller' => [
        'namespace'     => 'CMS\\Controllers\\Frontend\\',
        'class_suffix'  => 'Controller',
        'method_suffix' => 'Action'
    ]
]
```
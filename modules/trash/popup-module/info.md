# xeki_popup guide

+ Config
```bash
MessyJs
bower install MessiJS --save
```

+ Import module
```php
$xeki_popup =  \xeki\module_manager::import_module('xeki_popup');

```

+ Add xeki_popup

```php
$xeki_popup->add_msg("Title","message");

# only description
$xeki_popup->add_msg("message");
```
+ Common errors

Die(); on not_controllers pages or other files
Change die to returnl


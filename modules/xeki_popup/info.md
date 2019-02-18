# Popup guide

+ Config
```bash
MessyJs
bower install MessiJS --save
```

+ Import module
```php
$popUp =  \xeki\module_manager::import_module('xeki_popup');

```

+ Add Popup

```php
$popUp->add_msg("Title","message");

# only description
$popUp->add_msg("message");
```
+ Common errors

Die(); on not_controllers pages or other files
Change die to returnl


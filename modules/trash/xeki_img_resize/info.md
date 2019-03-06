**Image resize**

This module compress images to respective measures (In some cases, requires sql module).

Import module

```
$resize = \xeki\module_manager::import_module('xeki_img_resize');

```
Run

```
 ## items
 foreach ($projects as $key => $value)

     $projects[$key]['main_imagen']=$resize->resize($projects[$key]['main_imagen'], '320xauto',true);

```

In case of pc or cell phone, use:

```
if(ag_isMobile()){

   $model['primary_image']=$resize->resize($model['primary_image'],"324xauto");

}else{

   $model['primary_image']=$resize->resize($model['primary_image'],"1080xauto");

}
```

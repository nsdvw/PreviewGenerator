PreviewGenerator
================

Provides a way for generation fixed-size previews.
Extansible: you may use any library via adapter, which implements AdapterInterface.
Adapter for WideImage http://wideimage.sourceforge.net/ is included.

How to use
----------

   ``` php
use PreviewGenerator\PreviewGenerator;
use PreviewGenerator\Adapter\WideImageAdapter;

require_once "../vendor/autoload.php";

$adapter = new WideImageAdapter;
$previewGenerator = new PreviewGenerator($adapter);
$previewGenerator->load('image/source.png');
$previewGenerator->create(120, 90, 225, 225, 225)->save('image/preview.gif');
/* defaults: $width = 160, $height = 120, $red = 255, $green = 255, $blue = 255 */
```

How to install
--------------

Install via composer.
Add to composer.json next code:

   ``` json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/nsdvw/PreviewGenerator"
    }
],
"require": {
    "nsdvw/PreviewGenerator": "dev-master"
}
```

Then run composer install/update.

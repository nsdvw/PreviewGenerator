<?php
namespace PreviewGenerator\Adapter;

use WideImage\WideImage;

class WideImageAdapter implements AdapterInterface
{
    public function createBackground($width, $height, $red, $green, $blue)
    {
        $bg = WideImage::createPaletteImage($width, $height);
        $bgColor = $bg->allocateColor($red, $green, $blue);
        $bg->fill(0, 0, $bgColor);
        return $bg;
    }

    public function load($path)
    {
        return WideImage::load($path);
    }

    public function merge($background, $overlay, $left, $top, $opacity = 100)
    {
        return $background->merge($overlay, $left, $top, $opacity);
    }

    public function getWidth($image)
    {
        return $image->getWidth();
    }

    public function getHeight($image)
    {
        return $image->getHeight();
    }

    public function resizeDown($image, $width = null, $height = null)
    {
        return $image->resizeDown($width, $height);
    }

    public function save($image, $path)
    {
        return $image->saveToFile($path);
    }
}

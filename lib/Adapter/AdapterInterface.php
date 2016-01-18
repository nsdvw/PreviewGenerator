<?php
namespace PreviewGenerator\Adapter;

interface AdapterInterface
{
    public function load($path);
    public function save($image, $path);
    public function merge($background, $overlay, $left, $top, $opacity = 100);
    public function createBackground($width, $height, $red, $green, $blue);
    public function getWidth($image);
    public function getHeight($image);
    public function resizeDown($image, $width = null, $height = null);
}

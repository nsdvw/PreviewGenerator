<?php
namespace PreviewGenerator;

use PreviewGenerator\Adapter\AdapterInterface;

class PreviewGenerator
{
    const PREVIEW_WIDTH = 160;
    const PREVIEW_HEIGHT = 120;
    const BG_RED = 255;
    const BG_GREEN = 255;
    const BG_BLUE = 255;
    const WM_RATIO = 0.2;

    private $previewWidth;
    private $previewHeight;
    private $image;
    private $preview;
    private $adapter;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function load($path)
    {
        $this->image = $this->adapter->load($path);
        return $this;
    }

    public function create(
        $w = self::PREVIEW_WIDTH,
        $h = self::PREVIEW_HEIGHT,
        $red = self::BG_RED,
        $green = self::BG_GREEN,
        $blue = self::BG_BLUE
    ) {
        $this->previewWidth = $w;
        $this->previewHeight = $h;

        $adapter = $this->adapter;

        $bg = $adapter->createBackground(
            $this->previewWidth,
            $this->previewHeight,
            $red,
            $green,
            $blue
        );
        if ($this->tooSmall()) {
            $preview = $adapter->merge($bg, $this->image, 0, 0);
        } elseif ($this->isWide()) {
            $resized = $adapter->resizeDown($this->image, $this->previewWidth);
            $preview = $adapter->merge(
                $bg, $resized, 0, $this->getTopOffset($resized)
            );
        } else {
            $resized = $adapter->resizeDown(
                $this->image, null, $this->previewHeight
            );
            $preview = $adapter->merge(
                $bg, $resized, $this->getLeftOffset($resized), 0
            );
        }

        $this->preview = $preview;
        return $this;
    }

    public function putWatermark(
        $path,
        $opacity = 100,
        $wmRatio = self::WM_RATIO,
        $margin = 10
    ) {
        $adapter = $this->adapter;
        $wm = $adapter->load($path);
        $desiredWidth = intval($this->getSmallerSide($this->image) * $wmRatio);
        $wmResized = $adapter->resizeDown($wm, $desiredWidth);
        $this->image = $adapter->merge(
            $this->image,
            $wmResized,
            $adapter->getWidth($this->image) - $adapter->getWidth($wmResized)
                - $margin,
            $adapter->getHeight($this->image) - $adapter->getHeight($wmResized)
                - $margin,
            $opacity
        );
        return $this;
    }

    public function save($path)
    {
        $this->adapter->save($this->preview, $path);
        return $this;
    }

    private function isWide()
    {
        return $this->imageRatio() >= $this->previewRatio();
    }

    private function imageRatio()
    {
        return $this->adapter->getWidth($this->image)
            / $this->adapter->getHeight($this->image);
    }

    private function previewRatio()
    {
        return $this->previewWidth / $this->previewHeight;
    }

    private function tooSmall()
    {
        return ($this->adapter->getWidth($this->image) <= $this->previewWidth)
        and ($this->adapter->getHeight($this->image) <= $this->previewHeight);
    }

    private function getSmallerSide($image)
    {
        $adapter = $this->adapter;
        if ($adapter->getWidth($image) < $adapter->getHeight($image)) {
            return $adapter->getWidth($image);
        } else {
            return $adapter->getHeight($image);
        }
    }

    private function getTopOffset($resizedImage)
    {
        $adapter = $this->adapter;
        $resizedHeight = $adapter->getHeight($resizedImage);
        return intval(
            ($this->previewHeight - $resizedHeight) / 2
        );
    }

    private function getLeftOffset($resizedImage)
    {
        $adapter = $this->adapter;
        $resizedWidth = $adapter->getWidth($resizedImage);
        return intval(
            ($this->previewWidth - $resizedWidth) / 2
        );
    }
}

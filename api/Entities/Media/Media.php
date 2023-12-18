<?php

namespace Api\Entities\Media;

abstract class Media
{
    public string $name;
    public null|string $filename = null;
    public null|string $extension = null;
    public string $src;
    public int $width = 0;
    public int $height = 0;

    abstract function save();

    public function __construct()
    {
        $this->name = md5(now());
    }

    public static function make()
    {
        return new static;
    }

    public function size(int $width, int $height)
    {
        $this->width = $width;
        $this->height = $height;

        return $this;
    }

    public function source(string $src)
    {
        $this->src = $src;

        return $this;
    }

    public function name(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function filename(string $filename)
    {
        $this->filename = $filename;

        return $this;
    }

    public function extension(string $extension)
    {
        $this->extension = $extension;

        return $this;
    }
}

<?php

namespace Rahul900day\Csv;

use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use SplFileObject;

class Factory
{
    public function fromDisk(string $disk, string $path, string $open_mode = 'r'): Csv
    {
        return new Csv(Reader::createFromPath(Storage::disk($disk)->path($path), $open_mode));
    }

    public function fromFileObject(SplFileObject $file): Csv
    {
        return new Csv(Reader::createFromFileObject($file));
    }

    public function fromPath(string $path, string $open_mode = 'r'): Csv
    {
        return new Csv(Reader::createFromPath($path, $open_mode));
    }

    public function fromStream($stream): Csv
    {
        return new Csv($stream);
    }

    public function fromString(string $content): Csv
    {
        return new Csv(Reader::createFromString($content));
    }
}

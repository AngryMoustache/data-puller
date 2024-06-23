<?php

namespace Api\Entities\Media;

use App\Models\Attachment;
use Imagick;

class Ugoira extends Media
{
    public array $metadata;

    public function save(): null | Attachment
    {
        if (empty($this->metadata)) {
            return null;
        }

        $this->name = md5($this->name);

        // Save the files from the zip to a tmp folder
        @mkdir(storage_path('tmp'));

        // Pixiv requires some extra authorization to download the image
        $context = stream_context_create(['http' => [
            'method' => 'GET',
            'header' => 'Referer: https://pixiv.net'
        ]]);

        $zip = collect($this->metadata['zip_urls'])->first();
        $filename = storage_path("tmp/{$this->name}.zip");
        file_put_contents($filename, file_get_contents($zip, false, $context));

        // Unzip the file
        $zip = new \ZipArchive;
        $zip->open($filename);
        @mkdir(storage_path('tmp'));
        $zip->extractTo(storage_path("tmp/{$this->name}"));
        $zip->close();

        unlink($filename);

        // create gif
        $gif = new Imagick;
        foreach ($this->metadata['frames'] as $frame) {
            $_frame = new Imagick(storage_path("tmp/{$this->name}") . "/{$frame['file']}");
            $_frame->setImageFormat('gif');
            $_frame->setImageDelay($frame['delay'] / 1000);
            $gif->addImage($_frame);
        }

        $gif->optimizeImageLayers();
        $gif->setFormat('gif');

        // Write the gif to a file
        @mkdir(storage_path('gifs'));
        $gifPath = storage_path("gifs/{$this->name}.gif");
        $gif->writeImages($gifPath, true);

        // Clean up & send to media server
        $gif->clear();
        $gif->destroy();

        $result = Image::make()->source($gifPath)->name($this->name)->save();

        // Clean up
        @unlink($gifPath);
        foreach ($this->metadata['frames'] as $frame) {
            @unlink(storage_path("tmp/{$this->name}") . "/{$frame['file']}");
        }

        @rmdir(storage_path("tmp/{$this->name}"));

        return $result;
    }

    public function metadata(array $metadata)
    {
        $this->metadata = $metadata;

        return $this;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Attachment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CheckMissingAttachmentFields extends Command
{
    public $signature = 'check:attachments';

    public function handle()
    {
        Attachment::where('size', null)
            ->orWhere('height', null)
            ->orWhere('width', null)
            ->orWhere('mime_type', null)
            ->orWhere('md5', null)
            ->orWhere('md5', '0')
            ->orderByDesc('created_at')
            ->get()
            ->each(function (Attachment $attachment) {
                $path = Str::replace(' ', '%20', $attachment->path());
                $this->info($path);

                if (Http::get($path)->status() !== 200) {
                    $this->info('Skipping ' . $attachment->original_name);

                    return;
                }

                if (is_null($attachment->md5)) {
                    $attachment->md5 = md5_file($path);
                }

                if (is_null($attachment->width) || is_null($attachment->height)) {
                    $attachment->width = getimagesize($path)[0];
                    $attachment->height = getimagesize($path)[1];
                }

                if (is_null($attachment->size)) {
                    $attachment->size = get_headers($path, true)['Content-Length'];
                }

                if (is_null($attachment->mime_type)) {
                    $attachment->mime_type = get_headers($path, true)['Content-Type'];
                }

                $attachment->save();
            });
    }
}

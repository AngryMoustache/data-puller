<?php

namespace App;

use App\Enums\Origin as EnumsOrigin;
use App\Enums\Status;
use App\Enums\TaskType;
use App\Models\Origin;
use App\Models\Pull;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Task
{
    public string $name;

    public int $media_id;

    public TaskType $type;

    public bool $completed = false;

    public function __construct(public string $path)
    {
        $this->name = Str::afterLast($path, '/');
        $this->type = TaskType::from(Str::betweenFirst($path, 'tasks/', '/'));
    }

    public function createPull(): void
    {
        $pull = Pull::create([
            'origin_id' => Origin::where('type', EnumsOrigin::EXTERNAL)->first()->id,
            'name' => $this->name,
            'status' => Status::PENDING,
        ]);

        $media = $this->type->createMedia($this->path);

        $this->media_id = $media->id;

        DB::insert('INSERT INTO media_pull (media_type, media_id, pull_id, sort_order) values (?, ?, ?, 0)', [
            get_class($media),
            $media->id,
            $pull->id,
        ]);
    }
}

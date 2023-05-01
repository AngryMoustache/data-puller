<?php

namespace App\Http\Controllers;

use Api\Entities\ApiUpload;
use App\Models\Origin;
use App\Models\Prompt;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function store(Request $request)
    {
        // Save the upload
        $origin = Origin::where('slug', $request->input('origin'))->firstOrFail();
        (new ApiUpload($request->all()))->save($origin);

        return response()->json([
            'message' => 'Successful upload',
        ]);
    }

    public function checkPrompt()
    {
        $prompt = Prompt::day();

        $prompt->increment('discord_pinged');

        return response()->json((int) $prompt->discord_pinged);
    }
}

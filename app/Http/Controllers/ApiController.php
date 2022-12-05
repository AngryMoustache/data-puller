<?php

namespace App\Http\Controllers;

use Api\Entities\AIGeneration;
use App\Models\Origin;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function store(Request $request)
    {
        if ($request->bearerToken() !== config('app.api_token')) {
            return response()->json([
                'message' => 'Invalid token',
            ], 401);
        }

        // Save the upload
        $origin = Origin::where('slug', $request->input('origin'))->firstOrFail();
        (new AIGeneration($request->all()))->save($origin);

        return response()->json([
            'message' => 'Successful upload',
        ]);
    }
}

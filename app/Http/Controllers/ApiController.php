<?php

namespace App\Http\Controllers;

use Api\Entities\ApiUpload;
use App\Models\Origin;
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
}

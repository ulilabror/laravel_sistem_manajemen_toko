<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Resources\BaseResource;
use App\Http\Resources\FileResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $files = File::all();
        return response()->json(new BaseResource('success', FileResource::collection($files), 'Files retrieved successfully'), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'files' => 'required|array',
            'files.*' => 'file|mimes:jpeg,png,jpg,gif,svg,pdf,docx,svg|max:2048',
            'uploaded_by' => 'required|exists:users,id',
            'related_id' => 'required|integer',
            'related_type' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(new BaseResource('error', null, 'Validation Failed', $validator->errors()->all()), 422);
        }

        $files = [];

        foreach ($request->file('files') as $file) {
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('files', $filename, config('filesystems.default'));
            /** @var \Illuminate\Filesystem\FilesystemManager $disk */
            $disk = Storage::disk(config('filesystems.default'));
            $url = $disk->url($path);

            $fileRecord = File::create([
                'filename' => $filename,
                'path' => $path,
                'url' => url($url),
                'uploaded_by' => Auth::user()->id,
                'related_id' => $request->related_id,
                'related_type' => $request->related_type,
            ]);

            $files[] = new FileResource($fileRecord);
        }

        return response()->json(new BaseResource('success', $files, 'Files uploaded successfully'), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $file = File::find($id);

        if (!$file) {
            return response()->json(new BaseResource('error', null, 'File not found', ['File not found']), 404);
        }

        return response()->json(new BaseResource('success', new FileResource($file), 'File retrieved successfully'), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $file = File::find($id);

        if (!$file) {
            return response()->json(new BaseResource('error', null, 'File not found', ['File not found']), 404);
        }

        Storage::delete($file->path);
        $file->delete();

        return response()->json(new BaseResource('success', null, 'File deleted successfully'), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $file = File::find($id);

        if (!$file) {
            return response()->json(new BaseResource('error', null, 'File not found', ['File not found']), 404);
        }

        $validator = Validator::make($request->all(), [
            'filename' => 'sometimes|required|string|max:255',
            'file' => 'sometimes|required|file|mimes:jpeg,png,jpg,gif,svg,pdf,docx,svg|max:2048',
            'uploaded_by' => 'sometimes|required|exists:users,id',
            'related_id' => 'sometimes|required|integer',
            'related_type' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(new BaseResource('error', null, 'Validation Failed', $validator->errors()->all()), 422);
        }

        if ($request->hasFile('file')) {
            Storage::delete($file->path);
            $newFile = $request->file('file');
            $filename = time() . '_' . Str::uuid() . '.' . $newFile->getClientOriginalExtension();
            $path = $newFile->storeAs('files', $filename, config('filesystems.default'));
            /** @var \Illuminate\Filesystem\FilesystemManager $disk */
            $disk = Storage::disk(config('filesystems.default'));
            $url = $disk->url($path);
            $file->update([
                'filename' => $filename,
                'path' => $path,
                'url' => $url,
            ]);
        }

        $file->update($request->only(['filename', 'uploaded_by', 'related_id', 'related_type']));

        return response()->json(new BaseResource('success', new FileResource($file), 'File updated successfully'), 200);
    }
}

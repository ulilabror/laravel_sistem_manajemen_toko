<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Resources\FileResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $files = File::all();
        return response()->json($files);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'path' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,docx,svg|max:2048',
            'filename' => 'required|string|max:255',
            'uploaded_by' => 'required|exists:users,id',
            'related_id' => 'required|integer',
            'related_type' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $file = $request->file('path');
        $filename = time() . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/files', $filename);

        $file = File::create([
            'filename' => $filename,
            'path' => $path,
            'uploaded_by' => Auth::user()->id,
            'related_id' => $request->related_id,
            'related_type' => $request->related_type,
        ]);

        return response()->json(['message' => 'File uploaded successfully', 'file' => new FileResource($file)], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $file = File::find($id);

        if (!$file) {
            return response()->json(['message' => 'File not found'], 404);
        }

        return response()->json(new FileResource($file));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $file = File::find($id);

        if (!$file) {
            return response()->json(['message' => 'File not found'], 404);
        }

        Storage::delete($file->path);
        $file->delete();

        return response()->json(['message' => 'File deleted successfully']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $file = File::find($id);

        if (!$file) {
            return response()->json(['message' => 'File not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'filename' => 'sometimes|required|string|max:255',
            'path' => 'sometimes|required|file|mimes:jpeg,png,jpg,gif,svg,pdf,docx,svg|max:2048',
            'uploaded_by' => 'sometimes|required|exists:users,id',
            'related_id' => 'sometimes|required|integer',
            'related_type' => 'sometimes|required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('path')) {
            Storage::delete($file->path);
            $filePath = $request->file('path')->store('public/files');
            $file->path = $filePath;
        }

        $file->update($request->only(['filename', 'uploaded_by', 'related_id', 'related_type']));

        return response()->json(['message' => 'File updated successfully', 'file' => new FileResource($file)]);
    }
}

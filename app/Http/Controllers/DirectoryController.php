<?php

namespace App\Http\Controllers;

use App\Models\Directory;
use Flowgistics\XML\XML;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DirectoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $directories = Directory::where('parent_id', '=', 0)->get();

        $allDirectories = Directory::pluck('code','name', 'id')->all();
        $optionDir = Directory::all();

        return view('directory.index', compact(
            'directories', 'allDirectories', 'optionDir'
        ));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'code'      => ['required', 'unique:directory'],
            'name'      => ['required'],
            'parent_id' => ['required']
        ]);

        if($v->fails()) {
           return redirect()->back()->withErrors($v->errors());
        }

        if(Directory::find($request->input('parent_id')) || $request->input('parent_id') == 0) {
            Directory::create([
                'code'      => $request->input('code'),
                'name'      => $request->input('name'),
                'parent_id' => $request->input('parent_id') ?? 0
            ]);
            return redirect()->route('directory');
        }

    }

    public function edit(string $id)
    {
        $editDirectory = Directory::find($id);
        $directories = Directory::where('parent_id', '=', 0)->get();

        $allDirectories = Directory::pluck('code','name', 'id')->all();
        $optionDir = Directory::all();

        return view('directory.index', compact(
            'directories', 'allDirectories', 'optionDir', 'editDirectory'
        ));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Directory::where('id', $id)->update([
            'code' => $request->input('code'),
            'name' => $request->input('name'),
            'parent_id' => $request->parent_id ?? 0
        ]);
        return redirect()->route('directory');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Directory::destroy($id);
        Directory::where('parent_id', $id)->delete();
        return redirect()->back();
    }

    public function search(Request $request)
    {
        $directories = Directory::where('parent_id', '=', 0)
            ->where('code', 'like', '%' . $request->input('search_word') . '%')
            ->orWhere('name', 'like', '%' . $request->input('search_word') . '%')
            ->get();

        $allDirectories = Directory::where('code', 'like', '%' . $request->input('search_word') . '%')
            ->orWhere('name', 'like', '%' . $request->input('search_word') . '%')
            ->pluck('code','name', 'id');
        $optionDir = Directory::all();

        return view('directory.index', compact(
            'directories', 'allDirectories', 'optionDir'
        ));
    }

    public function import(Request $request)
    {
        // TODO send to job
        $originalName = $request->file->getClientOriginalName();
        $path = "files/". 'directory.xml';

        Storage::disk('local')->put($path, file_get_contents($request->file));

        $dirs = XML::import(storage_path('app/files/notes.xml'))
            ->toArray();

        foreach($dirs['directory'] as $dir) {
            $directory = Directory::find($dir->parent_id);
            if($dir->parent_id != 0 && isset($directory)) {
                Directory::create([
                    'code'      => $dir->code,
                    'name'      => $dir->name,
                    'parent_id' => $dir->parent_id
                ]);
            }elseif($dir->parent_id == 0 ) {
                Directory::create([
                    'code'      => $dir->code,
                    'name'      => $dir->name,
                    'parent_id' => $dir->parent_id ?? null
                ]);
            }
        }

        return redirect()->route('directory');
    }
}

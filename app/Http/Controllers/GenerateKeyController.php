<?php

namespace App\Http\Controllers;

use App\Models\RSAKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class GenerateKeyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list_keys = RSAKey::where('user_id', auth()->user()->id)->orderByDesc('created_at')->get();

        return view('generate_keys.index', compact('list_keys'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('generate_keys.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'private_key' => ['required', 'string'],
            'public_key' => ['required', 'string'],
        ]);
        $user_id = auth()->user()->id;

        $name = isset($request->name_key) ? $request->name_key : 'key_rsa_'. substr(md5(uniqid(rand(), true)), 0, 8);
        RSAKey::create([
            'private_key' => $request->private_key,
            'public_key' => $request->public_key,
            'user_id' => $user_id,
            'name' => $name
        ]);
        return redirect()->route('dashboard')->with('success_message', 'Lưu thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function downloadPublicKey(string $id)
    {
        $key = RSAKey::find($id);
        $fileName =  $key->name . '_pub.rsa';

        // Set the content type to force download
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        // Create the file for download without saving it on the server
        return Response::make($key->public_key, 200, $headers);
    }

    public function downloadPrivateKey(string $id)
    {
         $key = RSAKey::find($id);
        $fileName =  $key->name . '_pri.rsa';

        // Set the content type to force download
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        // Create the file for download without saving it on the server
        return Response::make($key->private_key, 200, $headers);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $key = RSAKey::find($id);
        $key->delete();
        return redirect()->route('dashboard')->with('success_message', 'Xóa thành công');
    }
}

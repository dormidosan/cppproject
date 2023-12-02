<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Travel;
use App\Traits\MailHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TravelController extends Controller
{
    use MailHelper;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $travels = Travel::where('user_id', Auth::id())->get();
        return view('travels.index', compact('travels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $image = new Image();
        $email = Auth::user()->email;

        $file_binary = $request->file('image');
        $travel_id = $request->input('travel_id');

        $image->travel_id = $travel_id;
        $image->original_name = $file_binary->getClientOriginalName();
        $image->s3_name = uniqid() . '_' . $file_binary->getClientOriginalName();


        $folderName = 'photos/' . $image->travel_id . '/';

        try {
            Storage::disk('s3')->putFileAs($folderName, $file_binary, $image->s3_name);
            $image->save();
            $this->sendEmail('Image uploaded in Flieghts', $email , 'You have uploaded an Image. Continue storing your moments in Flieghts');
            return redirect()->route('travels.show',$travel_id)->with('success', 'Image uploaded successfully.');
        } catch (\Exception $e) {
            return redirect()->route('travels.show',$travel_id)->with('error', 'Error uploading image');;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Travel $travel)
    {

        /**
         *Check if logged user can see this folder
         * ... CODE
         */


        //$car = Storage::disk('s3')->put('myphotos/example.txt', 'Contents');
        //$files = Storage::disk('s3')->files('photos/'.$travel->id);
        $files = Image::where('travel_id', $travel->id)->get();
        $filenames = [];
//        foreach ($files as $index => $path) {
//            $name = pathinfo($path, PATHINFO_FILENAME);
//            $filenames[$index] = ['name'=>$name,'path'=>$path];
//        }
        foreach ($files as $key => $value) {
            $path = 'photos/'.$travel->id.'/'.$value->s3_name;
            $filenames[$key] = ['name' => $value->original_name, 'path' => $path];
        }
        //dd($filenames);
        return view('travels.show', compact('filenames'))->with('travel_id', $travel->id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
        //
    }
}

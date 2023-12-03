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
        //Get the travels of the logged user
        $travels = Travel::where('user_id', Auth::id())->get();
        return view('travels.index', compact('travels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Create a new Image
        $image = new Image();
        $email = Auth::user()->email;

        //Get the data of the image
        $file_binary = $request->file('image');
        $travel_id = $request->input('travel_id');

        //Set parameters for image
        $image->travel_id = $travel_id;
        $image->original_name = $file_binary->getClientOriginalName();
        $image->s3_name = uniqid() . '_' . $file_binary->getClientOriginalName();


        //Set the url where store in bucket
        $folderName = 'photos/' . $image->travel_id . '/';

        //Try to store in bucket, if any error return an error message
        try {
            Storage::disk('s3')->putFileAs($folderName, $file_binary, $image->s3_name);
            $image->save();
            $this->sendEmail('Image uploaded in Flieghts', $email , 'You have uploaded an Image. Continue storing your moments in Flieghts');
            return redirect()->route('travels.show',$travel_id)->with('success', 'Image uploaded successfully.');
        } catch (\Exception $e) {
            return redirect()->route('travels.show',$travel_id)->with('error', 'Error uploading image');        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Travel $travel)
    {
        //Get the Images path from Database, to show the URL in the view
        $files = Image::where('travel_id', $travel->id)->get();
        $filenames = [];

        //Complete the url of the image to be accesible
        foreach ($files as $key => $value) {
            $path = 'photos/'.$travel->id.'/'.$value->s3_name;
            $filenames[$key] = ['name' => $value->original_name, 'path' => $path];
        }

        //return to the view
        return view('travels.show', compact('filenames'))->with('travel_id', $travel->id);
    }


}

<?php

namespace App\Http\Controllers;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    //all listings
    public function index()
    {
        return view('listings.index', [
            'listings'=> Listing::latest()->filter(request(['tag', 'search']))->SimplePaginate(4) //This shows "next" and "previous"
        ]);
    }

    //single listing
    public function show(Listing $listing){
        return view ('listings.show', [
            'listing' => $listing
        ]);
    }

    public function create (){
        return view ('listings.create');
    } 
    public function store (Request $request){
       $formFields = $request->validate([
           'title' => 'required',
           'company' => ['required', Rule::unique('listings', 'company')],
           'location' => 'required',
           'website' => 'required',
           'email' => ['required', 'email'],
           'tags' => 'required',
           'description' => 'required',
       ]);

       if($request->hasFile('logo')){
       $formFields['logo'] = $request->file('logo')->store('logos', 'public');

       }
       //assign user id to db from form field
       $formFields['user_id'] = auth()->id();

       Listing::create($formFields);
        return redirect('/')->with('message', 'Listing created successfully!');
    }

    public function edit(Listing $listing){
        return view ('listings.edit', ['listing'=>$listing]); 
    } 

    // Update Listing Data
    public function update(Request $request, Listing $listing) {
        // Make sure logged in user is owner
        if($listing->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }
        
        $formFields = $request->validate([
            'title' => 'required',
            'company' => ['required'],
            'location' => 'required',
            'website' => 'required',
            'email' => ['required', 'email'],
            'tags' => 'required',
            'description' => 'required'
        ]);

        if($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $listing->update($formFields);

        return redirect('/')->with('message', 'Listing updated successfully!');
    }

     public function destroy(Listing $listing){
         //Make sure only logged in user is the owner to delete the job
         if($listing->user_id !=auth()->id()){
            abort(403, 'Unauthorised Action');
        }
        $listing->delete();
        return redirect('/')->with('success', 'Listing deleted successfully!');
    } 

    public function manage(){
        return view('listings.manage', ['listings' =>auth()->user()->listings()->get()]);
    } 
} 
  
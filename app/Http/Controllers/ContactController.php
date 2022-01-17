<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Contact;
use App\Scopes\FilterScope;
use App\Scopes\SearchScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }
    public function index()
    {
        $user = Auth::user();
        // Contact::withoutGlobalScope(SearchScope::class)->get();
        $companies = $user->companies()->orderBy('name')->pluck('name', 'id')->prepend('All Companies', '');
        // \DB::enableQueryLog();
        $contacts = $user->contacts()->latestFirst()->paginate(10);
        // dd(\DB::getQueryLog());
        return view('contacts.index', compact(['contacts', 'companies']));
    }

    public function create()
    {
        $contact = new Contact();
        $companies = Auth::user()->companies()->orderBy('name')->pluck('name', 'id')->prepend('Select Company', '');
        return view('contacts.create', compact('companies', 'contact'));
    }

    public function show(Contact $contact)
    {
        return view('contacts.show', compact('contact'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'company_id' => 'required|exists:companies,id'
        ]);

        $request->user()->contacts()->create($request->all());

        return redirect()->route('contacts.index')->with('message', 'Contact has been added successfully');
    }

    public function edit(Contact $contact)
    {
        $companies = Auth::user()->companies()->orderBy('name')->pluck('name', 'id');
        return view('contacts.edit', compact('companies', 'contact'));
    }
   
    public function update(Contact $contact,Request $request )
    {
        // dd($request->company_id);
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'company_id' => 'required|exists:companies,id'
        ]);
        $contact->update($request->all());

        return redirect()->route('contacts.index')->with('message', 'Contact has been updated successfully');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return back()->with('message', 'Contact has been deleted successfully');
    }
}

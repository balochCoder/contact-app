<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Company;
use App\Models\Contact;
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
        $companies = Company::userCompanies();
      
        // \DB::enableQueryLog();
        $contacts = $user->contacts()->latestFirst()->paginate(10);
        // dd(\DB::getQueryLog());
        return view('contacts.index', compact(['contacts', 'companies']));
    }

    public function create()
    {
        $contact = new Contact();
        $companies = Company::userCompanies();
        return view('contacts.create', compact('companies', 'contact'));
    }

    public function show(Contact $contact)
    {
        return view('contacts.show', compact('contact'));
    }

    public function store(ContactRequest $request)
    {
       

        $request->user()->contacts()->create($request->all());

        return redirect()->route('contacts.index')->with('message', 'Contact has been added successfully');
    }

    public function edit(Contact $contact)
    {
        $companies = Company::userCompanies();
        return view('contacts.edit', compact('companies', 'contact'));
    }
   
    public function update(Contact $contact,ContactRequest $request )
    {
        // dd($request->company_id);
       
        $contact->update($request->all());

        return redirect()->route('contacts.index')->with('message', 'Contact has been updated successfully');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('contacts.index')->with('message', 'Contact has been deleted successfully');
    }
}

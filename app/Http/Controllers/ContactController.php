<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $companies = Company::orderBy('name')->pluck('name', 'id')->prepend('All Companies', '');
        $contacts = Contact::orderBy('id', 'desc')->where(function ($query) {
            if ($companyId = request('company_id')) {
                $query->where('company_id', $companyId);
            }

            if ($search = request('search')) {
                $query->where('first_name','LIKE',"%{$search}%");
            }
        })->paginate(10);
        return view('contacts.index', compact(['contacts', 'companies']));
    }

    public function create()
    {
        $contact = new Contact();
        $companies = Company::orderBy('name')->pluck('name', 'id')->prepend('Select Company', '');
        return view('contacts.create', compact('companies','contact'));
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

        Contact::create($request->all());

        return redirect()->route('contacts.index')->with('message','Contact has been added successfully');
    }

    public function edit(Contact $contact)
    {
        $companies = Company::orderBy('name')->pluck('name', 'id');
        return view('contacts.edit', compact('companies','contact'));
    }

    public function update(Request $request, Contact $contact)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'company_id' => 'required|exists:companies,id'
        ]);

        $contact->update($request->all());

        return redirect()->route('contacts.index')->with('message','Contact has been updated successfully');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return back()->with('message','Contact has been deleted successfully');
    }
}

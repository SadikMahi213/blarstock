<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Subscriber;

class ContactController extends Controller
{
    function subscriberIndex() {
        $pageTitle   = 'Subscribers';
        $subscribers = Subscriber::searchable(['email'])->latest()->paginate(getPaginate());

        return view('admin.page.subscriber', compact('pageTitle', 'subscribers'));
    }

    function sendEmailSubscriber() {
        $this->validate(request(), [
            'subject' => 'required',
            'body'    => 'required',
        ]);

        $subscribers = Subscriber::cursor();

        foreach ($subscribers as $subscriber) {
            $receiverName = explode('@', $subscriber->email)[0];
            $user = [
                'username' => $subscriber->email,
                'email'    => $subscriber->email,
                'fullname' => $receiverName,
            ];

            notify($user, 'DEFAULT',[
                'subject' => request('subject'),
                'message' => request('body'),
            ],['email']);
        }

        $toast[] = ['success', 'Email will be send to all subscribers'];
        return back()->withToasts($toast);
    }

    function subscriberRemove($id) {
        $subscriber = Subscriber::findOrFail($id);
        $subscriber->delete();

        $toast[] = ['success', 'Subscriber delete success'];
        return back()->withToasts($toast);
    }

    function contactIndex() {
        $pageTitle = 'Contacts';
        $contacts  = Contact::searchable(['email'])->dateFilter()->orderBy('status')->latest()->paginate(getPaginate());

        return view('admin.page.contact', compact('pageTitle', 'contacts'));
    }

    function contactRemove($id) {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        $toast[] = ['success', 'Contact delete success'];
        return back()->withToasts($toast);
    }
    
    function contactStatus($id) {
        return Contact::changeStatus($id);
    }
}

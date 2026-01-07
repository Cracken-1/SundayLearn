<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:newsletters,email'
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'Invalid email address or already subscribed.');
        }

        try {
            Newsletter::create([
                'email' => $request->email,
                'subscribed_at' => now(),
                'status' => 'active'
            ]);

            return back()->with('success', 'Thank you for subscribing to our newsletter!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to subscribe. Please try again.');
        }
    }

    public function unsubscribe(Request $request)
    {
        $email = $request->get('email');
        
        if (!$email) {
            return back()->with('error', 'Email address is required.');
        }

        $newsletter = Newsletter::where('email', $email)->first();
        
        if (!$newsletter) {
            return back()->with('error', 'Email address not found in our records.');
        }

        $newsletter->update(['status' => 'unsubscribed']);
        
        return back()->with('success', 'You have been successfully unsubscribed.');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function index()
    {
        $newsletters = Newsletter::orderBy('created_at', 'desc')->paginate(50);
        
        $stats = [
            'total' => Newsletter::count(),
            'subscribed' => Newsletter::subscribed()->count(),
            'unsubscribed' => Newsletter::where('status', 'unsubscribed')->count(),
            'bounced' => Newsletter::where('status', 'bounced')->count(),
        ];
        
        return view('admin.newsletters.index', compact('newsletters', 'stats'));
    }
    
    public function export()
    {
        $subscribers = Newsletter::subscribed()->get();
        
        $csv = "Email,Name,Subscribed At\n";
        foreach ($subscribers as $subscriber) {
            $csv .= "\"{$subscriber->email}\",\"{$subscriber->name}\",\"{$subscriber->subscribed_at}\"\n";
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="subscribers_' . date('Y-m-d') . '.csv"');
    }
    
    public function destroy(Newsletter $newsletter)
    {
        $newsletter->delete();
        
        return redirect()->route('admin.newsletters.index')
            ->with('success', 'Subscriber deleted successfully!');
    }
}

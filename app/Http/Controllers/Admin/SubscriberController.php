<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    /**
     * Display a listing of subscribers with stats.
     */
    public function index()
    {
        $subscribers = Subscriber::latest()->paginate(20);
        
        // Calculate stats
        $stats = [
            'total' => Subscriber::count(),
            'active' => Subscriber::where('is_active', true)->count(),
            'unsubscribed' => Subscriber::where('is_active', false)->count(),
        ];

        return view('admin.subscribers.index', compact('subscribers', 'stats'));
    }

    /**
     * Store a newly created subscriber.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:subscribers,email',
            'name' => 'nullable|string|max:255',
        ]);

        Subscriber::create([
            'email' => $request->email,
            'name' => $request->name,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'تم إضافة المشترك بنجاح');
    }

    /**
     * Toggle the subscriber's active status.
     */
    public function toggleStatus(Subscriber $subscriber)
    {
        $subscriber->update([
            'is_active' => !$subscriber->is_active,
        ]);

        $status = $subscriber->is_active ? 'مفعّل' : 'ملغي الاشتراك';
        
        return redirect()->back()->with('success', "تم تحديث حالة المشترك إلى: {$status}");
    }

    /**
     * Remove the specified subscriber (hard delete).
     */
    public function destroy(Subscriber $subscriber)
    {
        // Force delete to permanently remove the record
        $subscriber->forceDelete();

        return redirect()->back()->with('success', 'تم حذف المشترك نهائياً');
    }
}

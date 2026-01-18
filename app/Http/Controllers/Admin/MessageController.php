<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MessageController extends Controller
{
    /**
     * Display a listing of contact messages.
     */
    public function index(Request $request): View
    {
        $query = ContactMessage::query()->latest();

        // Filter by read status
        if ($request->has('filter')) {
            if ($request->filter === 'unread') {
                $query->unread();
            } elseif ($request->filter === 'read') {
                $query->read();
            }
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $messages = $query->paginate(15)->withQueryString();
        $unreadCount = ContactMessage::unreadCount();
        $totalCount = ContactMessage::count();

        return view('admin.messages.index', compact('messages', 'unreadCount', 'totalCount'));
    }

    /**
     * Display a single contact message.
     */
    public function show(ContactMessage $message): View
    {
        // Mark as read when viewing
        if (!$message->is_read) {
            $message->markAsRead();
        }

        return view('admin.messages.show', compact('message'));
    }

    /**
     * Toggle the read status of a message.
     */
    public function toggleRead(ContactMessage $message): RedirectResponse
    {
        if ($message->is_read) {
            $message->markAsUnread();
            $status = 'تم تحديد الرسالة كغير مقروءة';
            // Redirect to index because show() auto-marks as read
            return redirect()->route('admin.messages.index')->with('success', $status);
        } else {
            $message->markAsRead();
            $status = 'تم تحديد الرسالة كمقروءة';
            return back()->with('success', $status);
        }
    }

    /**
     * Delete a contact message.
     */
    public function destroy(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return redirect()
            ->route('admin.messages.index')
            ->with('success', 'تم حذف الرسالة بنجاح');
    }

    /**
     * Mark all messages as read.
     */
    public function markAllRead(): RedirectResponse
    {
        ContactMessage::unread()->update(['is_read' => true]);

        return back()->with('success', 'تم تحديد جميع الرسائل كمقروءة');
    }

    /**
     * Delete all read messages (cleanup).
     */
    public function deleteRead(): RedirectResponse
    {
        $count = ContactMessage::read()->delete();

        return back()->with('success', "تم حذف {$count} رسالة مقروءة");
    }
}

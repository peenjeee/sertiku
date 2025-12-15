<?php
namespace App\Http\Controllers;

use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    /**
     * Create a new support ticket (user side)
     */
    public function createTicket(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        $ticket = SupportTicket::create([
            'user_id'         => Auth::id(),
            'subject'         => $validated['subject'],
            'status'          => 'open',
            'last_message_at' => now(),
        ]);

        SupportMessage::create([
            'ticket_id'     => $ticket->id,
            'sender_id'     => Auth::id(),
            'message'       => $validated['message'],
            'is_from_admin' => false,
        ]);

        return response()->json([
            'success' => true,
            'ticket'  => $ticket->load('messages.sender'),
        ]);
    }

    /**
     * Send a message to a ticket (user or admin)
     */
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'ticket_id' => 'required|exists:support_tickets,id',
            'message'   => 'required|string|max:2000',
        ]);

        $ticket = SupportTicket::findOrFail($validated['ticket_id']);
        $user   = Auth::user();

        // Check permission: user can only send to their own tickets, admin can send to any
        if (! $user->is_admin && $ticket->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message = SupportMessage::create([
            'ticket_id'     => $ticket->id,
            'sender_id'     => $user->id,
            'message'       => $validated['message'],
            'is_from_admin' => $user->is_admin || $user->is_master,
        ]);

        // Update ticket last message time
        $ticket->update(['last_message_at' => now()]);

        // If admin replies and ticket is open, set to in_progress
        if (($user->is_admin || $user->is_master) && $ticket->status === 'open') {
            $ticket->update([
                'status'            => 'in_progress',
                'assigned_admin_id' => $user->id,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $message->load('sender'),
        ]);
    }

    /**
     * Get messages for a ticket (polling endpoint)
     */
    public function getMessages(Request $request, SupportTicket $ticket)
    {
        $user = Auth::user();

        // Check permission
        if (! $user->is_admin && ! $user->is_master && $ticket->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Mark messages as read
        $ticket->messages()
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = $ticket->messages()
            ->with('sender:id,name,avatar')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'ticket'   => $ticket,
            'messages' => $messages,
        ]);
    }

    /**
     * Get user's tickets
     */
    public function userTickets()
    {
        $tickets = SupportTicket::where('user_id', Auth::id())
            ->with(['messages' => function ($q) {
                $q->latest()->take(1);
            }])
            ->latest('last_message_at')
            ->get();

        return response()->json(['tickets' => $tickets]);
    }

    /**
     * Close a ticket
     */
    public function closeTicket(SupportTicket $ticket)
    {
        $user = Auth::user();

        if (! $user->is_admin && ! $user->is_master && $ticket->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $ticket->update(['status' => 'closed']);

        return response()->json(['success' => true]);
    }

    // ===== ADMIN/MASTER METHODS =====

    /**
     * Admin: List all tickets
     */
    public function adminIndex(Request $request)
    {
        $query = SupportTicket::with(['user:id,name,email,avatar', 'assignedAdmin:id,name'])
            ->withCount(['messages as unread_count' => function ($q) {
                $q->whereNull('read_at')
                    ->where('is_from_admin', false);
            }]);

        // Filter by status
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $tickets = $query->latest('last_message_at')->paginate(20);

        return view('admin.support.index', compact('tickets'));
    }

    /**
     * Master: List all tickets (same as admin but for master panel)
     */
    public function masterIndex(Request $request)
    {
        $query = SupportTicket::with(['user:id,name,email,avatar', 'assignedAdmin:id,name'])
            ->withCount(['messages as unread_count' => function ($q) {
                $q->whereNull('read_at')
                    ->where('is_from_admin', false);
            }]);

        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $tickets = $query->latest('last_message_at')->paginate(20);

        return view('master.support.index', compact('tickets'));
    }

    /**
     * Admin: Show ticket detail
     */
    public function adminShow(SupportTicket $ticket)
    {
        // Mark all user messages as read
        $ticket->messages()
            ->where('is_from_admin', false)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $ticket->load(['user', 'assignedAdmin', 'messages.sender']);

        return view('admin.support.show', compact('ticket'));
    }

    /**
     * Master: Show ticket detail
     */
    public function masterShow(SupportTicket $ticket)
    {
        $ticket->messages()
            ->where('is_from_admin', false)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $ticket->load(['user', 'assignedAdmin', 'messages.sender']);

        return view('master.support.show', compact('ticket'));
    }

    /**
     * Admin/Master: Reply to ticket (API)
     */
    public function adminReply(Request $request, SupportTicket $ticket)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $user = Auth::user();

        $message = SupportMessage::create([
            'ticket_id'     => $ticket->id,
            'sender_id'     => $user->id,
            'message'       => $validated['message'],
            'is_from_admin' => true,
        ]);

        $ticket->update([
            'last_message_at'   => now(),
            'status'            => $ticket->status === 'open' ? 'in_progress' : $ticket->status,
            'assigned_admin_id' => $ticket->assigned_admin_id ?? $user->id,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message->load('sender'),
            ]);
        }

        return back()->with('success', 'Pesan terkirim');
    }

    /**
     * Get unread tickets count for admin notification
     */
    public function unreadCount()
    {
        $count = SupportTicket::whereHas('messages', function ($q) {
            $q->whereNull('read_at')
                ->where('is_from_admin', false);
        })->count();

        return response()->json(['count' => $count]);
    }
}

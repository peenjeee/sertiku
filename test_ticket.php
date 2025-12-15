<?php
// Test script to create a support ticket

$user = \App\Models\User::first();

$ticket = \App\Models\SupportTicket::create([
    'user_id'         => $user->id,
    'subject'         => 'Test Ticket dari User',
    'status'          => 'open',
    'last_message_at' => now(),
]);

\App\Models\SupportMessage::create([
    'ticket_id'     => $ticket->id,
    'sender_id'     => $user->id,
    'message'       => 'Halo admin, ini pesan test dari user',
    'is_from_admin' => false,
]);

echo "Created test ticket #" . $ticket->id . "\n";

<?php
namespace App\Http\Controllers;

use App\Models\User;

use App\Models\Conversation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\MessageNotification;


class ConversationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        // Fetch conversations for the authenticated user
        $conversations = $user ? $user->conversations()
                ->with(['users', 'messages' => function ($query) {
                    $query->latest()->limit(1);
                }])
                ->latest('updated_at')
                ->get()
            : collect();

        $users = User::where('id', '!=', $user ? $user->id : 0)->get();

        // Use customer layout if user has customer role
        // For customers use Blade layout, for admin use Blade component
        $isCustomer = $user && $user->hasRole('customer');
        return view('conversations.index', [
            'conversations' => $conversations,
            'users' => $users,
            'isCustomer' => $isCustomer
        ]);
    }
// With  Route model binding Laravel automatically fetch the
// Conversation model from the database using the ID in the URL and give it to your controller method.
    public function show(Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $user = auth()->user();
        $user = $user instanceof \App\Models\User ? $user : \App\Models\User::find($user?->id);
        $messages = $conversation->messages()->with('user')->oldest()->get();

        // Mark messages as read
        $conversation->messages()
            ->where('user_id', '!=', $user ? $user->id : 0)
            ->where('read', false)
            ->update(['read' => true]);

        // Use customer layout if user has customer role
        $isCustomer = $user && $user->hasRole('customer');
        return view('conversations.show', [
            'conversation' => $conversation,
            'messages' => $messages,
            'isCustomer' => $isCustomer
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Check if conversation already exists
        $user = auth()->user();
        $user = $user instanceof \App\Models\User ? $user : \App\Models\User::find($user?->id);
        $conversation = $user
            ? $user->conversations()
                ->whereHas('users', function ($query) use ($request) {
                    $query->where('user_id', $request->user_id);
                })
                ->first()
            : null;
        // If conversation does not exist, create a new one

        if (! $conversation) {
            $conversation = Conversation::create();
            $conversation->users()->attach([Auth::id(), $request->user_id]);
        }

        return redirect()->route('conversations.show', $conversation);
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $message = $conversation->messages()->create([
            'user_id' => Auth::id(),
            'body'    => $request->body,
        ]);

        // Update conversation's updated_at
        $conversation->touch();
        // Notify other users in the conversation
        // Notify the recipient(s)
$recipient = $conversation->users()->where('users.id', '!=', Auth::id())->first();
if ($recipient) {
    $recipient->notify(new MessageNotification($message));
}

        return back()->with('success', 'Message sent!');
    }

    public function destroy(Conversation $conversation)
    {
        $this->authorize('delete', $conversation); // Optional: add policy for security
        $conversation->delete();

        return redirect()->route('conversations.index')->with('success', 'Conversation deleted.');
    }
}

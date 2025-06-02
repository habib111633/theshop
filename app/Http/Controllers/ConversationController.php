<?php
namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    public function index()
    {
        // dd(Auth::user());
        $conversations = Auth::user()->conversations()
            ->with(['users', 'messages' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->latest('updated_at')
            ->get();

        $users = User::where('id', '!=', Auth::id())->get();

        return view('conversations.index', compact('conversations', 'users'));
    }

    public function show(Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $messages = $conversation->messages()->with('user')->oldest()->get();

        // Mark messages as read
        $conversation->messages()
            ->where('user_id', '!=', Auth::id())
            ->where('read', false)
            ->update(['read' => true]);

        return view('conversations.show', compact('conversation', 'messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Check if conversation already exists
        $conversation = Auth::user()->conversations()
            ->whereHas('users', function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            })
            ->first();
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
        // $recipient = $conversation->users()->where('users.id', '!=', Auth::id())->first();
        // if ($recipient) {
        //     $recipient->notify(new MessageNotification($message));
        // }
        return back()->with('success', 'Message sent!');
    }

    public function destroy(Conversation $conversation)
    {
        $this->authorize('delete', $conversation); // Optional: add policy for security
        $conversation->delete();

        return redirect()->route('conversations.index')->with('success', 'Conversation deleted.');
    }
}

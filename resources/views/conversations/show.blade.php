<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Messages') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                <div class="p-6 bg-white border-b border-gray-100">
                    <!-- Conversation Header -->
                    <div class="flex items-center mb-6 pb-4 border-b border-gray-100">
                        <div
                            class="flex-shrink-0 h-12 w-12 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-xl mr-4">
                            @foreach ($conversation->users as $user)
                                @if ($user->id != Auth::id())
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                @endif
                            @endforeach
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                @foreach ($conversation->users as $user)
                                    @if ($user->id != Auth::id())
                                        {{ $user->name }}
                                    @endif
                                @endforeach
                            </h3>
                            <p class="text-sm text-gray-500">Active now</p>
                        </div>
                    </div>

                    <!-- Messages Container -->
                    <div class="space-y-4 mb-6 h-[400px] overflow-y-auto px-2">
                        @foreach ($messages as $message)
                            <div class="flex @if ($message->user_id == Auth::id()) justify-end @endif">
                                <div
                                    class="@if ($message->user_id == Auth::id()) bg-indigo-100 @else bg-gray-100 @endif rounded-2xl p-4 max-w-[80%]">
                                    <div class="flex items-center mb-1">
                                        @if ($message->user_id != Auth::id())
                                            <span
                                                class="font-semibold text-gray-800 mr-2">{{ $message->user->name }}</span>
                                        @endif
                                        <span
                                            class="text-xs text-gray-500">{{ $message->created_at->format('h:i A') }}</span>
                                    </div>
                                    <p class="text-gray-800">{{ $message->body }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Message Input -->
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        <form method="POST" action="{{ route('conversations.messages.store', $conversation) }}"
                            class="flex gap-2">
                            @csrf
                            <input type="text" name="body"
                                class="flex-1 rounded-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 shadow-sm"
                                placeholder="Type your message..." autofocus required>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

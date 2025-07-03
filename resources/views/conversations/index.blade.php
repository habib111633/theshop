@if (!empty($isCustomer) && $isCustomer)
    @extends('layouts.app2')
    @section('content')
@else
    <x-app-layout>
@endif


    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Left Side - New Conversation -->
                        <div class="w-full md:w-1/3">
                            <div class="bg-white rounded-lg shadow border border-gray-100">
                                <div class="p-4 border-b border-gray-200 bg-gray-50 rounded-t-lg">
                                    <h3 class="text-lg font-semibold text-gray-800">New Conversation</h3>
                                </div>
                                <div class="p-4">
                                    <form method="POST" action="{{ route('conversations.store') }}">
                                        @csrf
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Search
                                                Users</label>
                                            <div class="relative">
                                                <input type="text" id="userSearch"
                                                    class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                    placeholder="Type to search..." autocomplete="off">
                                                <div class="absolute left-3 top-2.5 text-gray-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                    </svg>
                                                </div>
                                            </div>

                                            <div id="userOptions"
                                                class="hidden mt-2 max-h-60 overflow-y-auto rounded-lg border border-gray-200 bg-white shadow-lg">
                                                @foreach ($users as $user)
                                                    <div class="user-option px-4 py-3 hover:bg-blue-50 cursor-pointer flex items-center"
                                                        data-value="{{ $user->id }}" onclick="selectUser(this)">
                                                        <div
                                                            class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold mr-3">
                                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-800">
                                                                {{ $user->name }}</div>
                                                            <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <select name="user_id" id="user_id" class="hidden" required>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit"
                                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                            Start Chat
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side - Conversation List -->
                        <div class="w-full md:w-2/3">
                            <div class="bg-white rounded-lg shadow border border-gray-100">
                                <div class="p-4 border-b border-gray-200 bg-gray-50 rounded-t-lg">
                                    <h3 class="text-lg font-semibold text-gray-800">Your Conversations</h3>
                                </div>

                                <div class="divide-y divide-gray-200">
                                    @forelse($conversations as $conversation)
                                        <div class="p-4 hover:bg-gray-50 transition duration-150">
                                            <a href="{{ route('conversations.show', $conversation) }}"
                                                class="flex items-start justify-between no-underline">
                                                <div class="flex items-start">
                                                    @foreach ($conversation->users as $user)
                                                        @if ($user->id != Auth::id())
                                                            <div
                                                                class="flex-shrink-0 h-12 w-12 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold mr-4">
                                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                    @endforeach

                                                    <div>
                                                        <div class="flex items-center">
                                                            <h4 class="text-md font-medium text-gray-800 mr-2">
                                                                @foreach ($conversation->users as $user)
                                                                    @if ($user->id != Auth::id())
                                                                        {{ $user->name }}
                                                                    @endif
                                                                @endforeach
                                                            </h4>
                                                            <span class="text-xs text-gray-500">
                                                                {{ $conversation->updated_at->diffForHumans() }}
                                                            </span>
                                                        </div>
                                                        <p class="text-sm text-gray-600 mt-1">
                                                            @if ($conversation->messages->first())
                                                                @if ($conversation->messages->first()->user_id == Auth::id())
                                                                    <span class="text-gray-500">You: </span>
                                                                @endif
                                                                {{ Str::limit($conversation->messages->first()->body, 60) }}
                                                            @else
                                                                <span class="text-gray-500 italic">No messages
                                                                    yet</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>

                                                <x-action-buttons
                                                    :delete-url="route('conversations.destroy', $conversation)"
                                                    :delete-confirm="'Delete this conversation?'"
                                                />
                                            </a>
                                        </div>
                                    @empty
                                        <div class="p-8 text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-12 w-12 mx-auto text-gray-400" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                            <h4 class="mt-3 text-lg font-medium text-gray-600">No conversations yet</h4>
                                            <p class="mt-1 text-sm text-gray-500">Start a new conversation by searching
                                                for a user</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userSearch = document.getElementById('userSearch');
            const userOptions = document.getElementById('userOptions');
            const hiddenSelect = document.getElementById('user_id');

            // Show dropdown when input is focused
            userSearch.addEventListener('focus', function() {
                userOptions.classList.remove('hidden');
                filterOptions();
            });

            // Filter options as user types
            userSearch.addEventListener('input', filterOptions);

            function filterOptions() {
                const searchValue = userSearch.value.toLowerCase();
                const options = document.querySelectorAll('.user-option');

                options.forEach(option => {
                    const userName = option.querySelector('div:nth-child(2) > div:first-child').textContent
                        .toLowerCase();
                    const userEmail = option.querySelector('div:nth-child(2) > div:last-child').textContent
                        .toLowerCase();
                    option.style.display = (userName.includes(searchValue) || userEmail.includes(
                            searchValue)) ?
                        'flex' :
                        'none';
                });
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.relative') && !e.target.closest('#userOptions')) {
                    userOptions.classList.add('hidden');
                }
            });
        });

        function selectUser(element) {
            const userId = element.getAttribute('data-value');
            const userName = element.querySelector('div:nth-child(2) > div:first-child').textContent.trim();

            // Update the visible search input
            document.getElementById('userSearch').value = userName;

            // Update the hidden select value
            document.getElementById('user_id').value = userId;

            // Hide the dropdown
            document.getElementById('userOptions').classList.add('hidden');

            // Reset dropdown scroll position
            document.getElementById('userOptions').scrollTop = 0;

            // Optionally blur the input to close the dropdown visually
            document.getElementById('userSearch').blur();

            // Trigger change event
            document.getElementById('user_id').dispatchEvent(new Event('change'));
        }
    </script>
@if (!empty($isCustomer) && $isCustomer)
    @endsection
@else
    </x-app-layout>
@endif

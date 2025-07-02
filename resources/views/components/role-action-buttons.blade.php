@props(['editUrl' => null, 'deleteUrl' => null, 'deleteConfirm' => 'Are you sure?', 'deleteName' => null])
<div class="flex justify-center items-center space-x-2">
    @if($editUrl)
    <div class="relative group">
        <a href="{{ $editUrl }}" class="text-yellow-500 hover:text-yellow-700 transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
        </a>
        <span class="absolute z-10 invisible group-hover:visible bg-gray-800 text-white text-xs rounded py-1 px-2 -bottom-6 left-1/2 transform -translate-x-1/2 whitespace-nowrap">Edit</span>
    </div>
    @endif
    @if($deleteUrl)
    <div class="relative group">
        <form action="{{ $deleteUrl }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-500 hover:text-red-700 transition-colors duration-200" onclick="return confirm('{{ $deleteConfirm }} @if($deleteName) {{ $deleteName }} @endif')">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
            <span class="absolute z-10 invisible group-hover:visible bg-gray-800 text-white text-xs rounded py-1 px-2 -bottom-7 left-1/2 transform -translate-x-1/2 whitespace-nowrap">Delete</span>
        </form>
    </div>
    @endif
</div>

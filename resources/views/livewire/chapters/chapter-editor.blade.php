<div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <div class="w-64 bg-white shadow-lg">
        <div class="p-4">
            <h2 class="text-xl font-bold text-gray-800">StoryWriter</h2>
            <p class="text-sm text-gray-600">{{ $story->title }}</p>
        </div>
        
        <!-- Chapter List -->
        <div class="px-4">
            <button wire:click="createNewChapter" 
                    class="w-full text-left px-3 py-2 text-blue-600 hover:bg-blue-50 rounded mb-4">
                + New Chapter
            </button>
        </div>
        
        <!-- Chapters List -->
        <div class="px-4">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Chapters</h3>
            @foreach($chapters as $chapter)
                <button wire:click="selectChapter({{ $chapter->id }})"
                        class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50 rounded mb-1 {{ $selectedChapter && $selectedChapter->id === $chapter->id ? 'bg-blue-50 text-blue-700' : '' }}">
                    Chapter {{ $chapter->order }}: {{ $chapter->title }}
                </button>
            @endforeach
        </div>
        
        <!-- Back to Stories -->
        <div class="px-4 mt-6">
            <a href="{{ route('stories.index') }}" 
               class="w-full text-left px-3 py-2 text-gray-600 hover:bg-gray-50 rounded">
                ← Back to Stories
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3">
                {{ session('message') }}
            </div>
        @endif

        <!-- Chapter Header -->
        <div class="bg-white border-b px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex-1">
                    <input wire:model="chapterTitle" 
                           placeholder="Chapter Title" 
                           class="text-xl font-semibold border-none focus:ring-0 w-full bg-transparent">
                    @error('chapterTitle') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <button wire:click="saveChapter" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Save Chapter
                    </button>
                </div>
            </div>
        </div>

        <!-- Chapter Content -->
        <div class="flex-1 p-6">
            <textarea wire:model="chapterContent" 
                      placeholder="Start writing your chapter..."
                      class="w-full h-full border-none resize-none focus:ring-0 text-gray-900 leading-relaxed"
                      style="min-height: 500px;"></textarea>
            @error('chapterContent') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>
</div>

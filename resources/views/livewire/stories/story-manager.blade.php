<div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <div class="w-64 bg-white shadow-lg">
        <div class="p-4">
            <h2 class="text-xl font-bold text-gray-800">StoryWriter</h2>
        </div>
        
        <!-- Story List -->
        <div class="px-4">
            <button wire:click="showCreateForm" 
                    class="w-full text-left px-3 py-2 text-blue-600 hover:bg-blue-50 rounded mb-4">
                + New Story
            </button>
        </div>
        
        <!-- Stories List -->
        <div class="px-4">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Your Stories</h3>
            @foreach($stories as $story)
                <button wire:click="selectStory({{ $story->id }})"
                        class="w-full text-left px-3 py-2 text-sm hover:bg-gray-50 rounded mb-1 {{ $selectedStory && $selectedStory->id === $story->id ? 'bg-blue-50 text-blue-700' : '' }}">
                    {{ $story->title }}
                    <span class="text-xs text-gray-500">({{ $story->chapters->count() }} chapters)</span>
                </button>
            @endforeach
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
        @if($showCreateForm)
            <!-- Story Creation Form -->
            <div class="flex-1 p-6 overflow-y-auto">
                <div class="max-w-4xl mx-auto">
                    <h1 class="text-2xl font-bold text-gray-900 mb-6">Create New Story</h1>
                    
                    @if (session()->has('message'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('message') }}
                        </div>
                    @endif

                    <form wire:submit="createStory">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Story Title</label>
                                <input wire:model="title" type="text" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Target Chapters</label>
                                <input wire:model="targetChapters" type="number" min="1" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('targetChapters') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Outline</label>
                            <textarea wire:model="outline" rows="4" 
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="Describe your story idea, main themes, and overall direction..."></textarea>
                            @error('outline') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Characters</label>
                            <textarea wire:model="characters" rows="3" 
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="Describe your main characters, their backgrounds, and relationships..."></textarea>
                            @error('characters') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Existing Story (if any)</label>
                            <textarea wire:model="existingStory" rows="4" 
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="If you have existing story content, paste it here..."></textarea>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">End Goal</label>
                            <textarea wire:model="endGoal" rows="3" 
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="What do you want to achieve with this story? What's the end goal?"></textarea>
                            @error('endGoal') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="mt-6">
                            <button type="button" wire:click="generatePlot" 
                                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 mr-4">
                                Create Plot
                            </button>
                        </div>

                        @if($generatedPlot)
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Generated Plot</label>
                                <textarea readonly rows="8" 
                                          class="w-full rounded-md border-gray-300 bg-gray-50 shadow-sm"
                                          placeholder="Generated plot will appear here...">{{ $generatedPlot }}</textarea>
                            </div>
                        @endif

                        <div class="mt-6 flex gap-4">
                            <button type="submit" 
                                    class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700">
                                Create Story
                            </button>
                            <button type="button" wire:click="cancelCreate" 
                                    class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @elseif($selectedStory)
            <!-- Story Details -->
            <div class="flex-1 p-6">
                <div class="max-w-4xl mx-auto">
                    <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $selectedStory->title }}</h1>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h3 class="font-semibold text-gray-900 mb-2">Outline</h3>
                            <p class="text-gray-700">{{ $selectedStory->outline }}</p>
                        </div>
                        
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h3 class="font-semibold text-gray-900 mb-2">Characters</h3>
                            <p class="text-gray-700">{{ $selectedStory->characters }}</p>
                        </div>
                    </div>
                    
                    @if($selectedStory->generated_plot)
                        <div class="bg-white p-4 rounded-lg shadow mb-6">
                            <h3 class="font-semibold text-gray-900 mb-2">Generated Plot</h3>
                            <p class="text-gray-700 whitespace-pre-line">{{ $selectedStory->generated_plot }}</p>
                        </div>
                    @endif
                    
                    <div class="flex gap-4">
                        <a href="{{ route('chapters.index', $selectedStory) }}" 
                           class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                            Start Writing Chapters
                        </a>
                        <button wire:click="showCreateForm" 
                                class="bg-gray-500 text-white px-6 py-2 rounded-md hover:bg-gray-600">
                            Create Another Story
                        </button>
                    </div>
                </div>
            </div>
        @else
            <!-- Welcome Screen -->
            <div class="flex-1 flex items-center justify-center">
                <div class="text-center">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Welcome to StoryWriter</h1>
                    <p class="text-gray-600 mb-8">Create and manage your long-form stories with AI assistance</p>
                    <button wire:click="showCreateForm" 
                            class="bg-blue-600 text-white px-8 py-3 rounded-md hover:bg-blue-700 text-lg">
                        Create Your First Story
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

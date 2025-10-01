<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col items-center justify-center">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Welcome to StoryWriter</h1>
            <p class="text-gray-600 mb-8 text-lg">Create and manage your long-form stories with AI assistance</p>
            <a href="{{ route('stories.index') }}" 
               class="bg-blue-600 text-white px-8 py-3 rounded-md hover:bg-blue-700 text-lg inline-block">
                Start Writing Stories
            </a>
        </div>
    </div>
</x-layouts.app>

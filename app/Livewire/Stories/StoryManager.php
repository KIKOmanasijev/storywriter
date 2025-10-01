<?php

namespace App\Livewire\Stories;

use App\Models\Story;
use App\Services\AIService;
use Livewire\Component;

class StoryManager extends Component
{
    public $stories;
    public $selectedStory;
    public $showCreateForm = false;
    
    // Story creation fields
    public $title = '';
    public $outline = '';
    public $targetChapters = 1;
    public $characters = '';
    public $existingStory = '';
    public $endGoal = '';
    public $generatedPlot = '';

    public function mount()
    {
        $this->stories = auth()->user()->stories()->with('chapters')->get();
    }

    public function createStory()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'outline' => 'required|string',
            'targetChapters' => 'required|integer|min:1',
            'characters' => 'required|string',
            'endGoal' => 'required|string'
        ]);

        $story = auth()->user()->stories()->create([
            'title' => $this->title,
            'outline' => $this->outline,
            'target_chapters' => $this->targetChapters,
            'characters' => $this->characters,
            'existing_story' => $this->existingStory,
            'end_goal' => $this->endGoal,
            'generated_plot' => $this->generatedPlot
        ]);

        $this->resetForm();
        $this->stories = auth()->user()->stories()->with('chapters')->get();
        $this->showCreateForm = false;
        
        session()->flash('message', 'Story created successfully!');
    }

    public function generatePlot()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'outline' => 'required|string',
            'characters' => 'required|string',
            'endGoal' => 'required|string'
        ]);

        $storyData = [
            'title' => $this->title,
            'outline' => $this->outline,
            'characters' => $this->characters,
            'existing_story' => $this->existingStory,
            'end_goal' => $this->endGoal,
            'target_chapters' => $this->targetChapters
        ];

        $this->generatedPlot = app(AIService::class)->generatePlot($storyData);
    }

    public function selectStory($storyId)
    {
        $this->selectedStory = Story::with('chapters')->find($storyId);
    }

    public function showCreateForm()
    {
        $this->showCreateForm = true;
        $this->resetForm();
    }

    public function cancelCreate()
    {
        $this->showCreateForm = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->title = '';
        $this->outline = '';
        $this->targetChapters = 1;
        $this->characters = '';
        $this->existingStory = '';
        $this->endGoal = '';
        $this->generatedPlot = '';
    }

    public function render()
    {
        return view('livewire.stories.story-manager');
    }
}

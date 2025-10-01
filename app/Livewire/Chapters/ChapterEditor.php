<?php

namespace App\Livewire\Chapters;

use App\Models\Chapter;
use App\Models\Story;
use App\Services\AIService;
use Livewire\Component;

class ChapterEditor extends Component
{
    public $story;
    public $chapters;
    public $selectedChapter;
    public $chapterContent = '';
    public $chapterTitle = '';
    public $isNewChapter = false;

    public function mount(Story $story)
    {
        $this->story = $story;
        $this->chapters = $story->chapters;
    }

    public function selectChapter($chapterId)
    {
        $this->selectedChapter = Chapter::find($chapterId);
        $this->chapterContent = $this->selectedChapter->content;
        $this->chapterTitle = $this->selectedChapter->title;
        $this->isNewChapter = false;
    }

    public function createNewChapter()
    {
        $this->selectedChapter = null;
        $this->chapterContent = '';
        $this->chapterTitle = '';
        $this->isNewChapter = true;
    }

    public function saveChapter()
    {
        $this->validate([
            'chapterTitle' => 'required|string|max:255',
            'chapterContent' => 'required|string'
        ]);

        if ($this->isNewChapter) {
            $chapter = $this->story->chapters()->create([
                'title' => $this->chapterTitle,
                'content' => $this->chapterContent,
                'order' => $this->story->chapters()->count() + 1
            ]);
        } else {
            $this->selectedChapter->update([
                'title' => $this->chapterTitle,
                'content' => $this->chapterContent
            ]);
            $chapter = $this->selectedChapter;
        }

        // Auto-summarize chapter
        $this->summarizeChapter($chapter);
        
        $this->chapters = $this->story->fresh()->chapters;
        $this->isNewChapter = false;
        
        session()->flash('message', 'Chapter saved successfully!');
    }

    private function summarizeChapter(Chapter $chapter)
    {
        if (!$chapter->summary) {
            $summaryData = app(AIService::class)->summarizeChapter($chapter->content);
            
            $chapter->summary()->create([
                'summary' => $summaryData['summary'],
                'key_points' => $summaryData['key_points']
            ]);
        }
    }

    public function render()
    {
        return view('livewire.chapters.chapter-editor');
    }
}

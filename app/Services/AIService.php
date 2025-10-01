<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class AIService
{
    private Client $client;
    private string $apiKey;
    private string $baseUrl = 'https://api-inference.huggingface.co/models/';

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.huggingface.api_key');
    }

    public function generatePlot(array $storyData): string
    {
        $prompt = $this->buildPlotPrompt($storyData);
        
        try {
            $response = $this->callModel($prompt, 'microsoft/DialoGPT-medium');
            return $response;
        } catch (\Exception $e) {
            Log::error('Failed to generate plot: ' . $e->getMessage());
            return 'Unable to generate plot at this time. Please try again later.';
        }
    }

    public function summarizeChapter(string $content): array
    {
        try {
            $summary = $this->callModel($content, 'facebook/bart-large-cnn');
            
            return [
                'summary' => $summary,
                'key_points' => $this->extractKeyPoints($content)
            ];
        } catch (\Exception $e) {
            Log::error('Failed to summarize chapter: ' . $e->getMessage());
            return [
                'summary' => 'Unable to generate summary at this time.',
                'key_points' => []
            ];
        }
    }

    private function buildPlotPrompt(array $storyData): string
    {
        $prompt = "Create a detailed plot for a story with the following details:\n\n";
        $prompt .= "Title: " . $storyData['title'] . "\n";
        $prompt .= "Outline: " . $storyData['outline'] . "\n";
        $prompt .= "Characters: " . $storyData['characters'] . "\n";
        $prompt .= "End Goal: " . $storyData['end_goal'] . "\n";
        $prompt .= "Target Chapters: " . $storyData['target_chapters'] . "\n\n";
        
        if (!empty($storyData['existing_story'])) {
            $prompt .= "Existing Story Context: " . $storyData['existing_story'] . "\n\n";
        }
        
        $prompt .= "Please create a detailed plot that includes:\n";
        $prompt .= "1. Main story arc\n";
        $prompt .= "2. Character development\n";
        $prompt .= "3. Key plot points for each chapter\n";
        $prompt .= "4. Conflict and resolution\n";
        $prompt .= "5. How the story reaches the end goal\n\n";
        $prompt .= "Format the plot as a structured narrative that can guide chapter writing.";

        return $prompt;
    }

    private function callModel(string $input, string $model): string
    {
        try {
            $response = $this->client->post($this->baseUrl . $model, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'inputs' => $input,
                    'parameters' => [
                        'max_length' => 1000,
                        'temperature' => 0.7,
                        'do_sample' => true
                    ]
                ],
                'timeout' => 30
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            
            if (isset($data[0]['generated_text'])) {
                return $data[0]['generated_text'];
            }
            
            if (isset($data[0]['summary_text'])) {
                return $data[0]['summary_text'];
            }
            
            return 'Unable to process the request.';
            
        } catch (GuzzleException $e) {
            Log::error('Hugging Face API error: ' . $e->getMessage());
            throw new \Exception('AI service temporarily unavailable');
        }
    }

    private function extractKeyPoints(string $content): array
    {
        // Simple key point extraction - can be enhanced with more sophisticated NLP
        $sentences = preg_split('/[.!?]+/', $content);
        $keyPoints = [];
        
        foreach ($sentences as $sentence) {
            $sentence = trim($sentence);
            if (strlen($sentence) > 20 && strlen($sentence) < 200) {
                $keyPoints[] = $sentence;
                if (count($keyPoints) >= 5) break; // Limit to 5 key points
            }
        }
        
        return $keyPoints;
    }
}

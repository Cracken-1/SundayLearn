<?php

namespace App\Services;

class TelegramContentParser
{
    public function parseCaption(string $caption): array
    {
        $parsed = [
            'topic' => null,
            'age_group' => null,
            'content_type' => null,
            'title' => null,
            'description' => null,
        ];

        // Parse structured caption format
        $lines = explode("\n", $caption);
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            if (preg_match('/^Topic:\s*(.+)$/i', $line, $matches)) {
                $parsed['topic'] = trim($matches[1]);
            } elseif (preg_match('/^Age:\s*(.+)$/i', $line, $matches)) {
                $parsed['age_group'] = trim($matches[1]);
            } elseif (preg_match('/^Type:\s*(.+)$/i', $line, $matches)) {
                $parsed['content_type'] = trim($matches[1]);
            } elseif (preg_match('/^Title:\s*(.+)$/i', $line, $matches)) {
                $parsed['title'] = trim($matches[1]);
            } elseif (preg_match('/^Description:\s*(.+)$/i', $line, $matches)) {
                $parsed['description'] = trim($matches[1]);
            }
        }

        return $parsed;
    }

    public function isValidStructuredContent(array $parsed): bool
    {
        return !empty($parsed['topic']) && 
               !empty($parsed['age_group']) && 
               !empty($parsed['title']);
    }

    public function extractFreeformContent(string $caption): array
    {
        // For unstructured captions, try to extract meaningful info
        return [
            'title' => $this->extractTitle($caption),
            'description' => $caption,
            'suggested_topic' => $this->suggestTopic($caption),
        ];
    }

    private function extractTitle(string $text): string
    {
        // Take first line or first 50 characters as title
        $lines = explode("\n", $text);
        $firstLine = trim($lines[0]);
        
        return strlen($firstLine) > 50 ? substr($firstLine, 0, 47) . '...' : $firstLine;
    }

    private function suggestTopic(string $text): ?string
    {
        $topics = [
            'Bible Stories' => ['david', 'goliath', 'noah', 'moses', 'jesus', 'daniel'],
            'Prayer' => ['pray', 'prayer', 'worship', 'praise'],
            'Songs' => ['song', 'sing', 'music', 'hymn'],
            'Crafts' => ['craft', 'make', 'create', 'draw', 'color'],
            'Games' => ['game', 'play', 'activity', 'fun'],
        ];

        $text = strtolower($text);
        
        foreach ($topics as $topic => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($text, $keyword) !== false) {
                    return $topic;
                }
            }
        }

        return null;
    }
}
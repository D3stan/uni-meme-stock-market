<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GeminiService
{
    protected ?string $apiKey;
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
        $this->apiUrl = config('services.gemini.url');
    }

    /**
     * Check if Gemini API is configured.
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Analyze a meme image for alt text and content moderation.
     *
     * @param string $imagePath Path relative to storage/app/public/
     * @return array|null Returns analysis result or null on failure
     */
    public function analyzeMeme(string $imagePath): ?array
    {
        if (!$this->isConfigured()) {
            Log::info('Gemini API not configured, skipping meme analysis');
            return null;
        }

        // Get full path to image
        $fullPath = Storage::disk('public')->path($imagePath);     
        if (!file_exists($fullPath)) {
            Log::error('Meme image not found for AI analysis', ['path' => $fullPath]);
            return null;
        }

        $mimeType = mime_content_type($fullPath);
        $imageData = base64_encode(file_get_contents($fullPath));

        $prompt = "
            Analizza questa immagine (un meme) agendo come un esperto di accessibilità e moderazione contenuti.
            Esegui i seguenti compiti:

            Trascrizione: Estrai fedelmente tutto il testo presente nell'immagine.
            Descrizione Visiva: Descrivi gli elementi grafici, i personaggi, le loro espressioni e l'ambientazione.
            Alt-Text Semantico: Crea una descrizione breve ed efficace per utenti non vedenti che spieghi il senso del meme (l'unione di immagine e testo).
            Moderazione: Valuta se il contenuto è appropriato per un pubblico generico. Segnala come 'unsafe' contenuti che incitano all'odio, violenza, bullismo, nudità o discriminazione.
            
            Restituisci il risultato esclusivamente in formato JSON con questa struttura: 
            { 
                \"testo_estratto\": \"...\", 
                \"alt_text\": \"...\", 
                \"is_appropriate\": boolean, 
                \"motivation\": \"none/hate/violence/sexual/other\" 
            }";

        try {
            $url = $this->apiUrl . "?key=" . $this->apiKey;

            $response = Http::withoutVerifying()
                ->timeout(30)
                ->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                                [
                                    'inline_data' => [
                                        'mime_type' => $mimeType,
                                        'data' => $imageData
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'response_mime_type' => 'application/json',
                        'temperature' => 0.2
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $jsonText = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
                
                if ($jsonText) {
                    $result = json_decode($jsonText, true);
                    Log::info('Gemini meme analysis completed', ['result' => $result]);
                    return $result;
                }
            }

            Log::error('Gemini API call failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return null;

        } catch (Exception $e) {
            Log::error('Gemini API exception', [
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }
}

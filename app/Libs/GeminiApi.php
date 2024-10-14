<?php

namespace App\Libs;

use Gemini\Laravel\Facades\Gemini;

class GeminiApi
{
    /**
     * Gemini API 呼び出し
     */
    public function callGeminiApi(string $prompt): string
    {
        // プロンプトをGemini APIに渡す
        $result = Gemini::geminiPro()->generateContent($prompt);

        // レスポンスからテキストを取得して返す
        return $result->text();
    }
}
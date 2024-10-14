<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Libs\GeminiApi; // Gemini APIのインポート
use GuzzleHttp\Client;
use Google\Cloud\Speech\V1\RecognitionConfig\AudioEncoding;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\RecognitionAudio;
use Google\Cloud\Speech\V1\SpeechClient;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // Gemini APIとVOICEVOXを組み合わせたメソッド
    public function callGeminiApi(string $prompt)
    {
        // 1. Gemini APIを呼び出してテキスト応答を生成
        $geminiApi = new GeminiApi();
        $response = $geminiApi->callGeminiApi($prompt);  // Gemini APIからの応答テキスト

        // すでに$responseがテキストの場合、そのまま利用
        $text = $response;  // 生成された応答テキスト

        // 2. VOICEVOXのaudio_queryエンドポイントにクエリを送信
        $base_url = config('google.voicevox_url');
       
        $client = new Client([
            'base_uri' => $base_url,
        ]);

        $audio_query_path = '/audio_query';
        $audio_query_response = $client->request('POST', $audio_query_path, [
            'query' => [
                'text' => $text . "に対して会話みたいに返してください.100文字以内.",
                'speaker' => '47'
            ]
        ]);

        $audio_query_response = json_decode($audio_query_response->getBody());

        $synthesis_path = '/synthesis';
                $synthesis_response = $client->request('POST', $synthesis_path, [
                    'query' => ['speaker' => '0'],
                    'json' => $audio_query_response,
                    'headers' => ['Accept' => 'audio/wav'],
                ]);
                
                $voice_data = $synthesis_response->getBody()->getContents();
                $voice_base_64 = base64_encode($voice_data);
                return $voice_base_64;
    }

    
    public function home(Request $request)
    {
        $audio_file = $request->file('audio'); // 'audio'はフォームフィールド名
        $text = 'こんにちは';
        if ($audio_file) {
            // 音声ファイルの内容を直接取得する
            $text = $this->speechToText($audio_file);
        }
        $voiceData = $this->callGeminiApi($text);
        return view('home')->with('voiceData', $voiceData);
    }

    public function speechToText($audio_file)
    {  
        $credential = config('google.google_credential');
        $config = new RecognitionConfig();
        $config->setEncoding(AudioEncoding::WEBM_OPUS);
        $config->setSampleRateHertz(48000);
        $config->setLanguageCode('ja-JP');
        // 音声ファイルの内容を取得する
        $audio_content = file_get_contents($audio_file->getRealPath());
        $audio = (new RecognitionAudio())
            ->setContent($audio_content); 
        $speechClient = new SpeechClient([
            'credentials' => $credential
        ]);
        $response = $speechClient->recognize($config, $audio);
        // 音声認識の結果を処理
        $transcript = '';
        foreach ($response->getResults() as $result) {
            $transcript .= $result->getAlternatives()[0]->getTranscript() . "\n";
        }
        // クライアントを閉じる
        $speechClient->close();
        return $transcript;
    }
}

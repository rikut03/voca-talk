<?php
return
[
    // 'google_credential' => env('GOOGLE_APPLICATION_CREDENTIAL'),
    'voicevox_url' => env('VOICEVOX_URL'),
    'google_credential' => json_decode(env('GOOGLE_APPLICATION_CREDENTIAL'), true),
];
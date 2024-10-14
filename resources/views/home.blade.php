<!DOCTYPE html>
<html>
    <head>
         <meta charset="UTF-8">
    </head>
    <body>
        <h1>音声</h1>
        <button id="startRecording">録音開始</button>
        <button id="stopRecording" disabled>録音停止</button>
        <div id="status"></div>
        <form id="audioForm" action="/" method="POST" enctype="multipart/form-data" style="display:none;">
            @csrf
            <input type="file" name="audio" id="audioInput">
            <input type="submit" value="送信">
        </form>
        <audio id="audioPlayer" controls></audio>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script>
            // 既存のコード
            const voiceBase64 = @json($voiceData);
            const binaryString = atob(voiceBase64);
            const len = binaryString.length;
            const bytes = new Uint8Array(len);
            for (let i = 0; i < len; i++) {
                bytes[i] = binaryString.charCodeAt(i);
            }
            const blob = new Blob([bytes], {type: 'audio/wav'});
            const url = URL.createObjectURL(blob);
            document.getElementById('audioPlayer').src = url;
            window.addEventListener('load', function() {
                audioPlayer.play();
            });
            let mediaRecorder;
        let audioChunks = [];
        document.getElementById('startRecording').addEventListener('click', async () => {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                mediaRecorder = new MediaRecorder(stream);
                mediaRecorder.start();
                document.getElementById('startRecording').disabled = true;
                document.getElementById('stopRecording').disabled = false;
                document.getElementById('status').textContent = '録音中...';
                mediaRecorder.addEventListener('dataavailable', event => {
                    audioChunks.push(event.data);
                });
                mediaRecorder.addEventListener('stop', () => {
                    const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                    sendAudioToServer(audioBlob);
                });
            } catch (error) {
                console.error('Error accessing microphone:', error);
                document.getElementById('status').textContent = 'マイクへのアクセスエラー';
            }
        });
        document.getElementById('stopRecording').addEventListener('click', () => {
            mediaRecorder.stop();
            document.getElementById('startRecording').disabled = false;
            document.getElementById('stopRecording').disabled = true;
            document.getElementById('status').textContent = '音声を処理中...';
        });
        function sendAudioToServer(audioBlob) {
            const audioFile = new File([audioBlob], 'recording.wav', { type: 'audio/wav' });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(audioFile);
            const audioInput = document.getElementById('audioInput');
            audioInput.files = dataTransfer.files;
            const form = document.getElementById('audioForm');
            form.submit();
        }
    </script>
    </body>
</html>
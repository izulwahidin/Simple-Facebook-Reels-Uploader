<?php
require(__DIR__.'/vendor/autoload.php');

// init Reels API
$fb_reels = new Wahidin\Fb\Reels(
    'EAATnSDqIOLMBAOxxxxx' //your facebook page token
);

try {
    // try to upload
    echo "Uploading ".basename($config->video_file).PHP_EOL;
    $reel = $fb_reels->upload(
        'local.mp4',
        'my video description'
    );
    echo "$reel\n\n"; //success
} catch (\Throwable $th) {
    echo "{$th->getMessage()}\n--- Failed to upload ---\n\n"; //failed
}
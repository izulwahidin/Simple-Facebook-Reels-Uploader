<?php
require(__DIR__.'/vendor/autoload.php');

// init Reels API
$fb_reels = new Wahidin\Facebook\Reels(
    'EAATnSDqIOLMBAOxxxxx' //your facebook page token
);

try {
    // try to upload
    echo "Uploading local.mp4\n";
    $reel = $fb_reels->upload(
        'local.mp4',
        'my video description'
    );
    echo "$reel\n\n"; //success
} catch (\Throwable $th) {
    echo "{$th->getMessage()}\n--- Failed to upload ---\n\n"; //failed
}

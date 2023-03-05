<?php namespace Wahidin\Facebook;
use Wahidin\Main;

#[AllowDynamicProperties]
class Reels extends Main{
    private static $access_token;
    private $video_id, $video_file, $video_description;
    private $fb_base = 'https://graph.facebook.com/v15.0/';

    public function __construct($token){
        self::$access_token = $token;
    }

    public function upload($video_file,$video_description){
	if(!is_file($this->video_file)) throw new \Exception("video file not found", 1);
	       
        $this->video_file = $video_file;
        $this->video_description = $video_description;
        $this->video_size = filesize($this->video_file);

        $init = self::init(); // calling init method


        $maxRetry = 5;
        retri:
        $this->endpoint = $init->upload_url;
        $this->method = 'post';
        $this->head = [
            'Authorization: OAuth '. self::$access_token,
            'Content-Length: '.$this->video_size,
            'Content-Type: application/octet-stream',
            'Offset: 0',
            'File_size: '.$this->video_size,
        ];
        $this->post = file_get_contents($this->video_file);

        $result = json_decode(self::fetch());

        if(isset($result->debug_info) && $maxRetry > 0){
            echo " $maxRetry";
            $maxRetry -= 1;
            goto retri;
        }elseif(isset($result->debug_info) && $maxRetry < 1){
            throw new \Exception("Upload Error", 1);
        }
        
        // calling publish method
        $publish = self::publish();

        return $publish;
    }

    private function init(){
        $this->endpoint =  $this->fb_base."me/video_reels";
        $this->method = 'post';
        $this->post = [
            'upload_phase' => 'start',
            'access_token' => self::$access_token
        ];
        
        $result = json_decode(self::fetch());
        
        if(!isset($result->video_id)){
            throw new \Exception("Init Error", 1);    
        }

        $this->video_id = $result->video_id;
        return $result;
    }


    private function publish(){
        $this->endpoint = $this->fb_base."me/video_reels";
        $this->method = 'post';
        $this->post = [
            'access_token' => self::$access_token,
            'video_id' => $this->video_id,
            'upload_phase' => 'finish',
            'video_state' => 'PUBLISHED',
            'description' => $this->video_description,
        ];

        $result = json_decode(self::fetch());

        if(isset($result->errors)){
            throw new \Exception("Publish Error: {$result->errors[0]->message}", 1);
        }
        return "Sukses -> https://fb.com/{$this->video_id}";
    }
}

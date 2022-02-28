<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Audio Thumbnail Url
    |--------------------------------------------------------------------------
    |
    | This value is the url of default audio thumbnail (=S3 file url)
    */
    'url' =>"https://".config('app.aws_bucket').".s3.".config('app.aws_default_region').".amazonaws.com/public/img/audio_thumbnail/default/8%E5%88%86%E9%9F%B3%E7%AC%A6%E3%81%AE%E3%82%A2%E3%82%A4%E3%82%B3%E3%83%B3%E7%B4%A0%E6%9D%90+2.png",

];

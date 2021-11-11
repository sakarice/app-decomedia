<?php

namespace App\Lib\Common;

class Test
{
    // 拡張子を除いたファイル名を取得する
    public static function logTest(){
      \Log::info('class名:'.str_replace(__NAMESPACE__.'\\', '', get_class()));
      \Log::info('関数名:'.__FUNCTION__);
    }


}
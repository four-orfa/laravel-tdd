<?php

namespace App;

use Facades\Illuminate\Support\Str;

class StrRandom
{
    public function random(int $len)
    {
        return Str::random($len);
    }
}

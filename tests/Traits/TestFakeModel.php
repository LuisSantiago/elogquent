<?php

namespace Elogquent\Tests\Traits;

use Elogquent\Traits\Elogquent;
use Illuminate\Database\Eloquent\Model;

class TestFakeModel extends Model
{
    use Elogquent;

    protected $guarded = [];
}

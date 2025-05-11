<?php

namespace Elogquent\Tests\Models;

use Elogquent\Traits\Elogquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestFakeModel extends Model
{
    use Elogquent;
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    protected $table = 'fake_models';

    protected array $dates = ['created_at'];
}

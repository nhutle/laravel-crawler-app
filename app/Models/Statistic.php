<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statistic extends Model
{
    public $fillable = ['keyword', 'total_adwords', 'total_links', 'total_search_results', 'html_code'];
}

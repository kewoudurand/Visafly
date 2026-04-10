<?php

namespace App\Http\Controllers\Goethe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Langue;
use App\Models\Course;
use App\Models\LangueSerie;

class GoetheController extends Controller
{
    public function index()
    {
        return view('goethe.index');
    }

    public function chooseType()
    {
        return view('goethe.choose-type');
    }

    public function chooseLevel($type)
    {
        $levels = ['A1','A2','B1','B2','C1'];

        return view('goethe.choose-level', compact('type','levels'));
    }
}

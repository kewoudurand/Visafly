<?php

namespace App\Http\Controllers\Goethe;

use App\Http\Controllers\Controller;
use App\Models\Langue;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index($level)
    {
        $allemand = Langue::where('nom','Allemand')->first();

        $courses = Course::where('langue_id', $allemand->id)
            ->where('niveau', $level)
            ->get();

        return view('goethe.courses.index', compact('courses','level'));
    }

    public function show(Course $course)
    {
        $lessons = $course->lessons()->orderBy('ordre')->get();

        return view('goethe.courses.show', compact('course','lessons'));
    }
}

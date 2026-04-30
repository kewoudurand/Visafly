<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\GereParInstructeur;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseInstructorController extends Controller
{
    use GereParInstructeur;

    public function index()
    {
        // ✅ Filtre direct — pas de scope pouvant être ignoré
        $cours = Course::where('instructor_id', auth()->id())
            ->withCount('lecons')
            ->orderBy('ordre')
            ->paginate(20);

        return view('instructor.courses.index', compact('cours'));
    }

    public function create()
    {
        return view('instructor.courses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->reglesCours());

        $data = $this->construireDataCours($request, $validated, auth()->id());

        // ✅ Double sécurité : forcer instructeur_id = utilisateur connecté
        $data['instructor_id'] = auth()->id();

        Course::create($data);

        return redirect()->route('instructor.cours.index')
            ->with('success', 'Cours créé avec succès.');
    }

    public function edit(Course $cours)
    {
        // ✅ Comparaison stricte en int
        abort_unless((int) $cours->instructor_id === (int) auth()->id(), 403);

        return view('instructor.courses.edit', compact('cours'));
    }

    public function update(Request $request, Course $cours)
    {
        abort_unless((int) $cours->instructor_id === (int) auth()->id(), 403);

        $validated = $request->validate($this->reglesCours());
        $data = $this->construireDataCours($request, $validated, auth()->id(), $cours);

        // ✅ instructeur_id immuable côté instructeur
        $data['instructor_id'] = auth()->id();

        $cours->update($data);

        return redirect()->route('instructor.cours.index')
            ->with('success', 'Cours mis à jour.');
    }

    public function destroy(Course $cours)
    {
        abort_unless((int) $cours->instructor_id === (int) auth()->id(), 403);
        $cours->delete();

        return redirect()->route('instructor.cours.index')
            ->with('success', 'Cours supprimé.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\User;
use PDF;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{

    public function dashboard()
    {
        $consultations = Consultation::latest()->get();
        return view('admin.dashboard', compact('consultations'));
    }

    public function user()
    {
        $users = User::get();
        return View('admin.userAdd',compact('users'));
    }

    public function deleteUser($id)
    {
        // Récupérer l'utilisateur
        $user = User::findOrFail($id);

        // Supprimer l'utilisateur
        $user->delete();

        // Rediriger avec message de succès
        return redirect()->route('admin.userAdd')->with('success', 'Utilisateur supprimé avec succès.');
    }

    public function finish($id)
    {
        $consult = Consultation::findOrFail($id);
        $consult->status = 1;
        $consult->save();

        return back()->with('success', 'Consultation marquée comme terminée.');
    }


    public function showConsultation($id)
    {
        $consultation = Consultation::findOrFail($id);
        return view('admin.seeUser', compact('consultation'));
    }

    public function deleteConsultation($id)
    {
        Consultation::findOrFail($id)->delete();

        return back()->with('success', 'Consultation supprimée.');
    }

    public function consultationPdf($id)
    {
        $consultation = Consultation::findOrFail($id);

        $pdf = PDF::loadView('admin.pdf', compact('consultation'))->setPaper('a4', 'portrait');

        return $pdf->download('Consultation_'.$consultation->full_name.'.pdf');
    }

}

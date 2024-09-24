<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Affichage des Examens
        $exams = \App\Models\Exam::all();
        return view('exams.index', compact('exams'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Creation d'une instance d'examen
        $exam = new \App\Models\Exam();
        $exam->service_id = $request->service_id;
        $exam->name = $request->name;
        $exam->description = $request->description;
        $exam->price = $request->price;
        $exam->save();

        return redirect()->route('exams.index')->with('success', 'L\'examen a bien été créé');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //Affichage des detail d'un examen
        $exam = Exam::find($id);
        return view('exams.show', compact('exam'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //;odifier un examen
        $exam = Exam::find($id);
        $exam->name = $request->name;
        $exam->description = $request->description;
        $exam->price = $request->price;
        $exam->save();

        return redirect()->route('exams.index')->with('success', 'L\'examen a bien été modifié');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //Supprimer un examen
        $exam = Exam::find($id);
        $exam->delete();

        return redirect()->route('exams.index')->with('success', 'L\'examen a bien été supprimé');
    }
}

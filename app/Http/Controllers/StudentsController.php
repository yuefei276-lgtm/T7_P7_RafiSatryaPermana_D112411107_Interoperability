<?php

namespace App\Http\Controllers;

use App\Models\Student;

class StudentsController extends Controller
{
    public function index()
    {
        $students = Student::orderBy('gpa', 'DESC')->get();

        return response()->json([
            "message" => "Daftar mahasiswa (Students)",
            "total" => $students->count(),
            "results" => $students
        ], 200);
    }
}

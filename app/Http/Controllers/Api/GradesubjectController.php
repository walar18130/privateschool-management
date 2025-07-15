<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gradesubject;

class GradesubjectController extends Controller
{
    // GET /gradesubjects
    public function index()
    {
        return response()->json([
            'message' => 'List of grade classes',
            'data' => Gradesubject::with(['Grade', 'academicyear'])->get()
        ]);        
        
    }

    // POST /gradesubjects
    public function store(Request $request)
    {
        $validated = $request->validate([
            'grade_id' => 'required|exists:grades,id',
            'academicyear_id' => 'required|exists:academicyears,id', // ✅ include this
            'name' => 'required|string|max:255',
            'code',
        ]);

        $subject = Gradesubject::create($validated);
        // $subject->load(['grade', 'academicYear']);

        return response()->json([
            'message' => 'Created successfully',
        ], 201);
    }

    // GET /gradesubjects/{id}
    public function show($id)
    {
        $subject = Gradesubject::with(['grade', 'academicyear'])->findOrFail($id);

        return response()->json([
            'id' => $subject->id,
            'name' => $subject->name,
            'code' => $subject->code,
            'grade' => $subject->grade->name ?? null,
            'academic_year' => $subject->academicYear->name ?? null,
        ]);
    }

    // PUT /gradesubjects/{id}
    public function update(Request $request, $id)
    {
        $subject = Gradesubject::findOrFail($id);

        $validated = $request->validate([
            'grade_id' => 'sometimes|exists:grades,id',
            'academicyear_id' => 'sometimes|exists:academicyears,id',
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:50',
        ]);

        $subject->update($validated);
        $subject->load(['grade', 'academicyear']);

        return response()->json([
            'message' => 'Updated successfully',
            'data' => [
                'id' => $subject->id,
                'name' => $subject->name,
                'code' => $subject->code,
                'grade' => $subject->grade->name ?? null,
                'academic_year' => $subject->academicYear->name ?? null,
            ]
        ]);
    }

    // DELETE /gradesubjects/{id}
    public function destroy($id)
    {
        Gradesubject::findOrFail($id)->delete();
        return response()->json(['message' => 'gradesubject deleted']);
    }
}
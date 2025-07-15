<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AcademicYear;

class AcademicYearController extends Controller
{
    public function index()
    {
        return response()->json(
            AcademicYear::select('id', 'name', 'start_date', 'end_date', 'active')->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:academicyears,name',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'active' => 'boolean',
        ]);

        $year = AcademicYear::create($validated);

        return response()->json([
            'message' => 'Created successfully',
            'data' => $year
        ], 201);
    }

    public function show($id)
    {
        $year = AcademicYear::findOrFail($id);

        return response()->json([
            'id' => $year->id,
            'name' => $year->name,
            'start_date' => $year->start_date->toDateString(),
            'end_date' => $year->end_date->toDateString(),
            'active' => $year->active,
        ]);
    }

    public function update(Request $request, $id)
    {
        $year = AcademicYear::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255|unique:academicyears,name,' . $year->id,
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'active' => 'nullable|boolean',
        ]);

        $year->update($validated);

        return response()->json([
            'message' => 'Updated successfully',
            'data' => $year
        ]);
    }

    public function destroy($id)
    {
        $year = AcademicYear::findOrFail($id);
        $year->delete();

        return response()->json(['message' => 'Deleted successfully'], 204);
    }
}
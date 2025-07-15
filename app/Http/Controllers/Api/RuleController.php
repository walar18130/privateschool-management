<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rule;

class RuleController extends Controller
{
    public function index()
    {
        return Rule::all();
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string']);
        $lastSerial = Rule::max('serial_number') ?? 0;

        $rule = Rule::create([
            'serial_number' => $lastSerial + 1,
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Rule created', 'data' => $rule], 201);
    }

    public function show($id)
    {
        return Rule::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $rule = Rule::findOrFail($id);
        $rule->update($request->only('name'));
        return response()->json(['message' => 'Rule updated', 'data' => $rule]);
    }

    public function destroy($id)
    {
        Rule::findOrFail($id)->delete();
        return response()->json(['message' => 'Rule deleted']);
    }
}
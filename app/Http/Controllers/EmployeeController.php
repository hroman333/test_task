<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;


class EmployeeController extends Controller
{
    public function data()
    {
        $query = Employee::with('position')->select('employees.*');

        return DataTables::of($query)
            ->addColumn('photo', function ($employee) {
                $photoPath = $employee->photo_path
                    ? asset('storage/' . $employee->photo_path)
                    : asset('images/default-avatar.png');
                return '<img src="' . $photoPath . '" class="img-circle" width="40">';
            })
            ->addColumn('position', function ($employee) {
                return $employee->position->name ?? '-';
            })
            ->addColumn('action', function ($employee) {
                return '
                <a href="' . route('employees.edit', $employee->id) . '" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></a>
                <form action="' . route('employees.destroy', $employee->id) . '" method="POST" style="display:inline;">
                    ' . csrf_field() . method_field('DELETE') . '
                    <button class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')"><i class="fas fa-trash"></i></button>
                </form>
            ';
            })
            ->rawColumns(['photo', 'action'])
            ->make(true);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([]);
        }

        $employees = Employee::where('name', 'LIKE', '%' . $query . '%')
            ->limit(10)
            ->get(['id', 'name']);

        return response()->json($employees);
    }

    public function index()
    {
        return view('employees.index');
    }

    public function create()
    {
        $positions = Position::all();

        return view('employees.create', compact('positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position_id' => 'required|exists:positions,id',
            'date_of_employment' => 'required|date',
            'phone' => 'required|string|max:20|regex:/^\+380\d{9}$/',
            'email' => 'required|email|unique:employees,email',
            'salary' => 'required|numeric|min:0|max:500000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'manager_id' => 'nullable|integer|exists:employees,id',

        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $validated['photo_path'] = $path;
        }

        $manager = Employee::find($validated['manager_id']);

        if ($manager) {
            $validated['level'] = $manager->level + 1;
        } else {
            $validated['level'] = 1;
        }

        if ($validated['level'] > 5) {
            return back()->withErrors(['manager_id' => 'Cannot assign a manager with level higher than 5.']);
        }

        Employee::create($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Працівника успішно додано!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $positions = Position::all();
        $managers = Employee::where('id', '!=', $employee->id)->get();
        return view('employees.edit', compact('employee', 'positions', 'managers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position_id' => 'required|exists:positions,id',
            'date_of_employment' => 'required|date',
            'phone' => 'required|string|max:20|regex:/^\+380\d{9}$/',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'salary' => 'required|numeric|min:0|max:500000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'manager_name' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $validated['photo_path'] = $path;


            if ($employee->photo_path) {
                Storage::disk('public')->delete($employee->photo_path);
            }
        }

        if (!empty($validated['manager_name'])) {
            $manager = Employee::where('name', $validated['manager_name'])->first();

            if (!$manager) {
                return back()->withErrors(['manager_name' => 'Керівника з таким іменем не знайдено.']);
            }

            $currentManager = $manager;
            $level = 1;

            while ($currentManager && $currentManager->manager_id) {
                if ($currentManager->id === $employee->id) {
                    return back()->withErrors(['manager_name' => 'Неможливо призначити працівника керівником самого себе.']);
                }
                $currentManager = $currentManager->manager;
                $level++;
                if ($level > 5) {
                    return back()->withErrors(['manager_name' => 'Керівник не може бути глибше 5 рівня.']);
                }
            }

            $validated['manager_id'] = $manager->id;
        } else {
            $validated['manager_id'] = null;
        }

        $employee->update($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Працівника успішно оновлено!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        DB::transaction(function () use ($employee) {

            $subordinates = $employee->subordinates;

            foreach ($subordinates as $subordinate) {

                $subordinate->update([
                    'manager_id' => $employee->manager_id,
                ]);
            }

            $employee->delete();
        });

        return redirect()->route('employees.index')->with('success', 'Співробітника успішно видалено з перепідпорядкуванням підлеглих.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PositionController extends Controller
{
    public function data()
    {
        $query = Position::select('positions.*');

        return DataTables::of($query)
            ->editColumn('created_at', function ($position) {
                return $position->created_at ? $position->created_at->format('d.m.Y H:i') : '';
            })
            ->editColumn('updated_at', function ($position) {
                return $position->updated_at ? $position->updated_at->format('d.m.Y H:i') : '';
            })
            ->addColumn('action', function ($position) {
                return '
            <a href="' . route('positions.edit', $position->id) . '" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></a>
            <form action="' . route('positions.destroy', $position->id) . '" method="POST" style="display:inline;">
                ' . csrf_field() . method_field('DELETE') . '
                <button class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')"><i class="fas fa-trash"></i></button>
            </form>
        ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function index()
    {
        return view('positions.index');
    }


    public function create()
    {
        return view('positions.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:256',
        ]);

        $validated['admin_created_id'] = auth()->id();
        $validated['admin_updated_id'] = auth()->id();

        Position::create($validated);

        return redirect()->route('positions.index')
            ->with('success', 'Position created successfully.');
    }

    public function edit(Position $position)
    {
        return view('positions.edit', compact('position'));

    }

    public function update(Request $request, Position $position)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:256',
        ]);

        $position->update(array_merge($validated, [
            'admin_updated_id' => auth()->id(),
        ]));

        return redirect()->route('positions.index')
            ->with('success', 'Position updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Position $position)
    {
        $position->delete();
        return redirect()->route('positions.index')->with('success', 'Position deleted successfully.');
    }
}

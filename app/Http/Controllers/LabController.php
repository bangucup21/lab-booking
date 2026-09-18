<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LabController extends Controller
{
    public function index()
    {
        $labs  = Resource::withCount(['schedules', 'bookings'])
            ->with('organization')
            ->orderBy('name')
            ->get();

        $organizations = Organization::where('is_active', true)->orderBy('name')->get();

        return view('labs.index', compact('labs', 'organizations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:100',
            'type'            => 'required|string|max:50',
            'organization_id' => 'nullable|exists:organizations,id',
            'building'        => 'nullable|string|max:100',
            'floor'           => 'nullable|integer',
            'room_number'     => 'nullable|string|max:50',
            'capacity'        => 'nullable|integer|min:1',
        ]);

        $validated['status'] = 'active';

        Resource::create($validated);

        Cache::forget('active_resources');

        return back()->with('success', "Lab \"{$validated['name']}\" berhasil ditambahkan.");
    }

    public function update(Request $request, Resource $lab)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:100',
            'type'            => 'required|string|max:50',
            'organization_id' => 'nullable|exists:organizations,id',
            'building'        => 'nullable|string|max:100',
            'floor'           => 'nullable|integer',
            'room_number'     => 'nullable|string|max:50',
            'capacity'        => 'nullable|integer|min:1',
            'status'          => 'required|in:active,inactive,maintenance',
        ]);

        $lab->update($validated);

        Cache::forget('active_resources');

        return back()->with('success', "Lab \"{$lab->name}\" berhasil diperbarui.");
    }

    public function destroy(Resource $lab)
    {
        $name = $lab->name;
        $lab->delete();

        Cache::forget('active_resources');

        return back()->with('success', "Lab \"{$name}\" berhasil dihapus.");
    }
}

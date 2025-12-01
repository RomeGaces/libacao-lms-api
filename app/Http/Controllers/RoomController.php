<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('per_page', 10);

        $query = Room::query();

        if ($search) {
            $query->where('room_number', 'like', "%{$search}%")
                ->orWhere('building_name', 'like', "%{$search}%")
                ->orWhere('type', 'like', "%{$search}%");
        }

        $rooms = $query->orderBy('room_number')->paginate($perPage);

        return response()->json([
            'data' => $rooms->items(),
            'current_page' => $rooms->currentPage(),
            'last_page' => $rooms->lastPage(),
            'total' => $rooms->total(),
            'per_page' => $rooms->perPage(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|max:255|unique:rooms,room_number',
            'building_name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:0',
            'type' => 'required|string|max:255',
        ]);

        $room = Room::create($validated);
        return response()->json($room, 201);
    }

    public function show($id)
    {
        $room = Room::findOrFail($id);
        return response()->json($room);
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $validated = $request->validate([
            'room_number' => 'sometimes|string|max:255|unique:rooms,room_number,' . $id . ',room_id',
            'building_name' => 'sometimes|string|max:255',
            'capacity' => 'sometimes|integer|min:0',
            'type' => 'sometimes|string|max:255',
        ]);

        $room->update($validated);
        return response()->json($room);
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();
        return response()->json(['message' => 'Room deleted successfully']);
    }
}

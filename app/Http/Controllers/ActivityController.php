<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityRequest;
use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'name' => 'string|nullable',
            'location' => 'string|nullable',
            'price' => 'numeric|nullable',
            'available_slots' => 'integer|nullable',
        ]);

        $activities = Activity::filter($validated)->get();

        return ActivityResource::collection($activities);
    }

    public function store(StoreActivityRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $data['image'] = $imagePath;
        }

        $activity = Activity::create($data);
        return response()->json($activity, 201);
    }

    public function show(Activity $activity)
    {
        return new ActivityResource($activity);
    }

    public function update(StoreActivityRequest $request, Activity $activity)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($activity->image) {
                Storage::disk('public')->delete($activity->image);
            }
            $imagePath = $request->file('image')->store('images', 'public');
            $data['image'] = $imagePath;
        }

        $activity->update($data);
        return response()->json($activity);
    }

    public function destroy(Activity $activity)
    {
        if ($activity->image) {
            Storage::disk('public')->delete($activity->image);
        }

        $activity->delete();
        return response()->noContent();
    }
}

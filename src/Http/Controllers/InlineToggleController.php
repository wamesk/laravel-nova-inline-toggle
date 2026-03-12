<?php

namespace Wame\InlineToggle\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Nova\Nova;

class InlineToggleController extends Controller
{
    public function update(Request $request, string $resource): JsonResponse
    {
        $validated = $request->validate([
            'id' => ['required', 'string'],
            'attribute' => ['required', 'string'],
            'value' => ['required'],
        ]);

        $resourceClass = Nova::resourceForKey($resource);

        if (! $resourceClass) {
            return response()->json(['message' => 'Resource not found.'], 404);
        }

        $model = $resourceClass::newModel();
        $record = $model->findOrFail($validated['id']);

        $record->{$validated['attribute']} = filter_var($validated['value'], FILTER_VALIDATE_BOOLEAN);
        $record->save();

        return response()->json(['success' => true]);
    }
}

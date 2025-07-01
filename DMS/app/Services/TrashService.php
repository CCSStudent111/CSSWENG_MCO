<?php

namespace App\Services;

class TrashService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        // Initialize any dependencies here if needed
    }
    private function validateModel(string $model)
    {
        if (!class_exists($model)) {
            throw new \InvalidArgumentException("Model {$model} does not exist.");
        }

        $uses = class_uses_recursive($model);

        if (!in_array('Illuminate\Database\Eloquent\SoftDeletes', $uses)) {
            throw new \InvalidArgumentException("Model {$model} must use SoftDeletes.");
        }
    }
    
    public function getTrashed(string $model, array $with = [])
    {
        $this->validateModel($model);
        return $model::onlyTrashed()
            ->with($with) // will edit this part 
            ->get();
    }

    public function restore(string $model, int $id)
    {
        $this->validateModel($model);
        $item = $model::onlyTrashed()->findOrFail($id);
        $item->restore();
    }

    public function forceDelete(string $model, int $id)
    {
        $this->validateModel($model);
        $item = $model::onlyTrashed()->findOrFail($id);
        $item->forceDelete();
    }
}

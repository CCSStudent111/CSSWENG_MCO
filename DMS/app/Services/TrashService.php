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

    public function getTrashed(string $model)
    {
        // Return all soft-deleted records of the given model
    }

    public function restore(string $model, int $id)
    {
        // Restore a soft-deleted record by model and ID
    }

    public function forceDelete(string $model, int $id)
    {
        // Permanently delete a soft-deleted record by model and ID
    }

    public function validateModel(string $model)
    {
        // Check if the given model class is valid and supports soft deletes
    }
}

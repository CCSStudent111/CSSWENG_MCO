<?php

namespace App\Policies;

use App\Models\DocumentPage;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DocumentPagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DocumentPage $documentPage): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DocumentPage $documentPage): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DocumentPage $documentPage): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DocumentPage $documentPage): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DocumentPage $documentPage): bool
    {
        return false;
    }

    public function download(User $user, DocumentPage $documentPage): bool
    {
        $document = $documentPage->document()->with('type.departments')->first();

        if (!$document) {
            return false;
        }

        return $document->type
            ->departments
            ->pluck('id')
            ->contains($user->department_id);
    }
}

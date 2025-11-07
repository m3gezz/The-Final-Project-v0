<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\ProjectInvitation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectInvitationPolicy
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
    public function view(User $user, ProjectInvitation $projectInvitation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Project $project): bool
    {
        $adminIds = $project->members
        ->where('role', 'admin')
        ->pluck('user_id');

        return $adminIds->contains($user->id) || $user->admin;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProjectInvitation $projectInvitation): bool
    {
        return $user->id === $projectInvitation->receiver_id || $user->admin;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProjectInvitation $projectInvitation): bool
    {
        return $user->id === $projectInvitation->user_id || $user->id === $projectInvitation->receiver_id || $user->admin;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProjectInvitation $projectInvitation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProjectInvitation $projectInvitation): bool
    {
        return false;
    }
}

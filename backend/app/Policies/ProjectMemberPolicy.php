<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\User;

class ProjectMemberPolicy
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
    public function view(User $user, ProjectMember $projectMember): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Project $project): bool
    {
        if (!$project) return false;
        $adminIds = $project->members
        ->where('role', 'admin')
        ->pluck('user_id');

        return $user->id === $project->user_id || $adminIds->contains($user->id) || $user->admin;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProjectMember $projectMember): bool
    {
        if ($projectMember->user_id === $user->id || $projectMember->project->user_id === $projectMember->user_id) {
            return false;
        }

        $adminIds = $projectMember->project->members
        ->where('role', 'admin')
        ->pluck('user_id');

        return $adminIds->contains($user->id) || $user->admin;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProjectMember $projectMember): bool
    {
        if ($projectMember->user_id === $user->id || $projectMember->project->user_id === $projectMember->user_id) {
            return false;
        }

        $adminIds = $projectMember->project->members
        ->where('role', 'admin')
        ->pluck('user_id');

        return $adminIds->contains($user->id) || $user->admin;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProjectMember $projectMember): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProjectMember $projectMember): bool
    {
        return false;
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'current_team_id' => $this->current_team_id,
            'profile_photo_path' => $this->profile_photo_path,
            'profile_photo_url' => $this->profile_photo_url,
            'max_own_team' => $this->max_own_team,
            'own_team_count' => $this->ownedTeams()->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'all_teams' => $this->allTeams(),
            'permissions' => $request->user()->permissions()->pluck('name')->toArray()
        ];
    }
}

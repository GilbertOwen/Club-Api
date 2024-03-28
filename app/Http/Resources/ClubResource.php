<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClubResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $materials= [];
        foreach($this->materials as $material) {
            $materials[] = $material->title;
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'club_day' => $this->club_day,
            'tech_field' => $this->tech_field,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'member_count' => count($this->users),
            'materials' => $materials
        ];
    }
}

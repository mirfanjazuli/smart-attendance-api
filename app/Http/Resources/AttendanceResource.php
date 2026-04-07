<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_name' => $this->user->name,
            'location' => $this->location->name,
            'check_in' => $this->check_in->format('Y-m-d H:i:s'),
            'status' => $this->status,
            'photo' => url('storage/' . $this->photo_path),
        ];
    }
}

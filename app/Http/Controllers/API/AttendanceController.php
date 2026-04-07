<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\StoreAttendanceRequest;
use App\Models\Attendance;
use App\Models\Location;
use App\Services\GeofencingService;
use App\Traits\APIResponse;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    use APIResponse;

    public function store(StoreAttendanceRequest $request, GeofencingService $geoService)
    {
        $user = $request->user();
        $location = Location::findOrFail($request->location_id);
        
        // 1. Cek apakah user sudah absen hari ini
        $alreadyCheckedIn = Attendance::where('user_id', $user->id)
            ->whereDate('check_in', now())
            ->exists();

        if ($alreadyCheckedIn) {
            return $this->error("Anda sudah melakukan absensi hari ini.", 422);
        }

        // 2. Cek Jarak via Service
        $check = $geoService->checkDistance(
            $request->latitude, $request->longitude,
            $location->latitude, $location->longitude,
            $location->radius
        );

        if (!$check['is_within_range']) {
            return $this->error("Anda berada di luar radius kantor ({$check['distance']}m)", 422);
        }

        // 3. Decode & Simpan Foto
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->photo));
        $fileName = 'absensi/' . $user->id . '_' . time() . '.png';
        Storage::disk('public')->put($fileName, $imageData);

        // 4. Simpan ke Database
        $attendance = Attendance::create([
            'user_id'     => $user->id,
            'location_id' => $location->id,
            'check_in'    => now(),
            'photo_path'  => $fileName,
            'status'      => now()->format('H:i') > '08:30' ? 'Terlambat' : 'Hadir',
        ]);

        return $this->success($attendance, "Absen berhasil dicatat!");
    }
}

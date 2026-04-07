<?php

namespace App\Http\Controllers\API;

use App\Exports\AttendanceExport;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Traits\APIResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HistoryController extends Controller
{
    use APIResponse;

    public function index(Request $request)
    {
        $user = $request->user();
        
        // Ambil data absen, urutkan dari yang terbaru
        $query = Attendance::with(['location', 'user'])
            ->where('user_id', $user->id);

        // Filter berdasarkan bulan jika ada (format: YYYY-MM)
        if ($request->has('month')) {
            $query->whereMonth('check_in', substr($request->month, 5, 2))
                  ->whereYear('check_in', substr($request->month, 0, 4));
        }

        $attendances = $query->orderBy('check_in', 'desc')->get();

        return $this->success($attendances, "Riwayat absen berhasil diambil");
    }

    public function exportExcel()
    {
        // Mengunduh file dengan nama report-absensi.xlsx
        return Excel::download(new AttendanceExport, 'report-absensi.xlsx');
    }
}

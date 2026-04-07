<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Attendance::with(['user', 'location'])->get();
    }

    public function headings(): array
    {
        return ["Nama Karyawan", "Lokasi Kantor", "Waktu Masuk", "Status"];
    }

    public function map($attendance): array
    {
        return [
            $attendance->user->name,
            $attendance->location->name,
            $attendance->check_in,
            $attendance->status,
        ];
    }
}

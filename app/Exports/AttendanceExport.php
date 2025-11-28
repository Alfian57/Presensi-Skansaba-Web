<?php

namespace App\Exports;

use App\Models\Attendance;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceExport implements FromCollection, WithColumnWidths, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $startDate;

    protected $endDate;

    protected $classroomId;

    public function __construct($startDate = null, $endDate = null, $classroomId = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->classroomId = $classroomId;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        $query = Attendance::with(['student.user', 'student.classroom'])
            ->orderBy('date', 'DESC')
            ->orderBy('status', 'DESC');

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('date', [$this->startDate, $this->endDate]);
        } elseif ($this->startDate) {
            $query->whereDate('date', $this->startDate);
        }

        if ($this->classroomId) {
            $query->whereHas('student', function ($q) {
                $q->where('classroom_id', $this->classroomId);
            });
        }

        return $query->get();
    }

    public function map($attendance): array
    {
        return [
            $attendance->date->format('d/m/Y'),
            $attendance->student->user->name ?? '-',
            $attendance->student->nisn ?? '-',
            $attendance->student->classroom->name ?? '-',
            $attendance->status->label(),
            $attendance->check_in_time?->format('H:i:s') ?? '-',
            $attendance->check_out_time?->format('H:i:s') ?? '-',
            $attendance->notes ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Siswa',
            'NISN',
            'Kelas',
            'Status Kehadiran',
            'Jam Masuk',
            'Jam Pulang',
            'Catatan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,
            'B' => 30,
            'C' => 15,
            'D' => 15,
            'E' => 20,
            'F' => 15,
            'G' => 15,
            'H' => 30,
        ];
    }

    public function title(): string
    {
        $title = 'Presensi';
        if ($this->startDate && $this->endDate) {
            $title .= ' '.date('d-m-Y', strtotime($this->startDate)).' - '.date('d-m-Y', strtotime($this->endDate));
        } elseif ($this->startDate) {
            $title .= ' '.date('d-m-Y', strtotime($this->startDate));
        }

        return $title;
    }
}

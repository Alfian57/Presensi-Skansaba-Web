<?php

namespace App\Exports;

use App\Models\ClassAbsence;
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

class SkippingClassExport implements FromCollection, WithColumnWidths, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $classroomSlug;

    protected $date;

    public function __construct($classroomSlug = null, $date = null)
    {
        $this->classroomSlug = $classroomSlug;
        $this->date = $date;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        $query = ClassAbsence::with(['student.user', 'student.classroom', 'schedule.subject', 'schedule.teacher.user'])
            ->orderBy('date', 'DESC');

        if ($this->classroomSlug) {
            $query->whereHas('student.classroom', function ($q) {
                $q->where('slug', $this->classroomSlug);
            });
        }

        if ($this->date) {
            $query->whereDate('date', $this->date);
        }

        return $query->get();
    }

    public function map($classAbsence): array
    {
        return [
            $classAbsence->date->format('d/m/Y'),
            $classAbsence->student->user->name ?? '-',
            $classAbsence->student->nisn ?? '-',
            $classAbsence->student->classroom->name ?? '-',
            $classAbsence->schedule->subject->name ?? '-',
            $classAbsence->schedule->teacher->user->name ?? '-',
            $classAbsence->reason ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Siswa',
            'NISN',
            'Kelas',
            'Mata Pelajaran',
            'Guru Pengajar',
            'Alasan',
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
            'E' => 25,
            'F' => 25,
            'G' => 40,
        ];
    }

    public function title(): string
    {
        $title = 'Bolos Pelajaran';
        if ($this->date) {
            $title .= ' '.date('d-m-Y', strtotime($this->date));
        }

        return $title;
    }
}

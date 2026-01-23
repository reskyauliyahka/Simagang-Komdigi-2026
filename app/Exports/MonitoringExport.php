<?php

namespace App\Exports;

use App\Models\Intern;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonitoringExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $filters;
    protected $rowNumber = 0;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Intern::with(['mentor', 'user']);

        // Filter by campus/institution (contains, case-insensitive)
        if (!empty($this->filters['institution'])) {
            $institution = trim($this->filters['institution']);
            $query->where('institution', 'like', "%{$institution}%");
        }

        // Filter by mentor
        if (!empty($this->filters['mentor_id'])) {
            $query->where('mentor_id', $this->filters['mentor_id']);
        }

        // Filter by date range (overlap logic)
        $startDate = !empty($this->filters['start_date']) ? Carbon::parse($this->filters['start_date'])->startOfDay() : null;
        $endDate = !empty($this->filters['end_date']) ? Carbon::parse($this->filters['end_date'])->endOfDay() : null;
        if ($startDate && $endDate) {
            // Include interns whose period overlaps [startDate, endDate]
            $query->where('start_date', '<=', $endDate)
                  ->where(function ($q) use ($startDate) {
                      $q->whereNull('end_date')
                        ->orWhere('end_date', '>=', $startDate);
                  });
        } elseif ($startDate) {
            $query->where(function ($q) use ($startDate) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', $startDate);
            });
        } elseif ($endDate) {
            $query->where('start_date', '<=', $endDate);
        }

        // Filter only active interns by default
        if (!isset($this->filters['is_active']) || $this->filters['is_active'] === '1') {
            $query->where('is_active', true);
        } elseif ($this->filters['is_active'] === '0') {
            $query->where('is_active', false);
        }

        return $query->orderBy('institution')
                     ->orderBy('name')
                     ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Anak Magang',
            'Nama Kampus',
            'Jurusan',
            'Mentor',
            'Tim',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Status',
            'Email',
            'No. HP',
            'Jenjang Pendidikan',
            'Tujuan Magang',
        ];
    }

    /**
     * @param mixed $intern
     * @return array
     */
    public function map($intern): array
    {
        $this->rowNumber++;
        
        return [
            $this->rowNumber,
            $intern->name,
            $intern->institution,
            $intern->major,
            $intern->mentor ? $intern->mentor->name : '-',
            $intern->team ?: '-',
            $intern->start_date ? $intern->start_date->format('d/m/Y') : '-',
            $intern->end_date ? $intern->end_date->format('d/m/Y') : '-',
            $intern->is_active ? 'Aktif' : 'Selesai',
            $intern->user ? $intern->user->email : '-',
            $intern->phone ?: '-',
            $intern->education_level ?: '-',
            $intern->purpose ?: '-',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Laporan Monitoring';
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2E8F0'],
                ],
            ],
        ];
    }
}

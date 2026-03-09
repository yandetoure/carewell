<?php

namespace App\Exports;

use App\Models\Appointment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FinancialDataExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $clinicId;

    public function __construct($clinicId = null)
    {
        $this->clinicId = $clinicId;
    }

    public function collection()
    {
        $query = Appointment::with(['user', 'service', 'doctor'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year);

        if ($this->clinicId) {
            $query->where('clinic_id', $this->clinicId);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Date',
            'Patient',
            'Service',
            'Médecin',
            'Statut',
            'Prix (FCFA)',
        ];
    }

    public function map($appointment): array
    {
        return [
            $appointment->id,
            $appointment->appointment_date ? $appointment->appointment_date->format('d/m/Y') : 'N/A',
            $appointment->user ? $appointment->user->first_name . ' ' . $appointment->user->last_name : 'N/A',
            $appointment->service ? $appointment->service->name : 'N/A',
            $appointment->doctor ? $appointment->doctor->first_name . ' ' . $appointment->doctor->last_name : 'N/A',
            ucfirst($appointment->status),
            $appointment->service ? $appointment->service->price : 0,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}

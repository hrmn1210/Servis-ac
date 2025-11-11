<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = User::where('role', 'user');

        if (isset($this->filters['status'])) {
            switch ($this->filters['status']) {
                case 'verified':
                    $query->whereNotNull('email_verified_at');
                    break;
                case 'unverified':
                    $query->whereNull('email_verified_at');
                    break;
                case 'active':
                    $query->where('is_active', true);
                    break;
                case 'suspended':
                    $query->where('is_active', false);
                    break;
            }
        }

        if (isset($this->filters['date_from'])) {
            $query->where('created_at', '>=', $this->filters['date_from']);
        }

        if (isset($this->filters['date_to'])) {
            $query->where('created_at', '<=', $this->filters['date_to'] . ' 23:59:59');
        }

        return $query->withCount('serviceRequests')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Username',
            'Phone Number',
            'Address',
            'Email Verified',
            'Status',
            'Total Requests',
            'Registered Date',
            'Last Updated'
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->username,
            $user->phone_number,
            $user->address,
            $user->email_verified_at ? 'Yes' : 'No',
            $user->is_active ? 'Active' : 'Suspended',
            $user->service_requests_count,
            $user->created_at->format('Y-m-d H:i:s'),
            $user->updated_at->format('Y-m-d H:i:s')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }
}

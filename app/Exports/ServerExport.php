<?php

namespace App\Exports;

use App\Models\superadmin\Server;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ServerExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $filters;
    
    public function __construct($filters)
    {
        $this->filters = $filters;
    }
    
    public function collection()
    {
        $query = Server::with(['rak', 'bidang', 'satker', 'websites']);
        
        // Filter rak
        if (isset($this->filters['rak']) && !empty($this->filters['rak'])) {
            $query->whereHas('rak', function($q) {
                $q->where('nomor_rak', $this->filters['rak']);
            });
        }
        
        // Filter bidang
        if (isset($this->filters['bidang']) && !empty($this->filters['bidang'])) {
            $query->whereHas('bidang', function($q) {
                $q->where('nama_bidang', $this->filters['bidang']);
            });
        }
        
        // Filter satker
        if (isset($this->filters['satker']) && !empty($this->filters['satker'])) {
            $query->whereHas('satker', function($q) {
                $q->where('nama_satker', $this->filters['satker']);
            });
        }
        
        // Filter status
        if (isset($this->filters['status']) && !empty($this->filters['status'])) {
            $query->where('power_status', $this->filters['status']);
        }
        
        return $query->get();
    }
    
    public function headings(): array
    {
        return [
            'No',
            'Nama Server',
            'Brand',
            'Rak / Slot',
            'Bidang',
            'Satker',
            'Status',
            'Jumlah Website',
        ];
    }
    
    public function map($server): array
    {
        static $no = 0;
        $no++;
        
        // Status label
        $status = $server->power_status === 'ON' ? 'Aktif' :
                  ($server->power_status === 'STANDBY' ? 'Maintenance' : 'Tidak Aktif');
        
        return [
            $no,
            $server->nama_server,
            $server->brand ?? '-',
            ($server->rak ? $server->rak->nomor_rak : '-') . ' / ' . ($server->u_slot ?? '-'),
            $server->bidang ? $server->bidang->nama_bidang : '-',
            $server->satker ? $server->satker->nama_satker : '-',
            $status,
            $server->websites->count(),
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
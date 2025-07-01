<?php

namespace App\Exports;

use App\Models\Item;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ItemsExport implements FromCollection, WithHeadings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Item::with(['ruangan','status','latestCalibration'])
            ->get()
            ->map(function ($item){
                return [
                    'uuid' => $item->uuid,
                    'no_seri' => $item->no_seri,
                    'name' => $item->name,
                    'ruangan' => $item->ruangan->name,
                    'condition' => $item->status?->condition,
                    'expired_at' =>  Carbon::parse($item->current_expired)->format('d M Y'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'UUID',
            'NO.Seri',
            'Nama',
            'Ruangan',
            'Kondisi',
            'Masa Berlaku'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->getStyle('A1:F1')->getFont()->setBold(true);

                // Auto-size the columns A-D.
                foreach (range('A', 'F') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle("A1:F{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color'       => ['argb' => 'FFCCCCCC'],
                        ],
                    ],
                ]);
            }
        ];
    }
}

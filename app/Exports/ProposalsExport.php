<?php

namespace App\Exports;

use App\Proposal;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProposalsExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct(int $year)
    {
      $this->year = $year;
    }

    public function headings(): array
    {
        return [
            '#',
            'Skema',
            'Judul',
            'Ketua',
            'Prodi',
            'Pembimbing',
            'Reviewer 1',
            'Reviewer 2',
        ];
    }

    /**
    * @var Proposal $proposal
    */
    public function map($proposal): array
    {
        return [
            $proposal->id,
            $proposal->skema,
            $proposal->judul,
            $proposal->students->first()->nama,
            $proposal->students->first()->major['degree']." ".$proposal->students->first()->major['name'],
            $proposal->teachers->first()->nama,
        ];
    }

    public function query()
    {

      $proposal = Proposal::with(['students' =>function($q) {
        $q->wherePivot('jabatan', '=', 'Ketua');
      }, "teachers" => function($q) {
        $q->wherePivot('jabatan', '=', 'Pembimbing');
      }])
      ->where('period_id', $this->year);
      return $proposal;
    }
}

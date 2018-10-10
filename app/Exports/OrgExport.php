<?php

namespace App\Exports;

use App\User;
use App\Fico;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrgExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection($id)
    {
        $user = User::where('id','=','$id')->get();
        return Fico::where('email','=','$user->email')->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Email',
            'Created At',
            'Updated At',
            'Action At',
            'Active / Inactive',
            'Access Level',
            'Hits',
            'Miss',
        ];
    }
}

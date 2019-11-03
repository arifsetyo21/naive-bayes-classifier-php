<?php

namespace App\Exports;

use App\Models\Article;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class ArticleExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    public function headings(): array
    {
        return [
            'id',
            'url_id',
            'title',
            'content',
            'created_at',
            'updated_at',
            'deleted_at',
            'category_id',
        ];
    }

    public function collection()
    {
        return Article::where('id', '>', 61)->get();
    }

}

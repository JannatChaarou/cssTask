<?php

namespace App\Exports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TasksExport implements FromCollection, WithHeadings
{
    /**
     * Return the collection of tasks for export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Get tasks for the authenticated user
        return Task::where('user_id', auth()->id())->get(['title', 'priority', 'description', 'created_at', 'updated_at']);
    }

    /**
     * Set the headings for the exported file.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Title',
            'Priority',
            'Description',
            'Created At',
            'Updated At'
        ];
    }
}

<?php

namespace App\Livewire\Pages;

use App\Models\Report;
use Livewire\Component;
use Livewire\Attributes\Layout;

class ReportDetails extends Component
{
    public Report $report;

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.pages.report-details', [
            'report' => $this->report
        ]);
    }
}

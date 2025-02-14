<?php

namespace App\Livewire\Pages;

use App\Enum\Status;
use App\Models\Report;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

class ReportSearch extends Component
{
    use WithPagination;

    protected $queryString = [
        'userId' => ['except' => ''],
    ];

    #[Rule('required')]
    public string $userId = '';

    public function mount()
    {
        $this->userId = request()->query('userId', $this->userId);
    }

    public function search()
    {
        //
    }

    public function resetResult()
    {
        $this->reset();
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.pages.report-search', [
            'reports' => Report::topReportedUsers(limit: 8)
                ->when($this->userId, fn ($query) => $query->where('discord_user_id', $this->userId))
                ->get(),
        ]);
    }
}

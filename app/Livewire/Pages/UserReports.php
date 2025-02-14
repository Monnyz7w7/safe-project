<?php

namespace App\Livewire\Pages;

use App\Enum\Status;
use App\Models\Report;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

class UserReports extends Component
{
    use WithPagination;

    const ITEMS_PER_PAGE = 12;

    #[Rule('required')]
    public string $userId = '';

    public string $filter = '';

    public string $status = '';

    public $discordUserId = null;

    protected $queryString = [
        'userId' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function mount()
    {
        $this->userId = request()->query('userId', $this->userId);
        $this->filter = request()->query('filter', $this->filter);
        $this->status = request()->query('status', $this->status);
    }

    public function search()
    {
        //
    }

    public function resetResult()
    {
        $this->reset([
            'status'
        ]);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.pages.user-reports', [
            'reports' => $reports = Report::query()
                ->where('discord_user_id', $this->discordUserId)
                ->when($this->status, fn ($query) => $query->where('status', Status::from($this->status)))
                ->with([
                    'discordServer',
                    'user'
                ])
                // ->select('reports.*')
                // ->addSelect(DB::raw('(SELECT COUNT(*) FROM reports AS r WHERE r.discord_user_id = reports.discord_user_id) AS report_count'))
                // ->orderBy('report_count', 'desc')
                ->latest()
                ->paginate(self::ITEMS_PER_PAGE),
            'statuses' => Status::cases(),
            'recordsCount' => $reports->total()
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Report::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('reports.index', [
            'reports' => auth()->user()->reports()->latest()->latest('id')->paginate(10),
            'discordServer' => auth()->user()->discordServer
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('reports.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        return view('reports.show', [
            'report' => $report
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\Management;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Management\SectorRequest;
use App\Repositories\Eloquent\SectorRepository;
use App\Repositories\Eloquent\AcademicYearRepository;

class SectorController extends Controller
{
    protected $sectorRepository;
    protected $academicYearRepository;
    
    public function __construct(SectorRepository $sectorRepository, AcademicYearRepository $academicYearRepository)
    {
        $this->sectorRepository = $sectorRepository;
        $this->academicYearRepository = $academicYearRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sectors = $this->sectorRepository->paginateWith(['academicYear'], 10);
        $academicYears = $this->academicYearRepository->all();
        
        return Inertia::render('management/sector/index', [
            'sectors' => $sectors,
            'academicYears' => $academicYears,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SectorRequest $request)
    {
        $this->sectorRepository->create($request->all());
        return redirect()->route('management.sectors.index')->with('success', 'Ngành sinh hoạt ' . $request->name . ' đã được tạo thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SectorRequest $request, string $id)
    {
        $this->sectorRepository->update($id, $request->all());
        return redirect()->route('management.sectors.index')->with('success', 'Ngành sinh hoạt ' . $request->name . ' đã được cập nhật thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $name = $this->sectorRepository->find($id)->name;
        $this->sectorRepository->delete($id);
        return redirect()->route('management.sectors.index')->with('success', 'Ngành sinh hoạt ' . $name . ' đã được xóa thành công');
    }
}

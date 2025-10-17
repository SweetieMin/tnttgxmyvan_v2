<?php

namespace App\Http\Controllers\Management;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Management\SectorRequest;
use App\Repositories\Eloquent\SectorRepository;
use App\Repositories\Eloquent\AcademicYearRepository;
use App\Helpers\ResponseToastHelper;

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
    public function index(Request $request)
    {
        $academicYearId = $request->get('academic_year_id');
        
        // Tìm niên khóa hiện tại nếu không có filter
        if (!$academicYearId) {
            $currentYear = date('Y');
            $currentAcademicYear = $this->academicYearRepository->getModel()
                ->where('name', 'like', $currentYear . '%')
                ->where('status_academic', 'ongoing')
                ->first();
            
            if ($currentAcademicYear) {
                $academicYearId = $currentAcademicYear->id;
            }
        }
        
        // Query sectors với filter
        $query = $this->sectorRepository->getModel()->with(['academicYear']);
        if ($academicYearId) {
            $query->where('academic_year_id', $academicYearId);
        }
        
        $sectors = $query->orderBy('ordering', 'asc')->paginate(10);
        $academicYears = $this->academicYearRepository->all();
        
        return Inertia::render('management/sector/index', [
            'sectors' => $sectors,
            'academicYears' => $academicYears,
            'currentAcademicYearId' => $academicYearId,
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
        return ResponseToastHelper::successRedirect(
            'management.sectors.index',
            'Ngành sinh hoạt ":name" đã được tạo thành công.',
            ['name' => $request->name]
        );
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
        return ResponseToastHelper::successRedirect(
            'management.sectors.index',
            'Ngành sinh hoạt ":name" đã được cập nhật thành công.',
            ['name' => $request->name]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $name = $this->sectorRepository->find($id)->name;
        $this->sectorRepository->delete($id);
        return ResponseToastHelper::successRedirect(
            'management.sectors.index',
            'Ngành sinh hoạt ":name" đã được xóa thành công.',
            ['name' => $name]
        );
    }
}

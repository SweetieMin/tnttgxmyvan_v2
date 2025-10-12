<?php

namespace App\Http\Controllers\Management;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Management\RegulationRequest;
use App\Repositories\Eloquent\RegulationRepository;
use App\Repositories\Eloquent\AcademicYearRepository;

class RegulationController extends Controller
{
    protected $regulationRepository;
    protected $academicYearRepository;
    
    public function __construct(RegulationRepository $regulationRepository, AcademicYearRepository $academicYearRepository)
    {
        $this->regulationRepository = $regulationRepository;
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
                ->first();
            
            if ($currentAcademicYear) {
                $academicYearId = $currentAcademicYear->id;
            }
        }
        
        // Query regulations với filter
        $query = $this->regulationRepository->getModel()->with(['academicYear']);
        if ($academicYearId) {
            $query->where('academic_year_id', $academicYearId);
        }
        
        $regulations = $query->orderBy('ordering', 'asc')->paginate(20);
        $academicYears = $this->academicYearRepository->all();
        
        return Inertia::render('management/regulation/index', [
            'regulations' => $regulations,
            'academicYears' => $academicYears,
            'currentAcademicYearId' => $academicYearId,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegulationRequest $request)
    {
        $this->regulationRepository->create($request->all());
        
        // Redirect về niên khóa hiện tại (đang ongoing)
        $currentAcademicYear = $this->academicYearRepository->getModel()
                ->first();
        
        if ($currentAcademicYear) {
            return redirect()->route('management.regulations.index', ['academic_year_id' => $currentAcademicYear->id])
                ->with('success', 'Nội quy đã được tạo thành công');
        }
        
        return redirect()->route('management.regulations.index')->with('success', 'Nội quy đã được tạo thành công');
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
    public function update(RegulationRequest $request, string $id)
    {
        $this->regulationRepository->update($id, $request->all());
        
        // Redirect về niên khóa hiện tại (đang ongoing)
        $currentAcademicYear = $this->academicYearRepository->getModel()
            ->first();
        
        if ($currentAcademicYear) {
            return redirect()->route('management.regulations.index', ['academic_year_id' => $currentAcademicYear->id])
                ->with('success', 'Nội quy đã được cập nhật thành công');
        }
        
        return redirect()->route('management.regulations.index')->with('success', 'Nội quy đã được cập nhật thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->regulationRepository->delete($id);
        return redirect()->route('management.regulations.index')->with('success', 'Nội quy đã được xóa thành công');
    }
}

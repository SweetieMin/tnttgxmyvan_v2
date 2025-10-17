<?php

namespace App\Http\Controllers\Management;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\ResponseToastHelper;
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
        
        return ResponseToastHelper::successRedirect(
            'management.regulations.index',
            'Nội quy ":description" đã được tạo thành công.',
            [
                'description' => $request->description,
                'academic_year_id' => optional($currentAcademicYear)->id,
            ]
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
    public function update(RegulationRequest $request, string $id)
    {
        $this->regulationRepository->update($id, $request->all());
        
        // Redirect về niên khóa hiện tại (đang ongoing)
        $currentAcademicYear = $this->academicYearRepository->getModel()
            ->first();

        return ResponseToastHelper::successRedirect(
            'management.regulations.index',
            'Nội quy ":description" đã được cập nhật thành công.',
            [
                'description' => $request->description,
                'academic_year_id' => optional($currentAcademicYear)->id,
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $currentAcademicYear = $this->academicYearRepository->getModel()
            ->first();
        $description = $this->regulationRepository->find($id)->description;
        $this->regulationRepository->delete($id);
        return ResponseToastHelper::successRedirect(
            'management.regulations.index',
            'Nội quy ":description" đã được tạo thành công.',
            [
                'description' => $description,
                'academic_year_id' => optional($currentAcademicYear)->id,
            ]
        );
    }
}

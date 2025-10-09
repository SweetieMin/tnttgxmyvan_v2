<?php

namespace App\Http\Controllers\Management;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Management\CourseRequest;
use App\Repositories\Eloquent\CourseRepository;
use App\Repositories\Eloquent\AcademicYearRepository;

class CourseController extends Controller
{
    protected $courseRepository;
    protected $academicYearRepository;
    
    public function __construct(CourseRepository $courseRepository, AcademicYearRepository $academicYearRepository)
    {
        $this->courseRepository = $courseRepository;
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
        
        // Query courses với filter
        $query = $this->courseRepository->getModel()->with(['academicYear']);
        if ($academicYearId) {
            $query->where('academic_year_id', $academicYearId);
        }
        
        $courses = $query->orderBy('ordering', 'asc')->paginate(10);
        $academicYears = $this->academicYearRepository->all();
        
        return Inertia::render('management/course/index', [
            'courses' => $courses,
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
    public function store(CourseRequest $request)
    {
        $this->courseRepository->create($request->all());
        return redirect()->route('management.courses.index')->with('success', 'Lớp giáo lý ' . $request->name . ' đã được tạo thành công');
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
    public function update(CourseRequest $request, string $id)
    {
        $this->courseRepository->update($id, $request->all());
        return redirect()->route('management.courses.index')->with('success', 'Lớp giáo lý ' . $request->name . ' đã được cập nhật thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $name = $this->courseRepository->find($id)->name;
        $this->courseRepository->delete($id);
        return redirect()->route('management.courses.index')->with('success', 'Lớp giáo lý ' . $name . ' đã được xóa thành công');
    }
}

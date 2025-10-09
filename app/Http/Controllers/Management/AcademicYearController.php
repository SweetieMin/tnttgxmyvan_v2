<?php

namespace App\Http\Controllers\Management;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Management\AcademicYearRequest;
use App\Repositories\Interfaces\AcademicYearRepositoryInterface;

class AcademicYearController extends Controller
{
    protected $academicYearRepository;

    public function __construct(AcademicYearRepositoryInterface $academicYearRepository)
    {
        $this->academicYearRepository = $academicYearRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $years = $this->academicYearRepository->paginate(10);

        return Inertia::render('management/academic-year/index', [
            'years' => $years,
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
    public function store(AcademicYearRequest $request)
    {
        $this->academicYearRepository->create($request->validated());

        return redirect()->route('management.academic-years.index')->with('success', 'Niên khóa ' . $request->name . ' đã được tạo thành công');
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
    public function update(AcademicYearRequest $request, string $id)
    {
        $this->academicYearRepository->update($id, $request->validated());

        return redirect()->route('management.academic-years.index')->with('success', 'Niên khóa ' . $request->name . ' đã được cập nhật thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $name = $this->academicYearRepository->find($id)->name;
        $this->academicYearRepository->delete($id);

        return redirect()->route('management.academic-years.index')->with('success', 'Niên khóa ' . $name . ' đã được xóa thành công');
    }
}

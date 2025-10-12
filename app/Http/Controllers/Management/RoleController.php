<?php

namespace App\Http\Controllers\Management;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Management\RoleRequest;
use App\Repositories\Eloquent\RoleRepository;

class RoleController extends Controller
{
    protected $roleRepository; 
    
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = $this->roleRepository->getModel()
            ->orderBy('ordering', 'asc')
            ->paginate(20);
        
        return Inertia::render('management/role/index', [
            'roles' => $roles,
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
    public function store(RoleRequest $request)
    {
        $this->roleRepository->create($request->all());
        return redirect()->route('management.roles.index')->with('success', 'Vai trò ' . $request->name . ' đã được tạo thành công');
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
    public function update(RoleRequest $request, string $id)
    {
        $this->roleRepository->update($id, $request->all());
        return redirect()->route('management.roles.index')->with('success', 'Vai trò ' . $request->name . ' đã được cập nhật thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $name = $this->roleRepository->find($id)->name;
        $this->roleRepository->delete($id);
        return redirect()->route('management.roles.index')->with('success', 'Vai trò ' . $name . ' đã được xóa thành công');
    }
}

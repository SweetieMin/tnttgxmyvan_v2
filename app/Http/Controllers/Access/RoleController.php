<?php

namespace App\Http\Controllers\Access;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Access\RoleRequest;
use App\Repositories\Eloquent\RoleRepository;
use App\Models\RoleHierarchy;

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
            ->with('subRoles')
            ->orderBy('ordering', 'asc')
            ->paginate(20);
        
        // Get all roles for hierarchy selection
        $allRoles = $this->roleRepository->getModel()
            ->orderBy('ordering', 'asc')
            ->get();
        
        return Inertia::render('access/role/index', [
            'roles' => $roles,
            'allRoles' => $allRoles,
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
        // Create the role
        $role = $this->roleRepository->create($request->all());
        
        // Handle role hierarchy
        $this->updateRoleHierarchy($role->id, $request->input('managed_role_ids', []));
        
        return redirect()->route('access.roles.index')->with('success', 'Vai trò ' . $request->name . ' đã được tạo thành công');
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
        // Update the role
        $this->roleRepository->update($id, $request->all());
        
        // Handle role hierarchy
        $this->updateRoleHierarchy($id, $request->input('managed_role_ids', []));
        
        return redirect()->route('access.roles.index')->with('success', 'Vai trò ' . $request->name . ' đã được cập nhật thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $name = $this->roleRepository->find($id)->name;
        $this->roleRepository->delete($id);
        return redirect()->route('access.roles.index')->with('success', 'Vai trò ' . $name . ' đã được xóa thành công');
    }

    /**
     * Update role hierarchy relationships
     */
    private function updateRoleHierarchy($roleId, $managedRoleIds)
    {
        // Remove existing hierarchies for this role
        RoleHierarchy::where('role_id', $roleId)->delete();

        // Add new hierarchies
        foreach ($managedRoleIds as $managedRoleId) {
            // Prevent self-management
            if ($managedRoleId != $roleId) {
                RoleHierarchy::create([
                    'role_id' => $roleId,
                    'manages_role_id' => $managedRoleId,
                ]);
            }
        }
    }
}

<?php

namespace App\Http\Controllers\Access;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Access\RoleRequest;
use App\Repositories\Eloquent\RoleRepository;
use App\Helpers\ResponseToastHelper;

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
        // Get all roles for hierarchy selection
        $allRoles = $this->roleRepository->getModel()
            ->orderBy('ordering', 'asc')
            ->get();
        
        return Inertia::render('access/role/actions-role', [
            'allRoles' => $allRoles,
            'mode' => 'create',
        ]);
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
        
        return ResponseToastHelper::successRedirect(
            'access.roles.index',
            'Vai trò ":name" đã được tạo thành công.',
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
        $role = $this->roleRepository->find($id);
        $role->load('subRoles');
        
        // Get all roles for hierarchy selection
        $allRoles = $this->roleRepository->getModel()
            ->orderBy('ordering', 'asc')
            ->get();
        
        // Get managed roles for this role (sub roles that this role manages)
        $managedRoles = $role->subRoles;
        
        return Inertia::render('access/role/actions-role', [
            'role' => $role,
            'allRoles' => $allRoles,
            'managedRoles' => $managedRoles,
            'mode' => 'edit',
        ]);
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
        
        return ResponseToastHelper::successRedirect(
            'access.roles.index',
            'Vai trò ":name" đã được cập nhật thành công.',
            ['name' => $request->name]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = $this->roleRepository->find($id);
        $name = $role->name;
        
        // Sync with empty array to remove all relationships
        $role->subRoles()->sync([]);
        
        // Delete the role
        $this->roleRepository->delete($id);
        
        return ResponseToastHelper::successRedirect(
            'access.roles.index',
            'Vai trò ":name" đã được xóa thành công.',
            ['name' => $name]
        );
    }

    /**
     * Update role hierarchy relationships using sync
     */
    private function updateRoleHierarchy($roleId, $managedRoleIds)
    {
        
        // Get the role instance
        $role = $this->roleRepository->find($roleId);
        
        // Filter out self-management (prevent role from managing itself)
        $filteredManagedRoleIds = array_filter($managedRoleIds, function($managedRoleId) use ($roleId) {
            return $managedRoleId != $roleId;
        });
        
        // Use sync to update the many-to-many relationship
        $role->subRoles()->sync($filteredManagedRoleIds);

    }
}

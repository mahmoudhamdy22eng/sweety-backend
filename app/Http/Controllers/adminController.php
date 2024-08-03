<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class adminController extends Controller
{
    public function index()
    {
        return response()->json(Admin::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admin,email', // Notice the table name 'admin' instead of 'admins'
            'role' => 'required|string|max:50',
            'password' => 'required|string|min:6', // Add this if you want password validation as well
        ]);
        

        $admin = Admin::create($request->all());

        return response()->json($admin, 201);
    }

    public function show($id)
    {
        $admin = Admin::findOrFail($id);

        return response()->json($admin);
    }
    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);
    
        // Validate incoming request data, exclude password from requirement if not provided
        $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|unique:admin,email,' . $admin->id, // Ensure unique except for this record
            'role' => 'string|max:50',
            // No password validation here unless you specifically want to enforce conditions when provided
        ]);
    
        // Data array excluding password initially
        $data = $request->only(['name', 'email', 'role']);
    
        // Check if a new password was provided and only then include it in the data array
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password); // Hash new password
        }
    
        // Update the admin with the new data
        $admin->update($data);
    
        return response()->json($admin);
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete(); // Soft delete

        return response()->json(['success' => true]);
    }

    public function restore($id)
    {
        $admin = Admin::withTrashed()->findOrFail($id);
        $admin->restore(); // Restore the soft deleted record

        return response()->json(['success' => true]);
    }
}
<?php

    namespace App\Http\Controllers;
    
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use App\Models\User;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;
    
    class UserController extends Controller
    {
        public function store(Request $request)
        {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'phone' => 'required|string|max:15',
                'user_type' => 'required|string|in:admin,supplier,customer',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
    
            // Create a new user
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'phone' => $request->input('phone'),
                'user_type' => $request->input('user_type'),
            ]);
    
            return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
        }
    
        public function update(Request $request, $id)
        {
            // Validate the request data
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
                'phone' => 'required|string|max:15',
            ]);
    
            // Update the user's information
            $user = User::findOrFail($id);
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->phone = $request->input('phone');
            $user->save();
    
            return response()->json(['message' => 'User information updated successfully', 'user' => $user]);
        }
    
        public function destroy($id)
        {
            // Delete the user
            $user = User::findOrFail($id);
            $user->delete();
    
            return response()->json(['message' => 'User deleted successfully']);
        }
    
        public function index()
        {
            // Get all users
            $users = User::all();
            return response()->json($users); // Return directly as an array
        }
    
        public function show($id)
        {
            // Get a specific user
            $user = User::findOrFail($id);
            return response()->json(['user' => $user]);
        }

        /**
     * Toggle the is_deleted status of a user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleStatus($id)
    {
        // Find the user by id
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Toggle the is_deleted status
        $user->is_deleted = $user->is_deleted ? 0 : 1;
        $user->save();

        return response()->json(['message' => 'User status updated successfully', 'user' => $user]);
    }
}


    

    
    // public function update(Request $request)
    // {
    //     // Validate the request data
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
    //         'phone' => 'required|string|max:15',
    //     ]);

    //     // Update the authenticated user's information
    //     $user = Auth::user();
    //     $user->name = $request->input('name');
    //     $user->email = $request->input('email');
    //     $user->phone = $request->input('phone');
    //     $user->save();

    //     return response()->json(['message' => 'User information updated successfully', 'user' => $user]);
    // }


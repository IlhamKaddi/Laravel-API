<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
// get all users ---------------------------------

//    public function index(Request $request)
//     {
//           $perPage = $request->query('per_page', 2);
//           $request->validate([
//               'per_page' => 'integer|min:1|max:50'
//          ]);
//         // $users = User::all();
//         //  $users =User::paginate($perPage);
//         $users = User::paginate($perPage)->withQueryString();
//         // search 
//         $search = $request->query('search');
//         $query = User::query();

//       if ($search) {
//             $query->where('name', 'like', '%' . $search . '%');
//         }

//         return response()->json([
//             'users' => $users
//         ]);
//     }

public function index(Request $request)
{
    $perPage = $request->query('per_page', 2);

    $request->validate([
        'per_page' => 'integer|min:1|max:50',
        'search' => 'nullable|string|max:100',
        'sort' => 'nullable|in:name,email,created_at',
        'direction' => 'nullable|in:asc,desc',
    ]);

    $search = $request->query('search');
    $role = $request->query('role');

    $query = User::query();

    // if ($search) {
    //     $query->where('name', 'like', '%' . $search . '%')
    //           ->orWhere('email', 'like', '%' . $search . '%');
    // }
    if ($search) {
    $query->where(function ($q) use ($search) {
        $q->where('name', 'like', '%' . $search . '%')
          ->orWhere('email', 'like', '%' . $search . '%');
    });
}

    if ($role) {
    $query->where('role', $role);
}
$sort = $request->query('sort');
$direction = $request->query('direction', 'asc');  

if ($sort) {
    $query->orderBy($sort, $direction);
}

$users = $query->paginate($perPage)->withQueryString();

    return response()->json([
        'users' => $users
    ]);
}


// get user by id ---------------------------------
    public function show($id)
{
    // $user = User::find($id);   -> this it return user but whene user not found it return (null)

    // this is return (not found) if user not exist in db
      $user = User::findOrFail($id);

    return response()->json([
        'user' => $user
    ]);
}

// update -------------------------------------------------------
public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'role' => 'required|in:user,manager,admin',
    ]);

    $user->update($validated);

    return response()->json([
        'message' => 'User updated successfully',
        'user' => $user
    ]);
}


// delete user by id ------------------------------------------
public function destroy($id)
{
    $user = User::findOrFail($id);

    $user->delete();

    return response()->json([
        'message' => 'User deleted successfully'
    ]);
}

}
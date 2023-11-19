<?php

namespace App\Http\Controllers;

use App\Http\Resources\{
    User as ResourcesUser,
    UserCollection
};
use App\Models\User;
use App\Support\Responder;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::paginate(10);
        return Responder::success(new UserCollection($user), __('message.success_retrieved'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|numeric',
            'age' => 'nullable|integer'
        ]);

        return Responder::success(new ResourcesUser(User::create($request->all())), __('message.success_create'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Responder::success(new ResourcesUser(User::findOrFail($id)), __('message.success_retrieved'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'nullable|numeric',
            'age' => 'nullable|integer'
        ]);

        $user = User::findOrFail($id);
        $user->update($request->all());

        return Responder::success(new ResourcesUser($user->refresh()), __('message.success_update'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return Responder::success($user, __('message.success_delete'));
    }

    public function searchUsers(Request $request)
    {
        $request->validate([
            'field' => 'required|in:email,phone',
            'value' => 'required|string',
            'order' => 'nullable|in:asc,desc',
        ]);

        // Query users based on the provided field and value
        $users = User::where($request->input('field'), 'like', "%$request->value%");

        // Sort the results
        if ($request->has('order')) {
            $order = $request->input('order');
            $users->orderBy($request->input('field'), $order);
        }

        // Get the results
        $results = $users->paginate(10);

        return Responder::success(new UserCollection($results), __('message.success_retrieved'));
    }
}

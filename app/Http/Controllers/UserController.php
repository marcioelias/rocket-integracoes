<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Rules\OldPasswordValidation;
use App\traits\UtilsTrait;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    use UtilsTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('layouts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:60|unique:users',
            'password' => 'required|min:8|confirmed'
        ], [], [
            'name' => 'Nome',
            'email' => 'Email',
            'username' => 'Usuário',
            'password' => 'Senha'
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'active' => $request->active
        ]);

        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(['redirect' => route('users.index')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return View('users.profile')->withModel($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return View('users.edit')->withModel($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|string|email|max:255|unique:users,id,'.$user->id,
        ], [], [
            'name' => 'Nome',
            'email' => 'Email',
            'username' => 'Usuário',
            'password' => 'Senha'
        ]);

        $user->fill([
            'name' => $request->name,
            'email' => $request->email,
            'active' => $request->active
        ]);

        $user->save();
        return response()->json(['redirect' => route('users.index')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if ($user->id == Auth::user()->id)
            return response()
                ->json(['message' => 'Não é possível remover o usuário atualmente logado.'])
                ->setStatusCode(403);
        else {
            $this->destroyModel($user);
        }
    }

    public function getUser(User $user) {
        return response()->json($user);
    }

    public function toggleActive(User $user) {
        if ($user->id != Auth::user()->id) {
            $user->active = !$user->active;
            $user->save();
        }
    }

    public function changePassword(Request $request, User $user) {
        $this->validate($request, [
            'old_password' => ['required', new OldPasswordValidation($user->password)],
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->password = bcrypt($request->password);

        return response()->json($user->save());
    }
}

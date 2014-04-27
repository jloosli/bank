<?php

class UserController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @route GET /user/
     *
     * @return Response
     */
    public function index() {
        $user = User::with('envelopes')
            ->where('bank_id', '=', Auth::user()->bank_id)
            ->where('user_type','user')
            ->get();
        return Response::json($user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        // Shouldn't be used in the api
    }

    /**
     * Store a newly created resource in storage.
     * @route POST /user/
     *
     * @return Response
     */
    public function store() {
        $user              = new User();
        $user->email       = Input::get('email');
        $user->name        = Input::get('name');
        $user->user_type   = Input::get('user_type', 'user');
        $user->username    = Input::get('username');
        $user->password    = Hash::make(Input::get('password'));
        $user->active      = 1;
        $user->balance     = 0;
        $user->bank_id     = Auth::user()->bank_id;

        $rules             = User::$rules;
        $rules['password'] = "required|min:3";
        if ($user->save($rules)) {
            $user->load('envelopes');
            return Response::json(array('success' => true, 'message' => "{$user->name} saved Successfully", 'data' => $user->toArray()));
        } else {
            return Response::json(array('success' => false, 'message' => $user->errors()->all()));
        }
    }

    /**
     * Display the specified resource.
     *
     * @ route GET /user/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function show($id) {
        $user = User::find($id);
        return Response::json(array('success' => true, 'data' => $user->toArray()));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id) {
        // not used
    }

    /**
     * Update the specified resource in storage.
     * @route PUT /user/{id}
     *
     *
     * @param  int $id
     * @return Response
     */
    public function update($id) {
        $user            = User::find($id);
        $user->email     = Input::get('email');
        $user->name      = Input::get('name');
        $user->user_type = Input::get('user_type');
        $user->username  = Input::get('username');
        $user->active    = Input::get('active');
        if (Input::get('password')) {
            $user->password = Hash::make(Input::get('password'));
        }
        if ($user->save()) {
            $user->load('envelopes');
            return Response::json(array('success' => true, 'message' => "{$user->name} saved Successfully", 'data' => $user->toArray()));
        } else {
            return Response::json(array('success' => false, 'message' => $user->errors()->all()));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @route DELETE /user/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id) {
        //@todo delete associated records
    }

    public function login() {
        $credentials = array('username' => Input::get('username'), 'password' => Input::get('password'), 'active' => 1);
        if (Auth::attempt($credentials)) {
            $authToken = AuthToken::create(Auth::user());
            $publicToken = AuthToken::publicToken($authToken);
            return Response::json(array('success' => true, 'message' => 'Logged In', 'data' => Auth::user()->toArray(), 'token' => $publicToken));
        }
        return Response::json(array('success' => false, 'message' => 'Incorrect Username or Password'));
    }

    public function logout() {
        Auth::logout();
        return Response::json(array('success' => true, 'message' => 'Logged Out'));
    }

}
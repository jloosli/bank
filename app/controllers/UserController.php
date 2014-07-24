<?php

class UserController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @route GET /banks/{id}/users/
     *
     * @param $id Bank id
     *
     * @return Response
     */
    public function index($id) {
        $users = User::with('envelopes')
            ->where('bank_id', '=', $id)
        ->get();
//            ->where('user_type','user')
//            ->paginate(3);
        return $users;
//        $response= $this->response->withPaginator()->withCollection($users, new \AvantiDevelopment\JrBank\UserTransformer());
//        return Response::json($users);
//        return Response::api()->addHeader('name','value')->withCollection($users, new AvantiDevelopment\JrBank\UserTransformer(),null, 'users');
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
     * @ route GET bank/{bank_id}/users/{user_id}
     *
     * @param $bank_id
     * @param $user_ids
     *
     * @return Response
     */
    public function show($bank_id, $user_ids) {
        $ids = explode(',',$user_ids);
        $user = User::with('envelopes')->whereIn('id', $ids)
        ->where('bank_id', $bank_id)->get();

//        return Response::api()->withCollection($user, new AvantiDevelopment\JrBank\BasicTransformer(), null, 'users');
        return $user;

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
     * @route DELETE banks/{id}/user/{user_id}
     *
     * @param $bank_id
     * @param $user_id
     *
     * @return Response
     */
    public function destroy($bank_id, $user_id) {
        $user = User::where('bank_id', $bank_id)->where('id', $user_id)->get();
        $user->delete();
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
<?php

use AvantiDevelopment\JrBank\Models\User;

class UserController extends \BaseController {
//    use Dingo\Api\Routing\ControllerTrait;
    /**
     * Display a listing of the resource.
     *
     * @route GET /banks/{id}/users/
     *
     * @param $id User id
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index( $id ) {
        $users = User::with( 'envelopes' )
                     ->where( 'bank_id', '=', $id )
                     ->get();

        return $users;
    }

    public function currentUser() {
        $user = API::user();
        if ( $user ) {
            return API::user();
        }
        throw new Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
    }

    /**
     * Store a newly created resource in storage.
     * @route POST /user/
     *
     */
    public function store( $bank_id ) {
        $user = new User();
        $inputs = Input::only( $user->getFillable() );
        $inputs = array_filter( $inputs, function ( $val ) {
            return !is_null( $val );
        } );
        if ( isset( $inputs['password'] ) ) {
            $inputs['password'] = Hash::make( $inputs['password'] );
        }
        $user->fill( $inputs );
        $user->bank_id = $bank_id;

        if ( $user->save() ) {
            Log::info( sprintf( 'Created User %s (%d)', $user->name, $user->id ) );
            $user->load( 'envelopes' );

            return $this->response->array( array(
                'success' => true,
                'message' => "{$user->name} saved Successfully",
                'data'    => $user->toArray()
            ) );
        } else {
            throw new Dingo\Api\Exception\StoreResourceFailedException( 'Could not create User.', $user->getErrors() );
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
    public function show( $bank_id, $user_ids ) {
        $ids = explode( ',', $user_ids );
        if ( Input::get( 'show_deleted' ) === 'true' ) {
            $user = User::withTrashed();
        } else {
            $user = new User();
        }
        $user = $user->with( 'envelopes' )->whereIn( 'id', $ids )
                     ->where( 'bank_id', $bank_id )->get();

        if ( count( $user ) ) {
            return $user;
        } else {
            throw new Symfony\Component\HttpKernel\Exception\NotFoundHttpException(
                "User" & count( $ids ) > 1 ? 's' : '' & ' not found.'
            );
        }


    }

    /**
     * Update the specified resource in storage.
     * @route PUT /user/{id}
     *
     *
     * @param      $bank_id
     * @param  int $user_id
     *
     * @return Response
     */
    public function update( $bank_id, $user_id ) {
        if ( Input::get( 'undelete' ) == 'true' ) {
            User::withTrashed()->where( 'bank_id', $bank_id )->where( 'id', $user_id )->restore();
        }
        $user = User::where( 'bank_id', $bank_id )->where( 'id', $user_id )->first();
        if ( !$user ) {
            throw new Symfony\Component\HttpKernel\Exception\NotFoundHttpException( "User not found." );
        }
        $inputs = Input::only( $user->getFillable() );
        $inputs = array_filter( $inputs, function ( $val ) {
            return !is_null( $val );
        } );
        if ( isset( $inputs['password'] ) ) {
            $inputs['password'] = Hash::make( $inputs['password'] );
        }


        if ( $user->update( $inputs ) ) {
            return $this->response->array( [
                'success' => true,
                'message' => "{$user->name} updated Successfully",
                'data'    => $user->toArray()
            ] );
        } else {
            throw new Dingo\Api\Exception\StoreResourceFailedException( 'Could not update User.', $user->getErrors() );
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
    public function destroy( $bank_id, $user_id ) {
        $user = User::where( 'bank_id', $bank_id )->where( 'id', $user_id )->first();
        if ( $user->delete() ) {
            return $this->response->array( [ 'success' => true ] );
        }
        throw new Symfony\Component\HttpKernel\Exception\NotFoundHttpException( "User not found." );
    }

    public function login() {
        $credentials = array(
            'username'   => Input::get( 'username' ),
            'password'   => Input::get( 'password' ),
            'deleted_at' => null
        );
        if ( Auth::attempt( $credentials ) ) {
            $authToken   = AuthToken::create( Auth::user() );
            $publicToken = AuthToken::publicToken( $authToken );

            return Response::json( array(
                'success' => true,
                'message' => 'Logged In',
                'data'    => Auth::user()->toArray(),
                'token'   => $publicToken
            ) );
        }

        return Response::json( array( 'success' => false, 'message' => 'Incorrect Username or Password' ) );
    }

    public function logout() {
        Auth::logout();

        return Response::json( array( 'success' => true, 'message' => 'Logged Out' ) );
    }

}
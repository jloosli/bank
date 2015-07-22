<?php

use AvantiDevelopment\JrBank\Models\Bank;
use AvantiDevelopment\JrBank\Models\User;
use Illuminate\Support\Facades\Input;

class BankController extends BaseController {
//    use Dingo\Api\Routing\ControllerTrait;
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $user  = API::user();
        $banks = new AvantiDevelopment\JrBank\Models\Bank();
        if ( Input::get( 'show_deleted' ) === 'true' ) {
            $banks = $banks->withTrashed();
        }
        if ( $user && $user->user_type !== 'super-admin' ) {
            $banks = $banks->where( 'id', $user->bank_id );
        }

        return $banks->get();
    }


    /**
     * Store a newly created resource in storage.
     * @route POST /api/banks
     *
     * @return Response
     */
    public function store() {

        $bank              = new Bank();
        $payload = Input::all();
        try {
            $bank->name        = $payload['bank']['name'];
            $bank->slug        = Input::get( 'slug', Str::slug( $bank->name ) );
            $bank->password    = Hash::make( Input::get( 'password', 'password' ) );
            $bank->interest    = $payload['bank']['interest'] ? $payload['bank']['interest'] : 0;
            $bank->compounding = Input::get( 'bank.compounding', 'monthly' );
        } catch ( ErrorException $e ) {
            throw new Dingo\Api\Exception\StoreResourceFailedException( 'Could not create Bank. Missing parameters.', [ $e->getMessage() ] );
        }
        DB::transaction( function () use ( $bank, $payload ) {
            if ( !$bank->save() ) {
                throw new Dingo\Api\Exception\StoreResourceFailedException( 'Could not create Bank.', $bank->getErrors() );
            }

            if ( !empty( $payload['users'] ) ) {
                foreach ( $payload['users'] as $user ) {
                    $u            = new User();
                    $u->name      = $user['name'];
                    $u->password  = Hash::make( $user['password'] );
                    $u->username  = $user['username'];
                    $u->email     = $user['email'];
                    $u->user_type = $user['user_type'];
                    $bank->users()->save( $u );
                }
            }
        } );

        return $this->response->array( [
            'success' => true,
            'message' => "{$bank->name} saved Successfully",
            'data' => [
                'bank'  => $bank->toArray(),
                'users' => $bank->users()
            ]
        ] );
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show( $id ) {
        if ( Input::get( 'show_deleted' ) === 'true' ) {

            $trashed = Bank::withTrashed()->find( $id );

            return $trashed;
        }

        try {
            return Bank::findOrFail( $id );
        } catch ( Illuminate\Database\Eloquent\ModelNotFoundException $e ) {
            throw new Symfony\Component\HttpKernel\Exception\NotFoundHttpException( "Bank not found." );
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function update( $id ) {
        if ( Input::get( 'undelete' ) === 'true' ) {
            Bank::withTrashed()->where( 'id', $id )->restore();
        }
        $bank = Bank::find( $id );
        if ( !$bank ) {
            throw new Symfony\Component\HttpKernel\Exception\NotFoundHttpException( "Bank not found." );
        }
        $inputs = Input::only( $bank->getFillable() );
        $inputs = array_filter( $inputs, function ( $val ) {
            return !is_null( $val );
        } );
        //@todo remove this when this isn't required in the database
        $inputs['password'] = 'password';
        $bank->update( $inputs );


        if ( $bank->save() ) {
            return $this->response->array( [
                'success' => true,
                'message' => "{$bank->name} updated Successfully",
                'data'    => $bank->toArray()
            ] );
        } else {
            throw new Dingo\Api\Exception\StoreResourceFailedException( 'Could not update Bank.', $bank->errors() );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy( $id ) {
        if ( Bank::destroy( $id ) ) {
            return $this->response->array( [ 'success' => true ] );
        }
        throw new Symfony\Component\HttpKernel\Exception\NotFoundHttpException( "Bank not found." );
    }

}

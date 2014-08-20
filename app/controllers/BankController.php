<?php

use AvantiDevelopment\JrBank\Models\Bank;
use Illuminate\Support\Facades\Input;

class BankController extends BaseController {

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
            $banks = $banks->where('id',$user->bank_id);
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
        $bank->name        = Input::get( 'name' );
        $bank->slug        = Input::get( 'slug', Str::slug( $bank->name ) );
        $bank->password    = Hash::make( Input::get( 'password' ) );
        $bank->interest    = Input::get( 'interest', 0 );
        $bank->compounding = Input::get( 'compounding' );

        if ( $bank->updateUniques() ) {
            return Response::api()->withArray( [
                'success' => true,
                'message' => "{$bank->name} saved Successfully",
                'data'    => $bank->toArray()
            ] );
        } else {
            throw new Dingo\Api\Exception\StoreResourceFailedException( 'Could not create Bank.', $bank->errors() );
        }
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
        if ( Input::get( 'undelete' ) == 'true' ) {
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
        $bank->update( $inputs );


        if ( $bank->updateUniques() ) {
            return Response::api()->withArray( [
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
            return Response::api()->withArray( [ 'success' => true ] );
        }
        throw new Symfony\Component\HttpKernel\Exception\NotFoundHttpException( "Bank not found." );
    }

}

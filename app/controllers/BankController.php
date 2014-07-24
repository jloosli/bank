<?php

class BankController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        return Bank::all();
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


//        $rules             = Bank::$rules;
//        $rules['password'] = "required|min:3";
        if ( $bank->updateUniques() ) {
            return Response::api()->withArray([
                'success' => true,
                'message' => "{$bank->name} saved Successfully",
                'data'    => $bank->toArray()
            ]);

        } else {
            throw new Dingo\Api\Exception\StoreResourceFailedException('Could not create Bank.', $bank->errors());
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
        return Bank::findOrFail( $id );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function update( $id ) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy( $id ) {
        Bank::destroy( $id );

        return true;
    }

}

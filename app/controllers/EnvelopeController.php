<?php

use AvantiDevelopment\JrBank\Models\Envelope;
use AvantiDevelopment\JrBank\Models\User;

class EnvelopeController extends BaseController {

    /**
     * Display a listing of a user's envelopes.
     *
     * @param $bank_id
     * @param $user_id
     *
     * @return Response
     */
    public function index( $bank_id, $user_id ) {
        return Envelope::where( 'user_id', $user_id )->get();
    }

    /*
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
    public function store( $bank_id, $user_id ) {
        $user = User::where( 'bank_id', $bank_id )->where( 'id', $user_id )->first();
        if ( !$user ) {
            throw new Symfony\Component\HttpKernel\Exception\NotFoundHttpException(
                'User not found.'
            );
        }
        $envelopes = Input::get('envelopes');
        foreach($envelopes as $env) {
            if(empty($env['id'])) {
                $envelope = new Envelope();
            } else {
                $envelope = $user->envelopes()->find($env['id']);
            }
            $envelope->user_id       = $env['user_id'];
            $envelope->name          = $env['name'];
            $envelope->percent       = $env['percent'] ? $env['percent'] : 0;
            $envelope->goal          = $env['goal'];
            $envelope->balance       = 0;
            $envelope->goal_date     = $env['goal_date'];
            $envelope->default_spend = $env['default_spend'] ? 1 : 0;
            $user->envelopes()->save( $envelope );
        }

            return Response::api()->withArray( [
                'success' => true,
                'message' => "Envelopes saved",
                'data'    => $user->envelopes()->get()->toArray()
            ] );
//        } else {
//            throw new Dingo\Api\Exception\StoreResourceFailedException( 'Could not create Envelope.', $envelope->errors() );
//        }


    }

    /**
     * Display the specified resource.
     *
     * @param $bank_id
     * @param $user_id
     * @param $envelope_ids
     *
     * @return Response
     */
    public function show( $bank_id, $user_id, $envelope_ids ) {
        $envelope_ids = explode( ',', $envelope_ids );
        if ( Input::get( 'show_deleted' ) === 'true' ) {
            $envelopes = Envelope::withTrashed();
        } else {
            $envelopes = new Envelope();
        }
        $envelopes = $envelopes->where( 'user_id', '=', $user_id )
                               ->whereIn( 'id', $envelope_ids )->get();
        if ( count( $envelopes ) === 0 ) {
            throw new Symfony\Component\HttpKernel\Exception\NotFoundHttpException(
                'Envelopes not found.'
            );
        }

        return $envelopes;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $bank_id
     * @param int $user_id
     */
    public function update( $bank_id, $user_id, $env_id ) {
        $user = User::where( 'bank_id', $bank_id )->where( 'id', $user_id )->first();
        if ( !$user ) {
            throw new Symfony\Component\HttpKernel\Exception\NotFoundHttpException(
                'User not found.'
            );
        }

        if ( Input::get( 'undelete' ) == 'true' ) {
            Envelope::withTrashed()->where( 'id', $env_id )->where( 'user_id', $user_id )->restore();
        }
        $envelope = $user->envelopes()->find( $env_id );
        if ( !$envelope ) {
            throw new Symfony\Component\HttpKernel\Exception\NotFoundHttpException(
                'Envelope not found.'
            );
        }
        $inputs = Input::only( $envelope->getFillable() );
        $inputs = array_filter( $inputs, function ( $val ) {
            return !is_null( $val );
        } );
        $envelope->update( $inputs );


        if ( $envelope->save() ) {
            return Response::api()->withArray( [
                'success' => true,
                'message' => "{$envelope->name} updated Successfully",
                'data'    => $envelope->toArray()
            ] );
        } else {
            throw new Dingo\Api\Exception\StoreResourceFailedException( 'Could not update Bank.', $envelope->errors() );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $bank_id
     * @param $user_id
     * @param $envelope_id
     *
     * @return Response
     */
    public function destroy( $bank_id, $user_id, $envelope_id ) {
        // @todo Check to make sure the envelope balance is $0
        $envelope = $this->show( $bank_id, $user_id, $envelope_id );
        if ( !$envelope[0] ) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException( 'Envelope not found' );
        }
        $envelope = $envelope[0];
        if ( (float) $envelope->balance == 0 && $envelope->delete() ) {
            return Response::api()->withArray( [ 'success' => true ] );
        }
        throw new \Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException( 'Envelope is not empty' );
    }

}

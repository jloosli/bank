<?php

use AvantiDevelopment\JrBank\Models\Transaction;
use AvantiDevelopment\JrBank\Models\User;
use AvantiDevelopment\JrBank\Models\EnvelopeTransaction;
use AvantiDevelopment\JrBank\Models\Envelope;

class TransactionController extends BaseController {
    use Dingo\Api\Routing\ControllerTrait;
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index( $bank_id, $user_id ) {
        $transactions = Transaction::with( 'envelope_transaction' )
            ->where( 'user_id', $user_id )
            ->orderBy('created_at', 'desc')
            ->paginate();

        return $transactions;
    }

    public function user( $id ) {
        $transactions = Transaction::with( 'envelope_transaction' )
            ->where( 'user_id', '=', $id )
            ->orderBy('created')
            ->get();

        return Response::json( $transactions );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param int $bank_id
     * @param int $user_id
     *
     * @return Response
     */
    public function store( $bank_id, $user_id ) {
        $user = User::where( 'bank_id', $bank_id )->where( 'id', $user_id )->first();
        if ( !$user ) {
            throw new Symfony\Component\HttpKernel\Exception\NotFoundHttpException( "User not found" );
        }
        $mainTransaction       = Input::get( 'transaction' );
        $envelope_transactions = $mainTransaction['envelope_transactions'];
        $transaction_check     = array_reduce(
            $envelope_transactions,
            function ( $item1, $item2 ) {
                return $item1 - $item2['amount'];
            },
            $mainTransaction['amount'] );
        if ( round($transaction_check,2) != 0 ) {
            throw new Symfony\Component\HttpKernel\Exception\BadRequestHttpException( "Envelope Transactions don't match up" );
        }

        try {
            DB::beginTransaction();
            // Update the user balance
            $user->balance += $mainTransaction['amount'];
            $user->save();

            // Enter the envelope transaction
            $transaction              = new Transaction();
            $transaction->amount      = $mainTransaction['amount'];
            $transaction->description = $mainTransaction['description'];
            $user->transactions()->save( $transaction );

            // Save the envelope Transactions
            foreach ( $envelope_transactions as $et ) {
                $theSubTransaction              = new EnvelopeTransaction();
                $theSubTransaction->amount      = $et['amount'];
                $theSubTransaction->envelope_id = $et['envelope_id'];
                $transaction->envelope_transaction()->save( $theSubTransaction );

                // Update the existing envelope balance.
                $envelope = Envelope::findorFail( $et['envelope_id'] );
                $envelope->balance += $et['amount'];
                $envelope->save();
            }
            DB::commit();
            $trans = $transaction->toArray();
            $trans['envelope_transaction'] = $transaction->envelope_transaction()->get()->toArray();
            return Response::api()->withArray( array(
                'success' => true,
                'transaction' => $trans
            ) );
        } catch ( Exception $e ) {
            DB::rollBack();
            var_dump( $e );
            throw new Dingo\Api\Exception\StoreResourceFailedException( "Couldn't save Transaction" );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $bank_id
     * @param $user_id
     * @param $trans_ids
     *
     * @return Transaction
     */
    public function show( $bank_id, $user_id, $trans_ids ) {
        $trans_ids    = explode( ',', $trans_ids );
        $transactions = Transaction::with( 'envelope_transaction' )
                                   ->where( 'user_id', $user_id )
                                   ->whereIn( 'id', $trans_ids )
                                   ->get();
        if ( count( $transactions ) === 0 ) {
            throw new Symfony\Component\HttpKernel\Exception\NotFoundHttpException( "Transaction(s) not found" );
        }

        return $transactions;
    }

}

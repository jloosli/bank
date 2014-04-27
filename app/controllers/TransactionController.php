<?php

class TransactionController extends BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        return Input::get('user');
    }

    public function user($id) {
        $transactions = Transaction::with('envelope_transaction')->where('user_id','=',$id)->get();

        return Response::json($transactions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        return View::make('transactions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        try {
            DB::transaction(function () {
                // Update the user balance
                $user = User::findOrFail(Input::get('user_id'));
                $user->balance+=Input::get('amount');
                $user->save();

                // Pull out the envelope_transactions and save the overall transaction
                $theTransaction = Input::all();
                $theEnvelopeTransactions = $theTransaction['envelope_transaction'];
                unset($theTransaction['envelope_transaction']);
                $transaction = new Transaction($theTransaction);
                $transaction->save();

                // Save the envelope Transactions
                foreach ($theEnvelopeTransactions as $et) {
                    $theSubTransaction = new EnvelopeTransaction($et);
                    $transaction->envelope_transaction()->save($theSubTransaction);

                    // Update the existing envelope balances.
                    $envelope = Envelope::findorFail($et['envelope_id']);
                    $envelope->balance += $et['amount'];
                    $envelope->save();
                }
            });
            return Response::json(array('success'=>true));

        } catch (Exception $e) {
            return Response::json(array('success' => false, 'message' => $e->getMessage()));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id) {
        return View::make('transactions.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id) {
        return View::make('transactions.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

}

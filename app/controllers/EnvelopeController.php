<?php

class EnvelopeController extends BaseController {

    /**
     * Display a listing of a user's envelopes.
     *
     * @param $bank_id
     * @param $user_id
     *
     * @return Response
     */
	public function index($bank_id, $user_id)
	{
        return Envelope::where('user_id', $user_id)->get();
	}

    /*
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        if(Input::get('id')) {
            $envelope = Envelope::findOrFail(Input::get('id'));
        } else {
            $envelope = new Envelope();
            $envelope->user_id = Input::get('user_id');
        }
        $envelope->name = Input::get('name');
        $envelope->percent = Input::get('percent',0);
        $envelope->goal = Input::get('goal');
        $envelope->goal_date = Input::get('goal_date');
        $envelope->is_active = Input::get('is_active') ? 1 : 0;
        $envelope->default_spend = Input::get('default_spend') ? 1 : 0;

        $envelope->save();
        return Response::json(array('success'=>true, 'envelope'=>$envelope->toArray()));

    }

    /**
     * Display the specified resource.
     *
     * @param $bank_id
     * @param $user_id
     * @param $envelope_id
     *
     * @return Response
     */
	public function show($bank_id, $user_id, $envelope_id)
	{
        return Envelope::where('user_id', '=', $user_id)
            ->where('id', $envelope_id)->get();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
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
	public function destroy($bank_id, $user_id, $envelope_id)
	{
		// @todo Check to make sure the envelope balance is $0
        $envelope = $this->show($bank_id,$user_id,$envelope_id);
        if (!envelope) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Envelope not found');
        }
        if((float) $envelope->balance === 0) {
            $envelope->delete();
            return true;
        }
        throw new \Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException('Envelope is not empty');
	}

}

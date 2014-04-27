<?php

class EnvelopeController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        return View::make('envelopes.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('envelopes.create');
	}

	/**
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
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $envelopes = Envelope::where('user_id', '=', $id)->get();
        return Response::json($envelopes);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        return View::make('envelopes.edit');
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
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}

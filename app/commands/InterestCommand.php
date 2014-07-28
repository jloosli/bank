<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class InterestCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'bank:interest';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Calculate interest.';

    /**
     * Create a new command instance.
     *
     * @param $bank
     *
     * @return \InterestCommand
     */
	public function __construct($bank)
	{
        $this->bank = $bank;
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$banks = $this->bank->with('users')->get();
        foreach($banks as $bank) {
            $interest = $bank->interest / ($bank->compounding == 'monthly' ? 12 : 1);
            $this->line("{$bank->name} compounding {$bank->compounding} at {$bank->interest}% interest");
            foreach($bank->users as $user) {
                $this->info($user);
                $transaction = new Transaction;
                $transaction->user_id = $user->id;
                $transaction->description = sprintf("%s interest of %0.0f%%. Negative balances double.",ucwords("{$bank->compounding}"),$bank->interest);
                $transaction->amount = 0;
                $envelopeTransactions = [];
                foreach($user->envelopes as $envelope) {
                    $this->info($envelope);
                    $interestAmount = round(($envelope->balance > 0 ? $interest : 2 * $interest) * $envelope->balance /100, 2);
                    $this->line("{$envelope->name} Interest: " . $interestAmount);
                    $envelopeTrans = new EnvelopeTransaction;
                    $envelopeTrans->envelope_id = $envelope->id;
                    $envelopeTrans->amount = $interestAmount;
                    $envelopeTransactions[] = $envelopeTrans;
                    $transaction->amount += $envelopeTrans->amount;
                    $envelope->balance += $envelopeTrans->amount;
                    $envelope->save();
                    $this->line("Updated {$user->name} envelope {$envelope->name}. New balance: {$envelope->balance}.");
                }
                $transaction->save();
                $this->info($transaction);
                foreach($envelopeTransactions as $et) {
                    $transaction->envelope_transaction()->save($et);
                }
                $user->balance += $transaction->amount;
                $user->save();
                $this->line("Updated {$bank->name}:{$user->name}. New Balance: {$user->balance}");
            }
        }

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
//			array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
//			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
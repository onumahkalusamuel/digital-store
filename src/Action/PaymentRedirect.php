<?php

namespace App\Action;

use App\Action\Api\QuickBuy;
use App\Helpers\SendMail;
use App\Interfaces\Payment\AccountTopUpPaymentInterface;
use App\Interfaces\Payment\QuickBuyPaymentInterface;
use App\Repositories\PaymentsRepository;
use App\Repositories\TrailLogRepository;
use App\Repositories\UsersRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Smarty as View;

class PaymentRedirect
{

    protected $user;
    protected $payments;
    protected $quickBuyPayment;
    protected $quickBuy;
    protected $view;
    protected $trailLog;
    protected $sendMail;
    protected $accountTopUpPayment;

    public function __construct(
        UsersRepository $user,
        PaymentsRepository $payments,
        QuickBuyPaymentInterface $quickBuyPayment,
        AccountTopUpPaymentInterface $accountTopUpPayment,
        QuickBuy $quickBuy,
        View $view,
        TrailLogRepository $trailLog,
        SendMail $sendMail
    ) {
        $this->user = $user;
        $this->payments = $payments;
        $this->quickBuyPayment = $quickBuyPayment;
        $this->accountTopUpPayment = $accountTopUpPayment;
        $this->quickBuy = $quickBuy;
        $this->view = $view;
        $this->trailLog = $trailLog;
        $this->sendMail = $sendMail;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        $args
    ): ResponseInterface {

        $transaction_id = $args['transaction_id'];
        $message = "";

        // fetch payment details from database
        $payment = $this->payments->find(['params' => ['transaction_id' => $transaction_id]]);

        // check if transaction exists or already processed
        if (empty($message) && empty($payment->id)) {
            $message = "Transaction details not found. Please contact support.";
        }
        if (empty($message) && $payment->status != "pending") {
            $message = "Transaction processed previously.";
        }

        // verify payment from payment service provider
        if (empty($message)) {
            if ($payment->type == "quickbuy") {
                $verify = $this->quickBuyPayment->paymentVerification($payment->transaction_id);
            }
            if ($payment->type == "accounttopup") {
                $verify = $this->accountTopUpPayment->paymentVerification($payment->transaction_id);
            }

            if (empty($verify['success'])) {
                $message = $verify['message'] ?? "Payment verification failed. Try again later.";
            }

            // be sure they paid in the correct amount
            if (empty($message) && $verify['amount'] < $payment->amount) {
                $message = "Invalid amount deposited. Please contact support.";
            }
        }

        // if it is quickbuy, process as quickbuy and complete transaction
        if (empty($message) && $payment->type == "quickbuy") {
            $processTransaction = $this->quickBuy->processTransaction($transaction_id);

            if (empty($processTransaction['success'])) {
                $message = $processTransaction['message'];
            }
        }

        // if account topup, add the paid amount to user's account
        if (empty($message) && $payment->type == "accounttopup") {
            // fetch the user
            $user = $this->user->readSingle(['id' => $payment->user_id]);

            if (empty($user->id)) {
                $message = "User account not found.";
            }

            if (empty($message)) {
                $updateUserBalance = $this->user->update([
                    'id' => $user->id,
                    'balance' => $user->balance + $verify['amount']
                ]);

                if (empty($updateUserBalance)) {
                    $message = "Account funding not completed. Please try again later.";
                }

                // notify user by email and record in traillog
                if (empty($message)) {
                    $this->trailLog->create([
                        'data' => [
                            'user_id' => $user->id,
                            'log_type' => 'accounttopup',
                            'service_provider' => "account",
                            'destination' => "Account Balance",
                            'amount' => $verify['amount'],
                            'description' => "Account TopUp",
                            'trans_ref' => $payment->transaction_id,
                            'balance_before' => $user->balance,
                            'balance_after' => $user->balance + $verify['amount'],
                            'loyalty_points' => 0,
                            'platform_id' => $payment->service_id
                        ]
                    ]);

                    // notify the user of this transaction
                    $this->sendMail->sendTransactionLog([
                        'transaction' => "Account TopUp",
                        'trans_ref' => $payment->transaction_id,
                        'amount' => $verify['amount'],
                        'destination' => $user->email,
                        'balance_before' => $user->balance,
                        'balance_after' => $user->balance + $verify['amount'],
                        'loyalty_points' => 0,
                    ], $user->email);
                }
            }
        }

        // mark payment and completed
        if (empty($message)) {
            $updateStatus = $this->payments->update(['id' => $payment->id, 'data' => ['status' => 'completed']]);
        }
        // redirect user to page with transaction completed successfully

        if (empty($message)) {
            $message = "Transaction processed successfully.";
            $success = true;
        } else {
            $success = false;
        }

        $this->view->assign('message', $message);
        $this->view->assign('success', $success);
        $this->view->display('public/pages/payment-redirect.tpl');

        return $response;
    }
}

<?php

namespace App\controllers;


use App\core\App;
use App\core\Controller;
use App\core\AppException;

class SiteController extends Controller
{
    /**
     * Home
     * @throws AppException
     */
    public function actionIndex(): void
    {
        if (App::$instance->user->isGuest()) {
            $this->redirect('site/login');
        }

        $user = App::$instance->user->model;
        $accountHistory = $user->getAccountHistory();
        $userBalance = $user->getBalance();

        $this->render('index', [
            'user'           => $user,
            'accountHistory' => $accountHistory,
            'userBalance'    => $userBalance,
        ]);
    }

    /**
     * Login
     * @throws AppException
     */
    public function actionLogin(): void
    {
        if (!App::$instance->user->isGuest()) {
            $this->goHome();
        }

        $action = isset($_POST['action']);
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $error = '';
        if ($action) {
            App::$session->open(true);
            $auth = App::$instance->user->auth($username, $password);
            if ($auth) {
                $this->goHome();
            } else {
                App::$session->destroy();
                $error = 'Incorrect username or password';
            }
            App::$session->writeClose();
        }

        $this->render('login', [
            'error'    => $error,
            'username' => $username,
        ]);
    }

    /**
     * Logout
     */
    public function actionLogout()
    {
        App::$session->open(true);
        App::$session->destroy();
        $this->goHome();
    }

    /**
     * Pay
     * @throws AppException
     */
    public function actionPay()
    {
        if (App::$instance->user->isGuest()) {
            $this->redirect('site/login');
        }

        $error = '';
        $pay = false;
        $action = isset($_POST['action']);
        $value = isset($_POST['money']) ? round($_POST['money'], 2) : 0;
        $user = App::$instance->user->model;

        if ($action) {
            if ($user->getBalance() >= $value) {
                $pay = $user->createPay($value);
                if (!$pay) {
                    $error = 'An error occurred during the transaction';
                }
            } else {
                $error = 'You do not have enough money';
            }
        } else {
            $this->goHome();
        }

        $this->render('pay', [
            'user'  => $user,
            'value' => $value,
            'error' => $error,
            'pay'   => $pay,
        ]);
    }

    /**
     * Confirm pay
     * @throws AppException
     */
    public function actionConfirmPay(): void
    {
        if (App::$instance->user->isGuest()) {
            $this->redirect('site/login');
        }

        $action = isset($_POST['action']);
        $payHash = $_POST['pay-hash'] ?? null;
        $user = App::$instance->user->model;

        if ($action) {
            $pay = $user->pay($payHash);
            if (!$pay) {
                throw new AppException('An error occurred during the transaction');
            }
            $this->redirect('site/successPay');
        } else {
            $this->goHome();
        }
    }

    /**
     * Success pay page
     */
    public function actionSuccessPay(): void
    {
        $this->render('successPay');
    }
}
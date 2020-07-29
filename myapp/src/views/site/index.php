<?php

use App\core\Route;

/**
 * @param float $balance
 * @return string
 */
function getBalanceClass($balance)
{
    return $balance > 0 ? 'text-success' : 'text-danger';
}

?>
<h1>Hello, <?php echo $user->username; ?>!</h1>

<h2>Your balance: <span class="<?php echo getBalanceClass($userBalance); ?>"><?php echo $userBalance; ?> $</span></h2>
<div class="row">
    <form class="col-sm-3" action="<?php echo Route::createUrl('site/pay'); ?>" method="POST">
        <div class="form-group">
            <label for="inputMoney">How many money withdraw?</label>
            <input type="number" step="0.01" min="0.01" max="9999999" name="money" id="inputMoney" class="form-control"
                   placeholder="0.00 $" required autofocus>
        </div>
        <button name="action" class="btn btn-lg btn-primary btn-block">Ok</button>
    </form>

    <hr/>


    <div class="col-sm-7">
        <?php if (!empty($accountHistory)) { ?>
            <h3>Account history</h3>
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Value</th>
                    <th>Datatime</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($accountHistory as $item) { ?>
                    <tr>
                        <td><?php echo $item['id']; ?></td>
                        <td class="<?php echo getBalanceClass($item['value']); ?>"><?php echo $item['value']; ?> $</td>
                        <td><?php echo $item['datetime']; ?></td>
                        <td><?php echo $item['status']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p class="text-center">No account history</p>
        <?php } ?>
    </div>
</div>
<?php

namespace App\Http\Controllers\admin\wallet;

use App\Http\Controllers\common\AdminController;
use App\Models\V1\WalletAddress;

/**
 * @ControllerAnnotation(title="wallet_address")
 */
class AddressController extends AdminController
{

    public function initialize()
    {
        parent::initialize();
        $this->model = new WalletAddress();
        $this->assign(
            [
                'currency_type' => ['USDT', 'BTC'],
                'protocol_type' => ['ERC20', 'TRC20', 'BSC'],
            ]
        );
    }

}

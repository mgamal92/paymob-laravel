<?php

declare(strict_types=1);

namespace MG\Paymob;

use MG\Paymob\Traits\CardPayment;
use MG\Paymob\Traits\PaymentFlow;
use MG\Paymob\Traits\WalletPayment;

class Paymob
{
    use PaymentFlow;
    use CardPayment;
    use WalletPayment;
}

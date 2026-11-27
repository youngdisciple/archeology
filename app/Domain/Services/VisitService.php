<?php

namespace App\Domain\Services;

use App\Domain\Models\BaseModel;
use App\Helpers\Core\Result as Result;
use Frostybee\Valicomb\Validator;

class VisitService extends BaseService
{
    // number of visitors INT
    // price FLOAT
    // currency STRING
    // discount -> input = decimal , output = % FLOAT
    // donation  FLOAT

    // (number of visitors * (ticket price - discount)) + donation

    function calculateVisit(
        int $visitors,
        float $ticketPrice,
        float $discount,
        float $donation,
        string $currency = 'CAD'
    ) : Result {
        $discountedTicket = ($ticketPrice / 100) * $discount;
        $total = $visitors * ($ticketPrice - $discountedTicket) + $donation;

        if ($currency == "USD"){
            $usdTotal = $total * 0.7;
            $total = $usdTotal;
        }

        return Result::success("Your visit's total is: ", $total);
    }
}

<?php

namespace LukePOLO\LaraCart\Coupons;

use LukePOLO\LaraCart\Contracts\CouponContract;
use LukePOLO\LaraCart\LaraCart;
use LukePOLO\LaraCart\Traits\CouponTrait;

/**
 * Class Fixed.
 */
class Fixed implements CouponContract
{
    use CouponTrait;

    public $code;
    public $value;

    /**
     * Fixed constructor.
     *
     * @param $code
     * @param $value
     * @param array $options
     */
    public function __construct($code, $value, $options = [])
    {
        $this->code = $code;
        $this->value = $value;

        $this->setOptions($options);
    }

    /**
     * Gets the discount amount.
     *
     * @param $throwErrors boolean this allows us to capture errors in our code if we wish,
     * that way we can spit out why the coupon has failed
     *
     * @return string
     */
    public function discount($throwErrors = false)
    {
        if (config('laracart.discountOnFees', false)) {
            $total = app(LaraCart::SERVICE)->subTotal(false) + app(LaraCart::SERVICE)->feeTotals(false) - $this->value;
        } else {
            $total = app(LaraCart::SERVICE)->subTotal(false) - $this->value;
        }

        if ($total < 0) {
            return app(LaraCart::SERVICE)->subTotal(false);
        }

        return $this->value;
    }

    /**
     * Displays the value in a money format.
     *
     * @param null $locale
     * @param null $internationalFormat
     *
     * @return string
     */
    public function displayValue($locale = null, $internationalFormat = null, $format = true)
    {
        return LaraCart::formatMoney(
            $this->discount(),
            $locale,
            $internationalFormat,
            $format
        );
    }
}

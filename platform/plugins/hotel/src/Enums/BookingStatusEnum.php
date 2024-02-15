<?php

namespace Botble\Hotel\Enums;

use Botble\Base\Supports\Enum;
use Html;

/**
 * @method static BookingStatusEnum PENDING()
 * @method static BookingStatusEnum PROCESSING()
 * @method static BookingStatusEnum COMPLETED()
 * @method static BookingStatusEnum CANCELLED()
 */
class BookingStatusEnum extends Enum
{
    public const PENDING = 'pending';
    public const PROCESSING = 'processing';
    public const COMPLETED = 'completed';
    public const CANCELLED = 'cancelled';

    /**
     * @var string
     */
    public static $langPath = 'plugins/hotel::booking.statuses';

    /**
     * @return string
     */
    public function toHtml()
    {
        switch ($this->value) {
            case self::PENDING:
                return Html::tag('span', self::PENDING()->label(), ['class' => 'label-warning status-label'])
                    ->toHtml();
            case self::PROCESSING:
                return Html::tag('span', self::PROCESSING()->label(), ['class' => 'label-info status-label'])
                    ->toHtml();
            case self::COMPLETED:
                return Html::tag('span', self::COMPLETED()->label(), ['class' => 'label-success status-label'])
                    ->toHtml();
            case self::CANCELLED:
                return Html::tag('span', self::CANCELLED()->label(), ['class' => 'label-danger status-label'])
                    ->toHtml();
            default:
                return parent::toHtml();
        }
    }
}

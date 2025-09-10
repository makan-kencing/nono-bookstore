<?php

namespace App\DTO\Request;

use App\DTO\DTO;
use App\Entity\Order\Shipment;
use App\Exception\BadRequestException;
use DateTime;
use Throwable;
use function App\Utils\array_get;

readonly class ShipmentUpdateDTO extends DTO
{
    public function __construct(
        public ? string $id=null,
        public ? string $order_id=null,
        public ? DateTime $ready_at=null,
        public ? DateTime $shipped_at=null,
        public ? DateTime $arrived_at=null,
        public ? DateTime $updated_at=null,
    ){
    }
    /**
     * @inheritDoc
     */
        public static function jsonDeserialize(mixed $json): ShipmentUpdateDTO{
            assert(is_string($json));
            try {
                return new self(
                    array_get($json,'id'),
                    array_get($json,'order_id'),
                    array_get($json,'ready_at'),
                    array_get($json,'shipped_at'),
                    array_get($json,'arrived_at'),
                    array_get($json,'updated_at'),
                );
            }catch (Throwable){
                throw new BadRequestException();
            }
        }

        public function update(Shipment $shipment): void
        {
            if ($this->updated_at){
               $shipment->updated_at = $this->updated_at;
            }

            if ($this->shipped_at){
                $shipment->shipped_at = $this->shipped_at;
            }

            if ($this->arrived_at){
                $shipment->arrived_at = $this->arrived_at;
            }

            if ($this->ready_at){
                $shipment->ready_at = $this->ready_at;
            }
        }

}

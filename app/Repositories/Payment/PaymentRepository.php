<?php

namespace App\Repositories\Payment;

use App\Enums\PaymentType;
use App\Models\Payment;
use App\Repositories\BaseRepository;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{

    public function getModel()
    {
        return Payment::class;
    }

    public function getAll($paginate = null, $with = [], $filter = null, $sort = null)
    {
        $query = $this->model->query();

        $query->with(['order', 'user']);

        if ($filter) {
            $filter = json_decode($filter, true);

            $transaction_type = $filter['transaction_type'] ?? null;

            if ($transaction_type) {
                switch ($transaction_type) {
                    case PaymentType::DEPOSIT: // nap tien
                        $query->where('transaction_type', PaymentType::DEPOSIT);
                        break;
                    case PaymentType::REFUND: // hoan tien
                        $query->where('transaction_type', PaymentType::REFUND);
                        break;
                    default:
                        break;
                }
            }
        }

        if ($sort) {

            switch ($sort) {
                case 'asc': // sap xep theo thu tu tang dan
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'desc': // sap xep theo thu tu giam dan
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    break;
            }
        }

        if ($paginate) {
            return $query->paginate($paginate);
        }

        return $query->get();
    }

    public function getMyPayment($paginate = null, $with = [], $filter = null, $sort = null, $user_id)
    {
        $query = $this->model->query();

        $query->where('user_id', $user_id);

        $query->with(['order', 'user']);

        if ($filter) {
            $filter = json_decode($filter, true);

            $transaction_type = $filter['transaction_type'] ?? null;

            if ($transaction_type) {
                switch ($transaction_type) {
                    case PaymentType::DEPOSIT: // nap tien
                        $query->where('transaction_type', PaymentType::DEPOSIT);
                        break;
                    case PaymentType::REFUND: // hoan tien
                        $query->where('transaction_type', PaymentType::REFUND);
                        break;
                    default:
                        break;
                }
            }
        }

        if ($sort) {
            switch ($sort) {
                case 'new': // sap xep theo thu tu tang dan
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'old': // sap xep theo thu tu giam dan
                    $query->orderBy('created_at', 'asc');
                    break;
                default:
                    break;
            }
        }

        if ($paginate) {
            return $query->paginate($paginate);
        }

        return $query->get();
    }
}

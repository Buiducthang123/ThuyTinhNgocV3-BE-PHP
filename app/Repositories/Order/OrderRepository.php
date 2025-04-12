<?php

namespace App\Repositories\Order;

use App\Mail\OrderStatus;
use App\Enums\OrderStatus as EnumsOrderStatus;
use App\Models\Order;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function getModel()
    {
        return Order::class;
    }

    public function getAll($paginate = null, $with = [], $filter = null, $sort = null)
    {
        $query = $this->model->with($with);

        if ($filter) {

            $filter = json_decode($filter, true);

            $status = $filter['status'] ?? null;

            if ($status) {
                $query->where('status', $status);
            }
        }

        if($sort){
            switch ($sort) {
                case 'all': // Tất cả
                    break;
                case 'new': // Mới nhất
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'old': // Cũ nhất
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'price_asc': // Tổng tiền tăng dần
                    $query->orderBy('total_price', 'asc');
                    break;
                case 'price_desc': // Tổng tiền giảm dần
                    $query->orderBy('total_price', 'desc');
                    break;

            }
        }

        if ($paginate) {
            return $query->paginate($paginate);
        }

        return $query->get();
    }

    public function show($id, $with = [])
    {
        if ($with) {
            return $this->model->with($with)->find($id);
        }
        return $this->model->find($id);
    }

    public function myOrder($paginate = null, $with = [], $filter = null, $sort = null){

        $user = Auth::user();

        if (!$user) {
            throw new \Exception('Không tìm thấy người dùng', 404);
        }

        $query = $this->model->where('user_id', $user->id)->with($with);

        if ($filter) {

            $filter = json_decode($filter, true);

            $status = $filter['status'] ?? null;

            if ($status) {
                $query->where('status', $status);
            }
        }

        if($paginate){
            return $query->paginate($paginate);
        }

        return $query->get();

    }

    public function sendMailOrderStatus($order, $user)
    {
        $mail = new OrderStatus($order, $user);

        Mail::to($user->email)->queue($mail);
    }

    public function getTotalRevenue($start_date = null, $end_date = null)
    {
        $query = $this->model->where('status', EnumsOrderStatus::DELIVERED);

        if ($start_date && $end_date) {
            $query->whereBetween('created_at', [$start_date, $end_date]);
        }

        return $query->sum('total_amount');
    }

    public function getRevenueByTime($start_date, $end_date, $optionShow = 'all')
    {
        $query = $this->model->where('status', EnumsOrderStatus::DELIVERED);

        $timeColumn = null;

        switch ($optionShow) {
            case 'today':
                $query->whereDate('created_at', now());
                $timeColumn = 'HOUR(created_at)';
                break;
            case 'yesterday':
                $query->whereDate('created_at', now()->subDay());
                $timeColumn = 'HOUR(created_at)';
                break;
            case 'this_week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                $timeColumn = 'DAYOFWEEK(created_at)';
                break;
            case 'last_week':
                $query->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()]);
                $timeColumn = 'DAYOFWEEK(created_at)';
                break;
            case 'this_month':
                $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
                $timeColumn = 'DAY(created_at)';
                break;
            case 'last_month':
                $query->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()]);
                $timeColumn = 'DAY(created_at)';
                break;
            case 'this_year':
                $query->whereBetween('created_at', [now()->startOfYear(), now()->endOfYear()]);
                $timeColumn = 'MONTH(created_at)';
                break;
            case 'last_year':
                $query->whereBetween('created_at', [now()->subYear()->startOfYear(), now()->subYear()->endOfYear()]);
                $timeColumn = 'MONTH(created_at)';
                break;
            default:
                $query->whereBetween('created_at', [$start_date, $end_date]);
                $timeColumn = 'YEAR(created_at)';
        }

        if ($timeColumn) {
            $query->select(DB::raw("$timeColumn as time"), DB::raw('SUM(total_amount) as revenue'))
                ->groupBy('time');
        }

        return $query->get();
    }

    public function getRevenueByRange($start_date, $end_date)
    {
        $start = Carbon::parse($start_date);
        $end = Carbon::parse($end_date);

        // Tạo nhãn (labels) cho từng ngày
        $chartV2Labels = [];
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $chartV2Labels[] = $date->format('d/m/Y');
        }

        $query = $this->model->where('status', EnumsOrderStatus::DELIVERED)
            ->whereBetween('created_at', [$start_date, $end_date])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('date')
            ->get();

        // Chuyển query thành mảng key-value
        $revenueMap = $query->pluck('revenue', 'date')->toArray();

        $chartV2Data = [];
        foreach ($chartV2Labels as $label) {
            $formattedDate = Carbon::createFromFormat('d/m/Y', $label)->format('Y-m-d');
            // Lấy doanh thu từ mảng revenueMap, nếu không có thì trả về 0
            $chartV2Data[] = isset($revenueMap[$formattedDate]) ? (float)$revenueMap[$formattedDate] : 0;
        }

        return [
            'labels' => $chartV2Labels,
            'data' => $chartV2Data,
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\StatisticalService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticalController extends Controller
{
    protected $statisticalService;

    public function __construct(StatisticalService $statisticalService)
    {
        $this->statisticalService = $statisticalService;
    }

    public function index()
    {
        $statistical = $this->statisticalService->index();
        return response()->json($statistical);
    }

    private function getDateRange($optionShow, $request)
    {
        switch($optionShow){
            case 'today':
                $start_date = Carbon::now()->format('Y-m-d');
                $end_date = Carbon::now()->tomorrow()->format('Y-m-d');
                break;
            case 'yesterday':
                $start_date = Carbon::now()->yesterday()->format('Y-m-d');
                $end_date = Carbon::now()->format('Y-m-d');
                break;
            case 'this_week':
                $start_date = Carbon::now()->startOfWeek()->format('Y-m-d');
                $end_date = Carbon::now()->endOfWeek()->format('Y-m-d');
                break;
            case 'last_week':
                $start_date = Carbon::now()->subWeek()->startOfWeek()->format('Y-m-d');
                $end_date = Carbon::now()->subWeek()->endOfWeek()->format('Y-m-d');
                break;
            case 'this_month':
                $start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
                $end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;
            case 'last_month':
                $start_date = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
                $end_date = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
                break;
            case 'this_year':
                $start_date = Carbon::now()->startOfYear()->format('Y-m-d');
                $end_date = Carbon::now()->endOfYear()->format('Y-m-d');
                break;
            case 'last_year':
                $start_date = Carbon::now()->subYear()->startOfYear()->format('Y-m-d');
                $end_date = Carbon::now()->subYear()->endOfYear()->format('Y-m-d');
                break;
            default:
                $start_date = $request->input('start_date') ?? Carbon::now()->subYears(10)->format('Y-m-d');
                $end_date = $request->input('end_date') ?? Carbon::now()->tomorrow()->format('Y-m-d');
        }

        return [$start_date, $end_date];
    }

    public function getRevenueByTime(Request $request)
    {
        $optionShow = $request->input('optionShow', 'all');
        [$start_date, $end_date] = $this->getDateRange($optionShow, $request);

        $chartV2StartDate = null;
        $chartV2EndDate = null;

        //decode dateTimeDetail
        // const statisticOrderQuery = reactive({
        //     optionShow: timeSelection.value,
        //     dateTimeDetail : {
        //         start: dateRangeSelect.value[0].format('YYYY-MM-DD'),
        //         end: dateRangeSelect.value[1].format('YYYY-MM-DD')
        //     }
        // });


        $dateTimeDetail = json_decode($request->input('dateTimeDetail'), true);

        if($dateTimeDetail){
            $chartV2StartDate = $dateTimeDetail['start'];
            $chartV2EndDate = $dateTimeDetail['end'];
        }

        $revenueData = $this->statisticalService->getRevenueByTime($start_date, $end_date, $optionShow, $chartV2StartDate, $chartV2EndDate);

        return response()->json($revenueData);
    }

    public function getTop10BestSeller(Request $request)
    {
        $optionShow = $request->input('optionShow', 'all');
        [$start_date, $end_date] = $this->getDateRange($optionShow, $request);

        $top10BestSeller = $this->statisticalService->getTop10BestSeller($start_date, $end_date, $optionShow);

        return response()->json($top10BestSeller);
    }

    public function getTop10Customer(Request $request)
    {
        $optionShow = $request->input('optionShow', 'all');
        [$start_date, $end_date] = $this->getDateRange($optionShow, $request);

        $top10Customer = $this->statisticalService->getTop10Customer($start_date, $end_date, $optionShow);

        return response()->json($top10Customer);
    }
}

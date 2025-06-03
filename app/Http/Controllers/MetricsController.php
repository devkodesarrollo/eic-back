<?php

namespace App\Http\Controllers;

use App\Services\Metrics\CalculateMetricsService;
use App\Services\Metrics\SaveMetricsService;
use App\Services\Metrics\ChangeFieldMetricsService;
use App\Services\Metrics\Report\GetFiltersReport;
use App\Util\Constants;
use Illuminate\Http\Request;

class MetricsController extends Controller
{
    private $calculateMetricsService;
    private $saveMetricsService;
    private $changeFieldMetricsService;
    private $getFiltersReport;

    public function __construct(
        CalculateMetricsService $calculateMetricsService,
        SaveMetricsService $saveMetricsService,
        ChangeFieldMetricsService $changeFieldMetricsService,
        GetFiltersReport $getFiltersReport
    )
    {
        $this->calculateMetricsService = $calculateMetricsService;
        $this->saveMetricsService = $saveMetricsService;
        $this->changeFieldMetricsService = $changeFieldMetricsService;
        $this->getFiltersReport = $getFiltersReport;
    }

    public function calculate(Request $request)
    {
        try{
            set_time_limit(60 * 60 * 24);
            $response = $this->calculateMetricsService->calculate($request);
            return $this->resolve($response);
        } catch (\Exception $e) {
            return $this->resolve(null, Constants::ERROR_GENERATE_RESULT . $e->getMessage(), true);
        }
    }

    public function save(Request $request)
    {
        try{
            $this->saveMetricsService->save($request);
            return $this->resolve(null, Constants::METRICS_SAVE_SUCCESSFULL);
        } catch (\Exception $e) {
            return $this->resolve(null, Constants::ERROR_GENERATE_RESULT . $e->getMessage(), true);
        }
    }

    public function reportGetFilters(Request $request){
        try{
            set_time_limit(60 * 60 * 24);
            $response = $this->getFiltersReport->get($request);
            return $this->resolve($response);
        } catch (\Exception $e) {
            return $this->resolve(null, Constants::ERROR_REPORT_GENERATE . $e->getMessage(), true);
        }
    }

    public function changeField(Request $request, $id){
        try{
            $model = $this->changeFieldMetricsService->changeField($request, $id);
            return $this->resolve($model, Constants::METRICS_DELETE_SUCCESS);
        }catch (ValidationException $e) {
            return $this->resolve($e->getErrors(), Constants::METRICS_DELETE_ERROR, true, Constants::STATUS_BAD_REQUEST);
        }catch (Exception $e) {
            return $this->resolve(null, Constants::MESSAGE_ERROR_SERVER, true, Constants::STATUS_ERROR_SERVER);
        }        
    }
}

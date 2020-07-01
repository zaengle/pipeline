<?php

namespace Zaengle\Pipeline;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Zaengle\Pipeline\Contracts\AbstractTraveler;

class Pipeline
{
    /**
     * @var Application
     */
    private $app;

    /**
     * Pipeline constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param $data
     * @param array $pipes
     * @param bool $useDatabaseTransactions
     * @return mixed
     * @throws \Exception
     */
    public function pipe($data, array $pipes, $useDatabaseTransactions = false)
    {
        try {
            if ($useDatabaseTransactions) {
                DB::beginTransaction();
            }

            return $this->app->make(\Illuminate\Pipeline\Pipeline::class)
        ->send($data)
        ->through($pipes)
        ->then(function ($data) use ($useDatabaseTransactions) {
            if ($useDatabaseTransactions) {
                DB::commit();
            }

            return $data instanceof AbstractTraveler
            ? $data->setStatus($data::TRAVELER_SUCCESS)
            : $data;
        });
        } catch (\Exception $exception) {
            if ($useDatabaseTransactions) {
                DB::rollBack();
            }
            if ($data instanceof AbstractTraveler) {
                return $data->setStatus($data::TRAVELER_FAIL)
          ->setMessage($exception->getMessage())
          ->setException($exception);
            }

            throw $exception;
        }
    }
}

<?php

namespace Zaengle\Pipeline;

use Exception;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Zaengle\Pipeline\Contracts\AbstractTraveler;

class Pipeline
{
    private Application $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param  AbstractTraveler  $data
     * @param  array  $pipes
     * @param  bool  $useDatabaseTransactions
     * @return AbstractTraveler
     *
     * @throws \Exception
     */
    public function pipe(AbstractTraveler $data, array $pipes, bool $useDatabaseTransactions = false): AbstractTraveler
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

                    return $data->setStatus($data::TRAVELER_SUCCESS);
                });
        } catch (Exception $exception) {
            if ($useDatabaseTransactions) {
                DB::rollBack();
            }

            return $data->setStatus($data::TRAVELER_FAIL)
              ->setMessage($exception->getMessage())
              ->setException($exception);
        }
    }
}

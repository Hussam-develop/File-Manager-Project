<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Exception;

trait TransactionalTrait
{
    public function executeInTransaction(callable $callback)
    {
        try {
            DB::beginTransaction();
            $result = $callback();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Session::flash('danger', $e->getMessage());
            return null; // or throw the exception if you want
        }
    }
}

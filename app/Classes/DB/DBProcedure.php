<?php

namespace App\Classes\DB;

use DB;
use PDOException;
use Request;
use Sitemap;
use User;
use Log;

class DBProcedure {

    public function call($procedureName, $bindings = [], $bindRule = null) {
        $dbh = DB::getPdo();
        $bindings = DB::prepareBindings($bindings);

        $sth = null;
        if (is_callable($bindRule)) {
            $sth = $bindRule($sth, $bindings);
        } else {
            $sqlParam = [];
            foreach ($bindings as $k => $v) {
                if (starts_with($k, ':')) {
                    $sqlParam[] = $k;
                } else {
                    $sqlParam[] = '?';
                }
            }
            $sth = $dbh->prepare('call `' . trim($procedureName) . '` (' . implode(',', $sqlParam) . ')');
        }
        $sth->execute($bindings);

        $result = [];
        do {
            try {
                $tmpResult = $sth->fetchAll(DB::getFetchMode());
            } catch (PDOException $ex) {
                break;
            }
            $procedureError = $this->checkProcedureError($tmpResult);
            if ($procedureError !== false) {
                $procedureError['exception'] = 'procedure';
                Log::error('Database call procedure "' . $procedureName . '" error.', $procedureError);
                $dbProcedureException = new DBProcedureException($procedureError['message']);
                $dbProcedureException->errorInfo = $procedureError;
                throw $dbProcedureException;
            }
            $result[] = $tmpResult;
        } while ($sth->nextRowset());

        return $result;
    }

    public function callFirstDataSet($procedureName, $bindings = [], $bindRule = null) {
        $result = $this->call($procedureName, $bindings, $bindRule);
        if (isset($result[0]) && is_array($result[0])) {
            return $result[0];
        } else {
            return array();
        }
    }

    public function callFirstDataRow($procedureName, $bindings = [], $bindRule = null) {
        $result = $this->call($procedureName, $bindings, $bindRule);
        if (isset($result[0][0]) && is_array($result[0][0])) {
            return $result[0][0];
        } else {
            return array();
        }
    }

    public function callFirstDataField($field, $procedureName, $bindings = [], $bindRule = null) {
        $result = $this->call($procedureName, $bindings, $bindRule);
        if (isset($result[0][0][$field])) {
            return $result[0][0][$field];
        } else {
            return null;
        }
    }

    public function writeLog($param) {
        $ip = Request::ip();
        if ($ip == '::1') {
            $ip = '127.0.0.1';
        }

        $bindParam = [
            'ip' => $ip,
            'page' => Sitemap::node()->getPath(),
            'table' => null,
            'operator' => DBOperator::OP_UNDEFINED,
            'data_before' => null,
            'data_after' => null,
            'member_id' => null,
            'admin_id' => null
        ];
        
        $bindParam = array_merge($bindParam, $param);
        if (!is_null($bindParam['data_before'])) {
            $bindParam['data_before'] = json_encode($bindParam['data_before']);
        }
        if (!is_null($bindParam['data_after'])) {
            $bindParam['data_after'] = json_encode($bindParam['data_after']);
        }


        try {
            $this->call('syslog', array_values($bindParam));
        } catch (\Exception $ex) {
            Log::error('write syslog error. ' . $ex->getMessage(), $bindParam);
        }
    }

    ##

    private function checkProcedureError($result) {
        $error = false;
        if (isset($result[0]) && is_array($result[0]) && count($result[0]) == 3) {
            $keys = array_keys($result[0]);
            if (strtolower($keys[0]) == 'level' && strtolower($keys[1]) == 'code' && strtolower($keys[2]) == 'message') {
                $error = [
                    'level' => $result[0][$keys[0]],
                    'code' => $result[0][$keys[1]],
                    'message' => $result[0][$keys[2]],
                ];
            }
        }
        return $error;
    }

}

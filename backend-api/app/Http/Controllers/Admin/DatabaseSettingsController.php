<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use File;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSettingsController extends Controller
{
    public function db_index()
    {
        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
        $filter_tables = array('admins', 'branches', 'business_settings', 'email_verifications', 'failed_jobs', 'migrations', 'oauth_access_tokens', 'oauth_auth_codes', 'oauth_clients', 'oauth_personal_access_clients', 'oauth_refresh_tokens', 'password_resets', 'phone_verifications', 'soft_credentials', 'users', 'currencies');
        $tables = array_values(array_diff($tables, $filter_tables));

        $rows = [];
        foreach ($tables as $table) {
            $count = DB::table($table)->count();
            array_push($rows, $count);
        }

        return view('admin-views.business-settings.db-index', compact('tables', 'rows'));
    }

    public function clean_db(Request $request)
    {
        $tables = (array)$request->tables;

        if(count($tables) == 0) {
            Toastr::error(translate('No Table Updated'));
            return back();
        }

        try {
            DB::transaction(function () use ($tables) {
                foreach ($tables as $table) {
                    DB::table($table)->delete();
                }
            });
        } catch (\Exception $exception) {
            Toastr::error(translate('Failed to update!'));
            return back();
        }

        Toastr::success(translate('Updated successfully!'));
        return back();
    }
}

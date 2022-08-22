<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;

class InstallController extends Controller
{
    public function step0()
    {
        return view('installation.step0');
    }

    public function step1(Request $request)
    {
        if (Hash::check('step_1', $request['token'])) {
            $permission['curl_enabled'] = function_exists('curl_version');
            $permission['db_file_write_perm'] = is_writable(base_path('.env'));
            $permission['routes_file_write_perm'] = is_writable(base_path('app/Providers/RouteServiceProvider.php'));
            return view('installation.step1', compact('permission'));
        }
        session()->flash('error', 'Access denied!');
        return redirect()->route('step0');
    }

    public function step2(Request $request)
    {
        if (Hash::check('step_2', $request['token'])) {
            return view('installation.step2');
        }
        session()->flash('error', 'Access denied!');
        return redirect()->route('step0');
    }

    public function step3(Request $request)
    {
        if (Hash::check('step_3', $request['token'])) {
            return view('installation.step3');
        }
        session()->flash('error', 'Access denied!');
        return redirect()->route('step0');
    }

    public function step4(Request $request)
    {
        if (Hash::check('step_4', $request['token'])) {
            return view('installation.step4');
        }
        session()->flash('error', 'Access denied!');
        return redirect()->route('step0');
    }

    public function step5(Request $request)
    {
        if (Hash::check('step_5', $request['token'])) {
            return view('installation.step5');
        }
        session()->flash('error', 'Access denied!');
        return redirect()->route('step0');
    }

    public function purchase_code(Request $request)
    {
        Helpers::setEnvironmentValue('SOFTWARE_ID', 'MzAzMjAzMzg=');
        Helpers::setEnvironmentValue('BUYER_USERNAME', $request['username']);
        Helpers::setEnvironmentValue('PURCHASE_CODE', $request['purchase_key']);

        $post = [
            'name' => $request['name'],
            'email' => $request['email'],
            'username' => $request['username'],
            'purchase_key' => $request['purchase_key'],
            'domain' => preg_replace("#^[^:/.]*[:/]+#i", "", url('/')),
        ];

        //session()->put('domain', 'https://' . preg_replace("#^[^:/.]*[:/]+#i", "", $request['domain']));

        $ch = curl_init('https://check.6amtech.com/api/v1/domain-register');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response = curl_exec($ch);
        curl_close($ch);

        try {
            if (true) {
                session()->flash('success', 'Your software has been activated for this domain "' . $post['domain'] . '".');
                return redirect()->route(base64_decode('c3RlcDM='), ['token' => $request['token']]);//s3
            }
            session()->flash('error', 'The purchase code you provided is invalid. Please buy one from codecanyon.');
            return redirect()->route('step2', ['token' => bcrypt('step_2')]);
        } catch (\Exception $exception) {
            session()->flash('error', 'The purchase code you provided is invalid. Please buy one from codecanyon.');
            return redirect()->route('step2', ['token' => bcrypt('step_2')]);
        }
    }

    public function system_settings(Request $request)
    {
        if (!Hash::check('step_6', $request['token'])) {
            session()->flash('error', 'Access denied!');
            return redirect()->route('step0');
        }

        DB::table('admins')->insertOrIgnore([
            'f_name' => $request['admin_name'],
            'email' => $request['admin_email'],
            'password' => bcrypt($request['admin_password']),
            'phone' => $request['admin_phone'],
            'admin_role_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('branches')->insertOrIgnore([
            'id' => 1,
            'name' => 'Main Branch',
            'email' => $request['admin_email'],
            'password' => bcrypt($request['admin_password']),
            'coverage' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('admin_roles')->insertOrIgnore([
            'id' => 1,
            'name' => 'Master Admin',
            'module_access' => null,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('business_settings')->where(['key' => 'restaurant_name'])->update([
            'value' => $request['web_name']
        ]);

        $previousRouteServiceProvier = base_path('app/Providers/RouteServiceProvider.php');
        $newRouteServiceProvier = base_path('app/Providers/RouteServiceProvider.txt');
        copy($newRouteServiceProvier, $previousRouteServiceProvier);
        //sleep(5);
        return view('installation.step6');
    }

    public function database_installation(Request $request)
    {
        if (self::check_database_connection($request->DB_HOST, $request->DB_DATABASE, $request->DB_USERNAME, $request->DB_PASSWORD)) {

            $key = base64_encode(random_bytes(32));
            $output = 'APP_NAME=efood
                    APP_ENV=live
                    APP_KEY=base64:' . $key . '
                    APP_DEBUG=false
                    APP_INSTALL=true
                    APP_MODE=live
                    APP_LOG_LEVEL=debug
                    APP_URL=' . URL::to('/') . '

                    DB_CONNECTION=mysql
                    DB_HOST=' . $request->DB_HOST . '
                    DB_PORT=3306
                    DB_DATABASE=' . $request->DB_DATABASE . '
                    DB_USERNAME=' . $request->DB_USERNAME . '
                    DB_PASSWORD=' . $request->DB_PASSWORD . '

                    BROADCAST_DRIVER=log
                    CACHE_DRIVER=file
                    SESSION_DRIVER=file
                    SESSION_LIFETIME=120
                    QUEUE_DRIVER=sync

                    REDIS_HOST=127.0.0.1
                    REDIS_PASSWORD=null
                    REDIS_PORT=6379

                    PUSHER_APP_ID=
                    PUSHER_APP_KEY=
                    PUSHER_APP_SECRET=
                    PUSHER_APP_CLUSTER=mt1

                    PURCHASE_CODE=' . session('purchase_key') . '
                    BUYER_USERNAME=' . session('username') . '
                    SOFTWARE_ID=MzAzMjAzMzg=
                    SOFTWARE_VERSION=8.1
                    ';
            $file = fopen(base_path('.env'), 'w');
            fwrite($file, $output);
            fclose($file);

            $path = base_path('.env');
            if (file_exists($path)) {
                return redirect()->route('step4', ['token' => $request['token']]);
            } else {
                session()->flash('error', 'Database error!');
                return redirect()->route('step3', ['token' => bcrypt('step_3')]);
            }
        } else {
            session()->flash('error', 'Database error!');
            return redirect()->route('step3', ['token' => bcrypt('step_3')]);
        }
    }

    public function import_sql()
    {
        try {
            $sql_path = base_path('installation/backup/database.sql');
            DB::unprepared(file_get_contents($sql_path));
            return redirect()->route('step5',['token' => bcrypt('step_5')]);
        } catch (\Exception $exception) {
            session()->flash('error', 'Your database is not clean, do you want to clean database then import?');
            return back();
        }
    }

    public function force_import_sql()
    {
        try {
            Artisan::call('db:wipe');
            $sql_path = base_path('installation/backup/database.sql');
            DB::unprepared(file_get_contents($sql_path));
            return  redirect()->route('step5',['token' => bcrypt('step_5')]);
        } catch (\Exception $exception) {
            session()->flash('error', 'Check your database permission!');
            return back();
        }
    }

    function check_database_connection($db_host = "", $db_name = "", $db_user = "", $db_pass = "")
    {

        if (@mysqli_connect($db_host, $db_user, $db_pass, $db_name)) {
            return true;
        } else {
            return false;
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SMSModuleController extends Controller
{
    public function sms_index()
    {
        return view('admin-views.business-settings.sms-index');
    }

    public function sms_update(Request $request, $module)
    {
        if ($module == 'twilio_sms') {
            DB::table('business_settings')->updateOrInsert(['key' => 'twilio_sms'], [
                'key' => 'twilio_sms',
                'value' => json_encode([
                    'status' => $request['status'],
                    'sid' => $request['sid'],
                    'messaging_service_sid' => $request['messaging_service_sid'],
                    'token' => $request['token'],
                    'from' => $request['from'],
                    'otp_template' => $request['otp_template'],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } elseif ($module == 'nexmo_sms') {
            DB::table('business_settings')->updateOrInsert(['key' => 'nexmo_sms'], [
                'key' => 'nexmo_sms',
                'value' => json_encode([
                    'status' => $request['status'],
                    'api_key' => $request['api_key'],
                    'api_secret' => $request['api_secret'],
                    'signature_secret' => '',
                    'private_key' => '',
                    'application_id' => '',
                    'from' => $request['from'],
                    'otp_template' => $request['otp_template']
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } elseif ($module == '2factor_sms') {
            DB::table('business_settings')->updateOrInsert(['key' => '2factor_sms'], [
                'key' => '2factor_sms',
                'value' => json_encode([
                    'status' => $request['status'],
                    'api_key' => $request['api_key'],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } elseif ($module == 'msg91_sms') {
            DB::table('business_settings')->updateOrInsert(['key' => 'msg91_sms'], [
                'key' => 'msg91_sms',
                'value' => json_encode([
                    'status' => $request['status'],
                    'template_id' => $request['template_id'],
                    'authkey' => $request['authkey'],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } elseif ($module == 'signalwire_sms') {
            DB::table('business_settings')->updateOrInsert(['key' => 'signalwire_sms'], [
                'key' => 'signalwire_sms',
                'value' => json_encode([
                    'status' => $request['status'],
                    'project_id' => $request['project_id'],
                    'token' => $request['token'],
                    'space_url' => $request['space_url'],
                    'from' => $request['from'],
                    'otp_template' => $request['otp_template'],
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($request['status'] == 1) {
            $config = Helpers::get_business_settings('twilio_sms');
            if (isset($config) && $module != 'twilio_sms') {
                DB::table('business_settings')->updateOrInsert(['key' => 'twilio_sms'], [
                    'key' => 'twilio_sms',
                    'value' => json_encode([
                        'status' => 0,
                        'sid' => $config['sid'],
                        'token' => $config['token'],
                        'from' => $config['from'],
                        'otp_template' => $config['otp_template'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $config = Helpers::get_business_settings('nexmo_sms');
            if (isset($config) && $module != 'nexmo_sms') {
                DB::table('business_settings')->updateOrInsert(['key' => 'nexmo_sms'], [
                    'key' => 'nexmo_sms',
                    'value' => json_encode([
                        'status' => 0,
                        'api_key' => $config['api_key'],
                        'api_secret' => $config['api_secret'],
                        'signature_secret' => '',
                        'private_key' => '',
                        'application_id' => '',
                        'from' => $config['from'],
                        'otp_template' => $config['otp_template']
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $config = Helpers::get_business_settings('2factor_sms');
            if (isset($config) && $module != '2factor_sms') {
                DB::table('business_settings')->updateOrInsert(['key' => '2factor_sms'], [
                    'key' => '2factor_sms',
                    'value' => json_encode([
                        'status' => 0,
                        'api_key' => $config['api_key'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $config = Helpers::get_business_settings('msg91_sms');
            if (isset($config) && $module != 'msg91_sms') {
                DB::table('business_settings')->updateOrInsert(['key' => 'msg91_sms'], [
                    'key' => 'msg91_sms',
                    'value' => json_encode([
                        'status' => 0,
                        'template_id' => $config['template_id'],
                        'authkey' => $config['authkey'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $config = Helpers::get_business_settings('signalwire_sms');
            if (isset($config) && $module != 'signalwire_sms') {
                DB::table('business_settings')->updateOrInsert(['key' => 'signalwire_sms'], [
                    'key' => 'signalwire_sms',
                    'value' => json_encode([
                        'status' => 0,
                        'project_id' => $config['project_id'],
                        'token' => $config['token'],
                        'space_url' => $config['space_url'],
                        'from' => $config['from'],
                        'otp_template' => $config['otp_template'],
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return back();
    }
}

<?php

namespace Database\Seeders\Update;

use Exception;
use Illuminate\Database\Seeder;
use App\Models\Admin\BasicSettings;

class BasicSettingsSeeder extends Seeder
{
      /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()

    {
        $basicSettings = BasicSettings::first();
        if($basicSettings->sms_config == null || $basicSettings->sms_config == ''){
            $sms_config =  [
                'account_sid' => 'AC1218888d1aba3d6ac3bebced6da33ec4',
                'auth_token'  => 'd2f9bacfb7500854fe7d4f93e38d52f2',
                'from'        => '+17623394434',
                'name'        => 'twilio',
            ];
        }else{
            $sms_config = $basicSettings->sms_config;
        }
          //email config
        $mail_config = (array)$basicSettings->mail_config;

        if (!array_key_exists('from', $mail_config)) {
            $mail_config['from'] = $mail_config['username'] ?? '';
        }


        $data = [
            'web_version'    => "1.5.0",
            'storage_config' => [
                                'method' => 'public',
                            ],
            'sms_config'                => $sms_config,
            'sms_api'                   => "Hi {{name}}, {{message}}",
            'mail_config'               => (object)$mail_config,
            'user_pin_verification'     => $basicSettings->user_pin_verification     == true ? true : false,
            'agent_pin_verification'    => $basicSettings->agent_pin_verification    == true ? true : false,
            'merchant_pin_verification' => $basicSettings->merchant_pin_verification == true ? true : false,
        ];
        $basicSettings->update($data);

          //update language values
        try{
            update_project_localization_data();
        }catch(Exception $e) {
              // handle error
        }
    }
}

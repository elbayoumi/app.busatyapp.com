<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            'id' => 1,
            'name' => 'Busaty',
            'slogan' => null,
            'short_description' => null,
            'address' => null,
            'country' => null,
            'city' => null,
            'postal_code' => null,
            'light_logo' => null,
            'dark_logo' => null,
            'favicon' => null,
            'dashboard_logo' => null,
            'telephone' => null,
            'mobile' => '01020472050',
            'email' => 'mohamedashrafelbayoumi@gmail.com',
            'linkedin' => null,
            'behance' => null,
            'github' => null,
            'twitter' => null,
            'facebook' => null,
            'instagram' => null,
            'meta_keywords' => null,
            'meta_description' => null,
            'meta_image' => null,
            'smtp_host' => 'busaty.org',
            'smtp_port' => '465',
            'smtp_encryption' => 'ssl',
            'smtp_username' => 'noreplay@busaty.org',
            'smtp_password' => 'ISn#xkM-lr^S',
            'smtp_from_address' => 'noreplay@busaty.org',
            'smtp_from_name' => 'Busaty <noreply@busaty.org>',
            'merchants_latest_version' => '1.0.0',
            'distributors_latest_version' => '1.0.0',
        ];
        DB::table('settings')->insert($settings);

        $notification_texts = array(
            array('id' => '9','title' => '{"ar": "صباح الخير", "en": "Good Morning"}','body' => '{"ar": "باص مدرسة {{School}}->name بدء الرحلة في طريقه إليك", "en": "باص مدرسة {{School}}->name بدء الرحلة في طريقه إليك"}','default_body' => '{"ar": "الباص في  طريقه اليك", "en": "الباص في  طريقه اليك"}','type' => NULL,'for_model_type' => 'App\\Models\\Trip','to_model_type' => 'App\\Models\\My_Parent','model_additional' => '{"to_model_additional": [], "for_model_additional": ["start_day"]}','group' => '1','created_at' => '2024-10-13 11:21:26','updated_at' => '2024-10-16 12:32:59'),
            array('id' => '11','title' => '{"ar": "صباح الخير", "en": "Good Morning"}','body' => '{"ar": "باص مدرسة {{School}}->name وصل للمدرسة", "en": "باص مدرسة {{School}}->name وصل للمدرسة"}','default_body' => '{"ar": "الباص وصل المدرسة", "en": "الباص وصل المدرسة"}','type' => NULL,'for_model_type' => 'App\\Models\\Trip','to_model_type' => 'App\\Models\\My_Parent','model_additional' => '{"to_model_additional": [], "for_model_additional": ["start_day"]}','group' => '1','created_at' => '2024-10-15 10:40:14','updated_at' => '2024-10-15 14:19:18'),
            array('id' => '12','title' => '{"ar": "مساء الخير", "en": "Good Evining"}','body' => '{"ar": "باص مدرسة {{School}}->name بدء الرحلة في طريقه إليك", "en": "باص مدرسة {{School}}->name بدء الرحلة في طريقه إليك"}','default_body' => '{"ar": "الباص في طريقه اليك", "en": "الباص في طريقه اليك"}','type' => NULL,'for_model_type' => 'App\\Models\\Trip','to_model_type' => 'App\\Models\\My_Parent','model_additional' => '{"to_model_additional": [], "for_model_additional": ["end_day"]}','group' => '1','created_at' => '2024-10-15 10:45:04','updated_at' => '2024-10-15 14:20:44'),
            array('id' => '13','title' => '{"ar": "صباح ااخير", "en": "Good Morning"}','body' => '{"ar": "الباص تحرك من المدرسة وفي طريقه لـ {{Student}}->name", "en": "الباص تحرك من المدرسة وفي طريقه لـ {{Student}}->name"}','default_body' => '{"ar": "الباص في طريقه اليك", "en": "الباص في طريقه اليك"}','type' => NULL,'for_model_type' => 'App\\Models\\Trip','to_model_type' => 'App\\Models\\My_Parent','model_additional' => '{"to_model_additional": [], "for_model_additional": ["start_day"]}','group' => '0','created_at' => '2024-10-15 10:49:39','updated_at' => '2024-10-15 14:21:47'),
            array('id' => '14','title' => '{"ar": "صباح الخير", "en": "Good Morning"}','body' => '{"ar": "الباص بالقرب من المنزل يرجى تجهيز  {{Student}}->name", "en": "الباص بالقرب من المنزل يرجى تجهيز  {{Student}}->name"}','default_body' => '{"ar": "الباص بالقرب من المنزل", "en": "الباص بالقرب من المنزل"}','type' => NULL,'for_model_type' => 'App\\Models\\Trip','to_model_type' => 'App\\Models\\My_Parent','model_additional' => '{"to_model_additional": [], "for_model_additional": ["start_day"]}','group' => '0','created_at' => '2024-10-15 10:53:24','updated_at' => '2024-10-15 14:22:55'),
            array('id' => '15','title' => '{"ar": "صباح ااخير", "en": "Good Morning"}','body' => '{"ar": "الباص بانتظار {{Student}}->name أمام المنزل", "en": "الباص بانتظار {{Student}}->name أمام المنزل"}','default_body' => '{"ar": "الباص بانتظارك   أمام المنزل", "en": "الباص بانتظارك   أمام المنزل"}','type' => NULL,'for_model_type' => 'App\\Models\\Trip','to_model_type' => 'App\\Models\\My_Parent','model_additional' => '{"to_model_additional": [], "for_model_additional": ["start_day"]}','group' => '0','created_at' => '2024-10-15 10:54:36','updated_at' => '2024-10-15 14:23:55'),
            array('id' => '16','title' => '{"ar": "مساء الخير", "en": "Good Evining"}','body' => '{"ar": "{{Student}}->name تحرك من المدرسة وفي طريقه للمنزل", "en": "{{Student}}->name تحرك من المدرسة وفي طريقه للمنزل"}','default_body' => '{"ar": "الباص تحرك من المدرسة", "en": "الباص تحرك من المدرسة"}','type' => NULL,'for_model_type' => 'App\\Models\\Trip','to_model_type' => 'App\\Models\\My_Parent','model_additional' => '{"to_model_additional": [], "for_model_additional": ["end_day"]}','group' => '0','created_at' => '2024-10-15 11:00:27','updated_at' => '2024-10-15 14:25:11'),
            array('id' => '17','title' => '{"ar": "مساء الخير", "en": "Good Evining"}','body' => '{"ar": "{{Student}}->name بالقرب من المنزل", "en": "{{Student}}->name بالقرب من المنزل"}','default_body' => '{"ar": "الباص بالقرب من المنزل", "en": "الباص بالقرب من المنزل"}','type' => NULL,'for_model_type' => 'App\\Models\\Trip','to_model_type' => 'App\\Models\\My_Parent','model_additional' => '{"to_model_additional": [], "for_model_additional": ["end_day"]}','group' => '0','created_at' => '2024-10-15 11:02:49','updated_at' => '2024-10-15 14:25:56'),
            array('id' => '18','title' => '{"ar": "مساء الخير", "en": "Good Evining"}','body' => '{"ar": "{{Student}}->name وصل المنزل", "en": "{{Student}}->name وصل المنزل"}','default_body' => '{"ar": "الباص وصل المنزل", "en": "الباص وصل المنزل"}','type' => NULL,'for_model_type' => 'App\\Models\\Trip','to_model_type' => 'App\\Models\\My_Parent','model_additional' => '{"to_model_additional": [], "for_model_additional": ["end_day"]}','group' => '0','created_at' => '2024-10-15 11:03:44','updated_at' => '2024-10-15 14:26:32')
          );

          DB::table('notification_texts')->insert($notification_texts);
    }
}

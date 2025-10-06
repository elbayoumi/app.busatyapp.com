<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $questions = array(
            array('id' => '2', 'question' => 'assdddd', 'answer' => 'ffdddd', 'type' => 'attendants', 'lang' => 'en', 'status' => '1', 'created_at' => '2025-01-12 15:33:19', 'updated_at' => '2025-01-12 15:33:19'),
            array('id' => '7', 'question' => '2. Will I receive notifications if there are changes to the bus schedule or route?', 'answer' => 'Yes, the app provides instant notifications for any updates, such as delays, route changes, or when the bus arrives at home or school. Ensure notifications are enabled in your app settings.', 'type' => 'parents', 'lang' => 'en', 'status' => '1', 'created_at' => '2025-01-12 16:24:37', 'updated_at' => '2025-01-12 16:24:37'),
            array('id' => '8', 'question' => '3. Can I access my child’s information through the app?', 'answer' => 'Absolutely. The app allows you to view details such as your child\'s name, address, and emergency contact information. This ensures you always have the necessary details at hand.', 'type' => 'parents', 'lang' => 'en', 'status' => '1', 'created_at' => '2025-01-12 16:25:18', 'updated_at' => '2025-01-12 16:25:18'),
            array('id' => '9', 'question' => '4. What should I do if the app is not showing the bus location?', 'answer' => 'If the bus location is not visible, ensure your internet connection is active and the app is updated to the latest version. If the issue persists, contact support through the app’s help section.', 'type' => 'parents', 'lang' => 'en', 'status' => '1', 'created_at' => '2025-01-12 16:25:51', 'updated_at' => '2025-01-12 16:25:51'),
            array('id' => '10', 'question' => '5. Is my data and my child’s information secure on the app?', 'answer' => 'Yes, the Busaty Parent App uses advanced encryption and data protection measures to ensure your information is secure and only accessible to authorized users. Your privacy and security are our top priorities.', 'type' => 'parents', 'lang' => 'en', 'status' => '1', 'created_at' => '2025-01-12 16:26:21', 'updated_at' => '2025-01-12 16:26:21'),
            array('id' => '11', 'question' => 'حمل التطبيق الآن واستمتع بإدارة متقدمة وتتبع في الوقت الفعلي لحافلات المدرسة،', 'answer' => 'التواصل الفعّال:


           تمكين المشرفين والسائقين من الوصول إلى مواقع الطلاب وإرسال إشعارات فورية للأهالي.
           السماح للأهالي بتتبع رحلات أطفالهم وتلقي الإشعارات عند وصول الحافلة إلى المنزل أو المدرسة.', 'type' => 'parents', 'lang' => 'ar', 'status' => '1', 'created_at' => '2025-01-15 14:41:01', 'updated_at' => '2025-01-15 15:33:12'),
            array('id' => '12', 'question' => 'hyugyg', 'answer' => 'guyfuygyu', 'type' => 'parents', 'lang' => 'ar', 'status' => '1', 'created_at' => '2025-02-05 21:47:31', 'updated_at' => '2025-02-05 21:47:31'),
            array('id' => '13', 'question' => 'kuigyittyuyuguy', 'answer' => 'شسي', 'type' => 'attendants', 'lang' => 'en', 'status' => '1', 'created_at' => '2025-02-05 21:47:55', 'updated_at' => '2025-02-05 23:03:02'),
            array('id' => '14', 'question' => 'كيف يمكنني إضافة طالب جديد إلى النظام؟', 'answer' => 'لإضافة طالب جديد، انتقل إلى صفحة الطلاب، ثم اضغط على "إضافة طالب جديد".
           ستظهر لك خيارات لإضافة الطالب يدويًا أو استيراد قائمة الطلاب من ملف Excel.
           بعد اختيار الطريقة المناسبة، أدخل بيانات الطالب مثل الاسم، العنوان، رقم الهاتف،
           المرحلة الدراسية، الصف، النوع، اسم الباص، نوع الاشتراك، والموقع الجغرافي،
           ثم اضغط على "إضافة" لحفظ البيانات.', 'type' => 'schools', 'lang' => 'ar', 'status' => '1', 'created_at' => '2025-02-05 21:53:26', 'updated_at' => '2025-02-05 23:05:47'),
            array('id' => '15', 'question' => 'How can I add a new student to the system?', 'answer' => 'To add a new student, go to the Students Page, then click "Add New Student".
           You can either add the student manually or import a list from an Excel file.
           After selecting the preferred method, enter the student\'s details
           such as name, address, phone number, academic level, class, gender,
           bus name, subscription type, and location, then click "Add" to save the data.', 'type' => 'schools', 'lang' => 'en', 'status' => '1', 'created_at' => '2025-02-05 21:56:02', 'updated_at' => '2025-02-05 23:04:37'),
            array('id' => '16', 'question' => 'كيف يمكنني تعديل بيانات طالب مسجل بالفعل؟', 'answer' => 'للتعديل على بيانات طالب، انتقل إلى صفحة الطلاب، ثم ابحث عن الطالب المطلوب باستخدام مربع البحث.
           بجوار اسم الطالب، اضغط على "تعديل"، ثم قم بتحديث البيانات المطلوبة واضغط على "حفظ".', 'type' => 'schools', 'lang' => 'ar', 'status' => '1', 'created_at' => '2025-02-05 23:07:15', 'updated_at' => '2025-02-05 23:07:15'),
            array('id' => '17', 'question' => 'كيف يمكنني حذف طالب من النظام؟', 'answer' => 'لحذف طالب، انتقل إلى صفحة الطلاب، ثم اضغط على زر الخيارات بجانب اسم الطالب،
           واختر "حذف". سيتم إزالة بيانات الطالب من النظام نهائيًا.', 'type' => 'schools', 'lang' => 'ar', 'status' => '1', 'created_at' => '2025-02-05 23:08:25', 'updated_at' => '2025-02-05 23:08:25'),
            array('id' => '18', 'question' => 'كيف يمكنني تعيين مشرف أو سائق للباص؟', 'answer' => 'لإضافة مشرف أو سائق، انتقل إلى صفحة المشرفين أو السائقين،
           ثم اضغط على "إضافة مشرف" أو "إضافة سائق".
           أدخل البيانات المطلوبة، مثل الاسم، اسم المستخدم، النوع، الباص المرتبط،
           العنوان، رقم الهاتف، وكلمة المرور،
           ثم اضغط على "إضافة".', 'type' => 'schools', 'lang' => 'ar', 'status' => '1', 'created_at' => '2025-02-05 23:09:13', 'updated_at' => '2025-02-05 23:09:13'),
            array('id' => '19', 'question' => 'كيف يمكنني تتبع حركة الباصات في الوقت الفعلي؟', 'answer' => 'في حالة وجود رحلات مفتوحة، يظهر زر "الرحلات المفتوحة" في الصفحة الرئيسية للتطبيق.
           عند الضغط عليه، ستظهر قائمة بجميع السائقين والمشرفين الذين في رحلة حاليًا.
           لمتابعة أي باص، اضغط على أيقونة "Track" بجوار السائق أو المشرف،
           وستتمكن من رؤية موقع الباص على الخريطة في الوقت الفعلي، بالإضافة إلى السرعة التي يسير بها.', 'type' => 'schools', 'lang' => 'ar', 'status' => '1', 'created_at' => '2025-02-05 23:10:05', 'updated_at' => '2025-02-05 23:10:05'),
            array('id' => '20', 'question' => 'كيف يمكنني الموافقة على طلب تغيير عنوان طالب؟', 'answer' => 'اذهب إلى صفحة طلبات تغيير العنوان، ثم اختر الطلب،
           وراجع العنوان الجديد، واضغط على "قبول" أو "رفض".', 'type' => 'schools', 'lang' => 'ar', 'status' => '1', 'created_at' => '2025-02-05 23:10:49', 'updated_at' => '2025-02-05 23:10:49'),
            array('id' => '21', 'question' => 'كيف يمكنني تغيير لغة التطبيق؟', 'answer' => 'انتقل إلى الإعدادات، ثم صفحة اللغة، واختر العربية أو الإنجليزية.', 'type' => 'schools', 'lang' => 'ar', 'status' => '1', 'created_at' => '2025-02-05 23:11:25', 'updated_at' => '2025-02-05 23:11:25'),
            array('id' => '22', 'question' => 'كيف يمكنني حذف حساب المدرسة؟', 'answer' => 'انتقل إلى الإعدادات، ثم اضغط على "مسح الحساب"، واتبع التعليمات لحذفه نهائيًا.', 'type' => 'schools', 'lang' => 'ar', 'status' => '1', 'created_at' => '2025-02-05 23:11:50', 'updated_at' => '2025-02-05 23:11:50'),
            array('id' => '23', 'question' => 'كيف يمكنني إضافة باص جديد؟', 'answer' => 'لإضافة باص جديد، انتقل إلى صفحة الباصات، ثم اضغط على "إضافة باص جديد".
           قم بإدخال اسم الباص، رقم الباص، والملاحظات،
           ثم اضغط على "إضافة" لحفظ البيانات.', 'type' => 'schools', 'lang' => 'ar', 'status' => '1', 'created_at' => '2025-02-05 23:12:38', 'updated_at' => '2025-02-05 23:12:38'),
            array('id' => '24', 'question' => 'هل يمكنني طباعة تقرير بالطلاب المسجلين في باص معين؟', 'answer' => 'نعم، يمكنك ذلك عن طريق صفحة الباصات.
           اضغط على "عرض الطلاب" بجوار الباص المطلوب،
           ثم اضغط على "طباعة PDF" للحصول على تقرير يحتوي على أسماء الطلاب، المرحلة الدراسية، وكود تسجيل أولياء الأمور.', 'type' => 'schools', 'lang' => 'ar', 'status' => '1', 'created_at' => '2025-02-05 23:13:31', 'updated_at' => '2025-02-05 23:13:31'),
            array('id' => '25', 'question' => 'كيف يمكنني إعادة تعيين كلمة المرور لمشرف أو سائق؟', 'answer' => 'لإعادة تعيين كلمة المرور لمشرف أو سائق،
           انتقل إلى صفحة المشرفين أو السائقين، ثم اضغط على "تعديل" بجوار الحساب المطلوب.
           بعد ذلك، أدخل كلمة المرور الجديدة وأكدها، ثم اضغط على "حفظ" لحفظ التغييرات.', 'type' => 'schools', 'lang' => 'ar', 'status' => '1', 'created_at' => '2025-02-05 23:14:22', 'updated_at' => '2025-02-05 23:14:22'),
            array('id' => '26', 'question' => 'ما هي البيانات التي يمكن للمشرفين والسائقين الوصول إليها؟', 'answer' => 'المشرفون والسائقون يمكنهم الوصول فقط إلى البيانات الخاصة بالطلاب المرتبطين بالباصات التي يعملون عليها.
           يمكنهم رؤية أسماء الطلاب، العناوين، أرقام الهواتف، المواقع الجغرافية،
           وإرسال إشعارات إلى أولياء الأمور عند الضرورة.', 'type' => 'schools', 'lang' => 'ar', 'status' => '1', 'created_at' => '2025-02-05 23:15:12', 'updated_at' => '2025-02-05 23:15:12'),
            array('id' => '27', 'question' => 'كيف يمكنني التواصل مع الدعم الفني؟', 'answer' => 'للتواصل مع فريق الدعم الفني، انتقل إلى صفحة المساعدة،
           ثم اضغط على "اتصل بنا". قم بملء النموذج ببياناتك ورسالتك،
           ثم اضغط على "إرسال". سيتم الرد عليك في أقرب وقت ممكن.', 'type' => 'schools', 'lang' => 'ar', 'status' => '1', 'created_at' => '2025-02-05 23:15:45', 'updated_at' => '2025-02-05 23:15:45'),
            array('id' => '28', 'question' => 'How can I edit the details of an already registered student?', 'answer' => 'To edit a student’s details, go to the Students Page,
           search for the student using the search box,
           click "Edit" next to the student’s name,
           update the necessary details,
           and click "Save".', 'type' => 'schools', 'lang' => 'en', 'status' => '1', 'created_at' => '2025-02-05 23:16:41', 'updated_at' => '2025-02-05 23:16:41')
        );





        DB::table('questions')->insert($questions);
    }
}

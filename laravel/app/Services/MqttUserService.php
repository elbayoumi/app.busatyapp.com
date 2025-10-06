<?php

namespace App\Services;

class MqttUserService
{
    protected $passwordFile = '/etc/mosquitto/passwordfile';
    protected $aclFile = '/etc/mosquitto/aclfile';

    public function addMqttUser($username, $password)
    {
        // تأكد من أن اسم المستخدم وكلمة المرور لا تحتويان على أحرف ضارة
        $sanitizedUsername = escapeshellarg($username);
        $sanitizedPassword = escapeshellarg($password);

        // إضافة كلمة المرور إلى ملف Mosquitto passwordfile
        $command = "sudo mosquitto_passwd -b {$this->passwordFile} $sanitizedUsername $sanitizedPassword";

        // نفذ الأمر
        $output = shell_exec($command . ' 2>&1');

        // تحقق من نتيجة الأمر
        if ($output === null) {
            throw new \Exception("Failed to add user: " . $output);
        }

        // تحديث ملف ACL
        $this->updateAclFile($username);

        // إعادة تشغيل Mosquitto لتطبيق التغييرات
        $this->restartMosquitto();

        return true;
    }

    public function updateMqttUser($username, $newPassword)
    {
        $sanitizedUsername = escapeshellarg($username);
        $sanitizedPassword = escapeshellarg($newPassword);

        // تحديث كلمة المرور في ملف passwordfile
        $command = "sudo mosquitto_passwd -b {$this->passwordFile} $sanitizedUsername $sanitizedPassword";

        // نفذ الأمر
        $output = [];
        $returnVar = 0;
        exec($command . ' 2>&1', $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception("Failed to update user password: " . implode("\n", $output));
        }

        return true;
    }

    public function deleteMqttUser($username)
    {
        $sanitizedUsername = escapeshellarg($username);

        // إزالة المستخدم من ملف passwordfile
        $command = "sudo mosquitto_passwd -D {$this->passwordFile} $sanitizedUsername";

        // نفذ الأمر
        $output = [];
        $returnVar = 0;
        exec($command . ' 2>&1', $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception("Failed to delete user: " . implode("\n", $output));
        }

        // حذف المستخدم من ملف ACL
        $this->removeFromAclFile($username);

        // إعادة تشغيل Mosquitto لتطبيق التغييرات
        $this->restartMosquitto();

        return true;
    }

    protected function updateAclFile($username)
    {
        // نص الصلاحيات للمستخدم الجديد
        $aclContent = "\nuser {$username}\ntopic readwrite #\n";

        // فتح ملف ACL للتحقق من وجوده وقراءة محتوياته
        if (!file_exists($this->aclFile)) {
            throw new \Exception("ACL file does not exist.");
        }

        // قراءة المحتوى الحالي للملف
        $currentContent = file_get_contents($this->aclFile);
        if ($currentContent === false) {
            throw new \Exception("Failed to read ACL file.");
        }

        // التأكد من أن الصلاحيات للمستخدم لم تضاف مسبقًا
        if (strpos($currentContent, "user {$username}") === false) {
            // إضافة الصلاحيات إلى نهاية الملف
            $currentContent .= $aclContent;
        }

        // كتابة المحتويات المحدثة إلى الملف
        if (file_put_contents($this->aclFile, $currentContent, LOCK_EX) === false) {
            throw new \Exception("Failed to write updated ACL file.");
        }
    }

    protected function removeFromAclFile($username)
    {
        // قراءة محتويات ملف ACL
        $aclContent = file_get_contents($this->aclFile);
        if ($aclContent === false) {
            throw new \Exception("Failed to read ACL file.");
        }

        // إزالة الصلاحيات الخاصة بالمستخدم
        $pattern = "/\nuser {$username}\n(topic [^\n]*\n)*/";
        $updatedContent = preg_replace($pattern, '', $aclContent);

        if ($updatedContent === null) {
            throw new \Exception("Failed to update ACL content.");
        }

        // كتابة المحتويات المحدثة إلى ملف ACL
        if (file_put_contents($this->aclFile, $updatedContent, LOCK_EX) === false) {
            throw new \Exception("Failed to write updated ACL file.");
        }
    }

    protected function restartMosquitto()
    {
        // إعادة تشغيل خدمة Mosquitto لتطبيق التغييرات
        $command = "sudo systemctl restart mosquitto";
        $output = [];
        $returnVar = 0;
        exec($command . ' 2>&1', $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception("Failed to restart Mosquitto service: " . implode("\n", $output));
        }
    }
}

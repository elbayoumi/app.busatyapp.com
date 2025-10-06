@extends('dashboard.layouts.app')
@push('page_vendor_css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashboard') }}/app-assets/vendors/css/forms/select/select2.min.css">
{{-- <script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script> --}}
@endpush
@push('page_styles')
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }
    h1 {
        text-align: center;
        color: #333;
    }
    #container {
        max-width: 600px;
        margin: 0 auto;
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    #messages {
        list-style-type: none;
        padding: 0;
        margin-bottom: 20px;
        height: 300px; /* جعل ارتفاع ثابت */
        overflow-y: auto; /* تمكين التمرير العمودي */
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
    }
    #messages li {
        padding: 10px;
        border-bottom: 1px solid #eee;
    }
    #messages li:last-child {
        border-bottom: none;
    }
    #messageInput {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 10px;
    }
    #sendButton {
        background-color: #5cb85c;
        color: white;
        border: none;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
    }
    #sendButton:hover {
        background-color: #4cae4c;
    }

    .notification {
    position: absolute; /* استخدم absolute لجعل الإشعار فوق المحتوى */
    top: 20px; /* يمكنك تعديل هذا الموضع حسب الحاجة */
    left: 50%; /* لمركزة الإشعار */
    transform: translateX(-50%); /* لتحريك الإشعار للنصف */
    background-color: #dff0d8;
    color: #3c763d;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 10px;
    z-index: 1000; /* تأكد من أن الإشعار فوق المحتوى الآخر */
}

</style>
@endpush
@section('content')
<section id="multilingual-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom p-2">
                    <h4 class="card-title">قائمة / الرسايل</h4>
                </div>
                <div id="container">
                    <h1>MQTT Messages</h1>
                    <ul id="messages"></ul>
                    <input type="text" id="messageInput" placeholder="Type your message here...">
                    <button id="sendButton">Send Message</button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('page_scripts_vendors')
<script src="{{ asset('assets/dashboard') }}/app-assets/vendors/js/forms/select/select2.full.min.js"></script>
@endpush

@push('page_scripts')
{{-- <script>
    // إعدادات الاتصال
    const options = {
        host: 'wss.busatyapp.com',
        port: 443,
        protocol: 'wss',
        username: 'ashraf', // استبدل بـ اسم المستخدم الخاص بك
        password: 'ashraf',    // استبدل بـ كلمة المرور الخاصة بك
     clientId: 'your_client_id'
    };

    // إنشاء عميل MQTT
    const client = mqtt.connect(options);

    // عند الاتصال بالخادم
    client.on('connect', function() {
        console.log('Connected to MQTT broker');

        // الاشتراك في موضوع معين
        client.subscribe('test/topic', function(err) {
            if (!err) {
                console.log('Subscribed to topic: test/topic');
            }
        });
    });

    // استلام الرسائل
    client.on('message', function(topic, message) {
        const msg = message.toString();
        console.log(`Received message from ${topic}: ${msg}`);
        const messagesList = document.getElementById('messages');
        const listItem = document.createElement('li');
        listItem.textContent = `Topic: ${topic}, Message: ${msg}`;
        messagesList.appendChild(listItem);
        messagesList.scrollTop = messagesList.scrollHeight; // Scroll to the bottom

        // إضافة إشعار جديد
        displayNotification(`Received message: ${msg}`);
    });

    // التعامل مع الأخطاء
    client.on('error', function(error) {
        console.error('Connection error:', error);
    });

    // إرسال الرسائل عند الضغط على الزر
    document.getElementById('sendButton').addEventListener('click', sendMessage);

    // إرسال الرسائل عند الضغط على "Enter"
    document.getElementById('messageInput').addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            sendMessage();
            event.preventDefault(); // لمنع إدخال سطر جديد
        }
    });

    function sendMessage() {
        const input = document.getElementById('messageInput');
        const message = input.value.trim(); // حذف المسافات البيضاء
        if (message) {
            client.publish('test/topic', message);
            input.value = ''; // Clear input field
        }
    }

    function displayNotification(msg) {
        const container = document.getElementById('container');
        const notification = document.createElement('div');
        notification.className = 'notification';
        notification.textContent = msg;
        container.prepend(notification); // أضف الإشعار في الأعلى
        setTimeout(() => {
            notification.remove(); // إزالة الإشعار بعد 3 ثواني
        }, 3000);
    }
</script> --}}
@endpush

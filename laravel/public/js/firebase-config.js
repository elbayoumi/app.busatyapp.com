// firebase-config.js
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.13.2/firebase-app.js";
import { getMessaging, getToken } from "https://www.gstatic.com/firebasejs/10.13.2/firebase-messaging.js";

const firebaseConfig = {
  apiKey: "AIzaSyAsbepfeItKyWtyJQG9maPA9B4UgdAtTSQ",
  authDomain: "busaty-app.firebaseapp.com",
  projectId: "busaty-app",
  storageBucket: "busaty-app.appspot.com",
  messagingSenderId: "66220404803",
  appId: "1:66220404803:web:7f65593649a98ba46124f6",
  measurementId: "G-T869W7J5H3"
};

const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

async function requestPermission() {
  try {
    const token = await getToken(messaging, {
      vapidKey: "BAnxNKaDFAjM5kqjHOOn1cyYYDiGyqQfSMQaR3LMGKSAkeh9o76QXM3g4YkIRs7SGWaU1TOCHDd5Vlxx8f3pfKo" // استبدله بمفتاح VAPID الخاص بك
    });
    if (token) {
      console.log('FCM Token:', token);
      // إرسال التوكن إلى الخادم
      await fetch('/save-token', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ token }),
      });
    } else {
      console.log('No registration token available.');
    }
  } catch (error) {
    console.error('Error getting FCM token:', error);
  }
}

requestPermission();

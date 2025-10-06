<!DOCTYPE html>
<html>
<head>
  <title>Firebase Token Example</title>
  <script type="module">
    // Import the functions you need from the SDKs you need
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.13.2/firebase-app.js";
    import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.13.2/firebase-analytics.js";
    import { getMessaging, getToken, onMessage } from "https://www.gstatic.com/firebasejs/10.13.2/firebase-messaging.js";

    // Your web app's Firebase configuration
    const firebaseConfig = {
      apiKey: "AIzaSyAsbepfeItKyWtyJQG9maPA9B4UgdAtTSQ",
      authDomain: "busaty-app.firebaseapp.com",
      projectId: "busaty-app",
      storageBucket: "busaty-app.appspot.com",
      messagingSenderId: "66220404803",
      appId: "1:66220404803:web:7f65593649a98ba46124f6",
      measurementId: "G-T869W7J5H3"
    };

    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const analytics = getAnalytics(app);
    const messaging = getMessaging(app);

    // Request permission and get FCM token
    async function requestPermission() {
      try {
        const permission = await Notification.requestPermission();
        if (permission === "granted") {
          console.log("Notification permission granted.");
          const token = await getToken(messaging, {
            vapidKey: 'Ikqc5EpC2o2yIHSVOiisCncmO3uw7m7tUlZAWB1YnJ4' // Replace with your VAPID key
          });
          console.log("FCM Token:", token);
        } else {
          console.error("Unable to get permission to notify.");
        }
      } catch (error) {
        console.error("Error getting FCM token:", error);
      }
    }

    // Call the function to request permission and get token
    requestPermission();
  </script>
</head>
<body>
  <h1>Check the console for FCM Token</h1>
</body>
</html>

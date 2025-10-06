// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics  } from "firebase/analytics";
import { getMessaging, getToken } from "firebase/messaging";

// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyAsbepfeItKyWtyJQG9maPA9B4UgdAtTSQ",
  authDomain: "busaty-app.firebaseapp.com",
  projectId: "busaty-app",
  storageBucket: "busaty-app.firebasestorage.app",
  messagingSenderId: "66220404803",
  appId: "1:66220404803:web:7f65593649a98ba46124f6",
  measurementId: "G-T869W7J5H3"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);

require('./bootstrap');
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/firebase-messaging-sw.js')
      .then((registration) => {
        console.log('Service Worker registered with scope:', registration.scope);
      })
      .catch((err) => {
        console.error('Service Worker registration failed:', err);
      });
  }

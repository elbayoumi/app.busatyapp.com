importScripts('https://www.gstatic.com/firebasejs/10.13.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/10.13.2/firebase-messaging.js');

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
firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

// Add your VAPID key here
messaging.usePublicVapidKey('BAnxNKaDFAjM5kqjHOOn1cyYYDiGyqQfSMQaR3LMGKSAkeh9o76QXM3g4YkIRs7SGWaU1TOCHDd5Vlxx8f3pfKo');

messaging.onBackgroundMessage((payload) => {
  console.log('Background Message received:', payload);

  self.registration.showNotification(payload.notification.title, {
    body: payload.notification.body,
    icon: payload.notification.icon
  });
});

importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js');
firebase.initializeApp({apiKey: "AIzaSyDhOmCTdj8EOjg_6qg2nKpr11HAUdob1_Q",authDomain: "saucy-e680a.firebaseapp.com",projectId: "saucy-e680a",storageBucket: "saucy-e680a.appspot.com", messagingSenderId: "727664446318", appId: "1:727664446318:web:50ee526b0171c33b420f7f"});
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) { return self.registration.showNotification(payload.data.title, { body: payload.data.body ? payload.data.body : '', icon: payload.data.icon ? payload.data.icon : '' }); });

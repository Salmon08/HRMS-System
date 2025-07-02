// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAuth, GoogleAuthProvider, signInWithPopup, signOut } from 'firebase/auth';

// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
const firebaseConfig = {
  apiKey: "AIzaSyA_p1dJK0pMVWziBHIkOxzy0SYlYQ7J7bM",
  authDomain: "hrms-709e2.firebaseapp.com",
  projectId: "hrms-709e2",
  storageBucket: "hrms-709e2.firebasestorage.app",
  messagingSenderId: "74992699159",
  appId: "1:74992699159:web:5db66b6a4f4ee8b1ef5952"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
const provider = new GoogleAuthProvider();
export { auth, provider, signInWithPopup, signOut };

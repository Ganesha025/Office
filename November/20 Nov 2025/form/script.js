  const firebaseConfig = {
            apiKey: "AIzaSyCIVmmeP7sPcZfnmPmtK8hV6M10b5DdHXw",
            authDomain: "hackathon-capthca.firebaseapp.com",
            projectId: "hackathon-capthca",
            storageBucket: "hackathon-capthca.firebasestorage.app",
            messagingSenderId: "90316977229",
            appId: "1:90316977229:web:3299a07bd1fb8b0e559804",
            measurementId: "G-9ZBDR8PL1F"
        };
    
firebase.initializeApp(firebaseConfig);
  const db = firebase.firestore();
async function UploadData(){

            try {
                for (let i = 0; i < localStorage.length; i++) {
                    const key = localStorage.key(i);
                    const value = localStorage.getItem(key);
                    let data;

                    try {
                        data = JSON.parse(value); // Parse the JSON string
                    } catch (error) {
                        alert(`Error parsing data for key: ${key}`);
                        console.error("Error parsing data for key:", key, error);
                        continue; // Skip the current iteration if parsing fails
                    }

                    // Reference to Firestore document (users collection)
                    const docRef = db.collection("users").doc(key);

                    // Check if document exists, create it if it doesn't
                    const docSnapshot = await docRef.get();
                    if (!docSnapshot.exists) {
                        console.log(`Document for ${key} does not exist. Creating new document.`);
                    }

                    // Upload to Firestore (will create the document if it doesn't exist)
                    await docRef.set(data, { merge: true });

                    console.log(`Uploaded data for: ${key}`);
                }

                alert("All data uploaded successfully!");
                displayLocalStorageData(); // Reload data after upload

            } catch (error) {
                alert(error);
            }
}
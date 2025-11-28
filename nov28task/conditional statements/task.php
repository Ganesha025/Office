<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mission Control Dashboard</title>
<style>
    /* --- Reset & base --- */
    * {margin: 0; padding: 0; box-sizing: border-box; font-family: 'Arial', sans-serif;}
    body {background: linear-gradient(135deg,#1c1c1c,#0f0f0f); color: #fff; min-height: 100vh; display: flex; flex-direction: column; align-items: center; padding: 2rem;}
    h1 {margin-bottom: 1rem; text-align: center; color: #00ffcc;}
    .container {max-width: 700px; width: 100%; background: rgba(255,255,255,0.05); padding: 2rem; border-radius: 15px; box-shadow: 0 0 20px rgba(0,255,200,0.5);}
    .task {margin-bottom: 2rem;}
    label {display: block; margin: 0.5rem 0 0.2rem;}
    input, select, button {width: 100%; padding: 0.5rem; border-radius: 5px; border: none; margin-bottom: 0.5rem;}
    button {background: #00ffcc; color: #000; font-weight: bold; cursor: pointer; transition: 0.3s;}
    button:hover {background: #00cca6;}
    .output {margin-top: 0.5rem; padding: 0.5rem; background: rgba(0,255,200,0.1); border-radius: 5px; min-height: 20px;}
    @media(max-width: 768px){
        .container {padding: 1rem;}
    }
</style>
</head>
<body>

<h1>🕵️ Mission Control Dashboard</h1>
<div class="container">

    <!-- Task 1: Mission Authorization -->
    <div class="task">
        <h2>Task 1: Mission Authorization</h2>
        <label for="agentLevel">Agent Level (1-Trainee, 2-Field, 3-Supervisor):</label>
        <input type="number" id="agentLevel" min="1" max="3">
        <button onclick="authorizeMission()">Check Access</button>
        <div class="output" id="missionOutput"></div>
    </div>

    <!-- Task 2: Target Status -->
    <div class="task">
        <h2>Task 2: Target Status</h2>
        <label for="targetStatus">Target Status (alive / eliminated):</label>
        <input type="text" id="targetStatus">
        <button onclick="checkTarget()">Update Mission Log</button>
        <div class="output" id="targetOutput"></div>
    </div>

    <!-- Task 3: Positive, Negative, Zero -->
    <div class="task">
        <h2>Task 3: Number Check</h2>
        <label for="number">Enter a Number:</label>
        <input type="number" id="number" step="any">
        <button onclick="checkNumber()">Check Sign</button>
        <div class="output" id="numberOutput"></div>
    </div>

    <!-- Task 4: Grade Range -->
    <div class="task">
        <h2>Task 4: Grade Check</h2>
        <label for="grade">Enter Grade (0-100):</label>
        <input type="number" id="grade" min="0" max="100">
        <button onclick="checkGrade()">Assign Letter Grade</button>
        <div class="output" id="gradeOutput"></div>
    </div>

    <!-- Task 5: Login Check -->
    <div class="task">
        <h2>Task 5: Login Credentials</h2>
        <label for="username">Username:</label>
        <input type="text" id="username">
        <label for="password">Password:</label>
        <input type="password" id="password">
        <button onclick="checkLogin()">Login</button>
        <div class="output" id="loginOutput"></div>
    </div>

</div>

<script>
    // --- Task 1 ---
    function authorizeMission() {
        let level = parseInt(document.getElementById('agentLevel').value);
        let output = document.getElementById('missionOutput');
        if(level === 1){
            output.innerText = "Access Denied: Trainees cannot start missions.";
        } else if(level === 2){
            output.innerText = "Access Granted: Field Agent access.";
        } else if(level === 3){
            output.innerText = "Access Granted: Supervisor privileges enabled.";
        } else {
            output.innerText = "Invalid level.";
        }
        localStorage.setItem('agentLevel', level);
    }

    // --- Task 2 ---
    function checkTarget() {
        let status = document.getElementById('targetStatus').value.toLowerCase();
        let output = document.getElementById('targetOutput');
        if(status === "alive"){
            output.innerText = "Mission Log: Target is still alive.";
        } else if(status === "eliminated"){
            output.innerText = "Mission Log: Target has been eliminated.";
        } else {
            output.innerText = "Invalid target status.";
        }
        localStorage.setItem('targetStatus', status);
    }

    // --- Task 3 ---
    function checkNumber() {
        let num = parseFloat(document.getElementById('number').value);
        let output = document.getElementById('numberOutput');
        if(num > 0){
            output.innerText = "The number is positive.";
        } else if(num < 0){
            output.innerText = "The number is negative.";
        } else {
            output.innerText = "The number is zero.";
        }
        localStorage.setItem('number', num);
    }

    // --- Task 4 ---
    function checkGrade() {
        let grade = parseInt(document.getElementById('grade').value);
        let output = document.getElementById('gradeOutput');
        let letter;
        if(grade >= 90) letter = 'A';
        else if(grade >= 80) letter = 'B';
        else if(grade >= 70) letter = 'C';
        else if(grade >= 60) letter = 'D';
        else letter = 'F';
        output.innerText = `Letter Grade: ${letter}`;
        localStorage.setItem('grade', grade);
    }

    // --- Task 5 ---
    const expectedUsername = "agent007";
    const expectedPassword = "secretpass";
    function checkLogin() {
        let username = document.getElementById('username').value;
        let password = document.getElementById('password').value;
        let output = document.getElementById('loginOutput');
        if(username === expectedUsername && password === expectedPassword){
            output.innerText = "Login Successful. Welcome, Agent!";
        } else {
            output.innerText = "Login Failed. Invalid credentials.";
        }
        localStorage.setItem('username', username);
    }
</script>

</body>
</html>

const { app, BrowserWindow, ipcMain } = require('electron');
const path = require('path');

let mainWindow = null;
let examMode = false;
let examCompleted = false; // <-- keep track of completion

function createWindow() {
    mainWindow = new BrowserWindow({
        frame: true,
        fullscreenable: true,
        webPreferences: {
            preload: path.join(__dirname, 'preload.js'),
            contextIsolation: true,
            nodeIntegration: false
        }
    });

    mainWindow.on('leave-full-screen', () => {
        if (examMode) {
            mainWindow.setFullScreen(true);
            mainWindow.setKiosk(true);
        }
    });

    loadHome();

    mainWindow.on('closed', () => { mainWindow = null; });
}

function loadHome() {
    examMode = false;
    mainWindow.setFullScreen(false);
    mainWindow.setFullScreenable(true);
    mainWindow.setKiosk(false);
    mainWindow.setResizable(true);
    mainWindow.setMaximizable(true);
    mainWindow.setMinimizable(true);
    mainWindow.setMenuBarVisibility(true);

    mainWindow.loadFile('index.html');

    mainWindow.webContents.once('did-finish-load', () => {
        // send state to renderer: hide Start button if examCompleted
        mainWindow.webContents.send('exam-state', { hideStartButton: examCompleted });
    });
}

function navigateToExam() {
    if (!mainWindow) return;
    examMode = true;

    mainWindow.setKiosk(true);
    mainWindow.setFullScreenable(false);
    mainWindow.setFullScreen(true);
    mainWindow.setMenuBarVisibility(false);

    mainWindow.loadURL('http://localhost/phpcompiler/index.php');
}

// IPC
ipcMain.handle('start-exam', () => {
    navigateToExam();
});

ipcMain.handle('submit-exam', () => {
    examCompleted = true; // mark as completed
    loadHome();
});

app.whenReady().then(createWindow);

app.on('window-all-closed', () => {
    if (process.platform !== 'darwin') app.quit();
});

const { app, BrowserWindow, ipcMain } = require('electron');
const path = require('path');
const mysql = require('mysql2/promise');

let mainWindow = null;
let examMode = false;

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

    mainWindow.loadFile('index.html');

    mainWindow.on('closed', () => {
        mainWindow = null;
    });
}

// ================= EXAM MODE =================
function navigateToExam() {
    if (!mainWindow) return;

    examMode = true;

    mainWindow.setKiosk(true);
    mainWindow.setFullScreen(true);
    mainWindow.setFullScreenable(false);
    mainWindow.setMenuBarVisibility(false);

    mainWindow.loadURL('http://localhost/savageking/index.php');
}

// ================= HOME MODE =================
function navigateToHome(message) {
    if (!mainWindow) return;

    examMode = false;

    mainWindow.setKiosk(false);
    mainWindow.setFullScreen(false);
    mainWindow.setFullScreenable(true);
    mainWindow.setResizable(true);
    mainWindow.setMaximizable(true);
    mainWindow.setMinimizable(true);
    mainWindow.setMenuBarVisibility(true);

    mainWindow.loadFile('index.html');

    mainWindow.webContents.once('did-finish-load', () => {
        if (message) {
            mainWindow.webContents.send('show-popup', message);
        }
    });
}

// ================= IPC =================
ipcMain.handle('start-exam', () => {
    navigateToExam();
});

ipcMain.handle('submit-exam', () => {
    navigateToHome('Exam submitted successfully!');
});

// ================= DATABASE =================
ipcMain.handle('get-db-schema', async () => {
    const connection = await mysql.createConnection({
        host: 'localhost',
        user: 'root',
        password: '',
        database: 'zensql'
    });

    const [tables] = await connection.query('SHOW TABLES');
    let schema = {};

    for (let row of tables) {
        const tableName = Object.values(row)[0];
        const [columns] = await connection.query(`DESCRIBE ${tableName}`);
        schema[tableName] = columns.map(col => col.Field);
    }

    await connection.end();
    return schema;
});

app.whenReady().then(createWindow);

app.on('window-all-closed', () => {
    if (process.platform !== 'darwin') app.quit();
});

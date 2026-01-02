const { app, BrowserWindow,ipcMain } = require('electron');
const path = require('path');

let mainWindow = null;
let examWindow = null;

function createWindow() {
  const win = new BrowserWindow({
    webPreferences: {
      preload: path.join(__dirname, 'preload.js'),
      contextIsolation: true,
      nodeIntegration: true
    }
  });

  win.loadFile('index.html');
 
  // win.loadURL('http://localhost:8080/exam/start');

  // Uncomment ONLY for development
  // mainWindow.webContents.openDevTools();
}

function exammode(){
  const win = new BrowserWindow({
    fullscreen: true,
    kiosk: true,   // 🚫 Disable ESC, Alt+F4
    webPreferences: {
      preload: path.join(__dirname, 'preload.js'),
      contextIsolation: true,
      nodeIntegration: true
    }
  });

  win.loadURL('http://localhost:8080/exam/start');

  examWindow.on('leave-full-screen',()=>{
    examWindow.setFullScreen(true);
  })

  if (mainWindow) {
    mainWindow.close();
    mainWindow = null;
  }

}

ipcMain.handle('start-exam', () => {
  exammode();
});

/* 🔥 GO BACK TO HOME */
ipcMain.handle('go-home', () => {
  if (examWindow) {
    examWindow.close();
    examWindow = null;
  }
  createWindow();
});


app.whenReady().then(() => {
  createWindow();

  app.on('activate', () => {
    if (BrowserWindow.getAllWindows().length === 0) {
      createWindow();
    }
  });
});

app.on('window-all-closed', () => {
  if (process.platform !== 'darwin') {
    app.quit();
  }
});

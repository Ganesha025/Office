const { contextBridge, ipcRenderer } = require('electron');

contextBridge.exposeInMainWorld('electronAPI', {
    startExam: () => ipcRenderer.invoke('start-exam'),
    submitExam: () => ipcRenderer.invoke('submit-exam'),
    onShowPopup: (callback) => ipcRenderer.on('show-popup', (event, message) => callback(message))
});
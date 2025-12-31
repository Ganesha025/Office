const { contextBridge, ipcRenderer } = require('electron');

contextBridge.exposeInMainWorld('electronAPI', {
    startExam: () => ipcRenderer.invoke('start-exam'),
    submitExam: () => ipcRenderer.invoke('submit-exam'),
    onPopup: (callback) => ipcRenderer.on('show-popup', (event, message) => callback(message))
});


contextBridge.exposeInMainWorld('dbAPI', {
    getSchema: () => ipcRenderer.invoke('get-db-schema')
});

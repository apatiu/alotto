import {app, BrowserWindow, nativeTheme} from 'electron'
import path from 'path'

try {
  if (process.platform === 'win32' && nativeTheme.shouldUseDarkColors === true) {
    require('fs').unlinkSync(require('path').join(app.getPath('userData'), 'DevTools Extensions'))
  }
} catch (_) {
}

let mainWindow

function createWindow() {
  /**
   * Initial window options
   */
  mainWindow = new BrowserWindow({
    width: 1000,
    height: 600,
    useContentSize: true,
    webPreferences: {
      contextIsolation: true,
      // More info: /quasar-cli/developing-electron-apps/electron-preload-script
      preload: path.resolve(__dirname, process.env.QUASAR_ELECTRON_PRELOAD)
    }
  })

  mainWindow.loadURL(process.env.APP_URL)

  if (process.env.DEBUGGING) {
    // if on DEV or Production with debug enabled
    mainWindow.webContents.openDevTools()
  } else {
    // we're on production; no access to devtools pls
    mainWindow.webContents.on('devtools-opened', () => {
      mainWindow.webContents.closeDevTools()
    })
  }

  mainWindow.on('closed', () => {
    mainWindow = null
  })
}

app.on('ready', createWindow)

app.on('window-all-closed', () => {
  if (process.platform !== 'darwin') {
    app.quit()
  }
})

app.on('activate', () => {
  if (mainWindow === null) {
    createWindow()
  }
})

const {Reader} = require('thaismartcardreader.js')
const myReader = new Reader()

process.on('unhandledRejection', (reason) => {
  console.log('From Global Rejection -> Reason: ' + reason);
});

console.log('Waiting For Device !')
myReader.on('device-activated', async (event) => {
  console.log('Device-Activated')
  console.log(event.name)
  console.log('=============================================')
})

myReader.on('error', async (err) => {
  console.log(err)
})

myReader.on('image-reading', (percent) => {
  console.log(percent)
})

myReader.on('card-inserted', async (person) => {
  const cid = await person.getCid()
  const first4Code = await person.getFirst4CodeUnderPicture()
  const last8Code = await person.getLast8CodeUnderPicture()
  const thName = await person.getNameTH()
  const enName = await person.getNameEN()
  const dob = await person.getDoB()
  const issueDate = await person.getIssueDate()
  const expireDate = await person.getExpireDate()
  const address = await person.getAddress()
  const issuer = await person.getIssuer()

  console.log(`CitizenID: ${cid}`)
  console.log(`First4CodeUnderPicture: ${first4Code}`)
  console.log(`Last8CodeUnderPicture: ${last8Code}`)
  console.log(`THName: ${thName.prefix} ${thName.firstname} ${thName.lastname}`)
  console.log(`ENName: ${enName.prefix} ${enName.firstname} ${enName.lastname}`)
  console.log(`DOB: ${dob.day}/${dob.month}/${dob.year}`)
  console.log(`Address: ${address}`)
  console.log(`IssueDate: ${issueDate.day}/${issueDate.month}/${issueDate.year}`)
  console.log(`Issuer: ${issuer}`)
  console.log(`ExpireDate: ${expireDate.day}/${expireDate.month}/${expireDate.year}`)

  console.log('=============================================')
  console.log('Receiving Image')
  const photo = await person.getPhoto()
  console.log(`Image Saved to ${path.resolve('')}/${cid}.bmp`)
  console.log('=============================================')
  // const fileStream = fs.createWriteStream(`${cid}.bmp`)
  // const photoBuff = Buffer.from(photo)
  // fileStream.write(photoBuff)
  // fileStream.close()
})

myReader.on('card-removed', () => {
  console.log('card removed')
})

myReader.on('device-deactivated', () => {
  console.log('device-deactivated')
})

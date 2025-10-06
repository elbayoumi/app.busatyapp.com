var express = require('express');
var router = express.Router();
const { exec } = require('child_process');
const path = require('path');
const targetDirectory = path.join(__dirname, '../');

/* GET home page. */
router.get('/', function(req, res, next) {
  res.render('index', { title: 'Expresss' });
});

router.get('/test-socket', function(req, res, next) {
  res.render('test-socket', { title: 'Expresss' });
});

router.get('/git_pull', (req, res) => {
  // console.log(`Source Path: ${targetDirectory}`);
  const command = 'git pull'; // Replace with your command
  // res.json(targetDirectory)

  exec(command, { cwd: targetDirectory }, (error, stdout, stderr) => {
    if (error) {
      console.error(`Error: ${error}`);
      return res.status(500).json({ data: '', message: `Error: ${error.message}`, error: true });
    }
    if (stderr) {
      console.error(`stderr: ${stderr}`);
      return res.status(500).json({ data: '', message: `stderr: ${stderr}`, error: true });
    }
    console.log(`stdout: ${stdout}`);
    res.json({ data: stdout, message: 'Command executed successfully', error: false });
  });
});


module.exports = router;

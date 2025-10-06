const express = require('express');
const router = express.Router();

router.get('/', (req, res) => {
  res.send('Sockets endpoint');
});


module.exports = router;

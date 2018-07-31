var express = require('express');
var bodyParser = require('body-parser');
var cors = require('cors');
var methodOverride = require('method-override');
const path = require('path');
var httpsServer = require('https');
var httpServer = require('http');
const fs = require('fs');

var httpsApp = express();
var httpApp = express();

const httpsOpt = {
    cert: fs.readFileSync('/etc/letsencrypt/live/www.feesimpletracker.io/fullchain.pem'),
    key: fs.readFileSync('/etc/letsencrypt/live/www.feesimpletracker.io/privkey.pem')
};

httpsApp.set('port', 443); // default port for https is 443

httpApp.set('port', 80); // default port for http is 80
httpApp.get("*", function (req, res, next) {
	let host = req.headers.host;
	if (host.match(/^www/) !== null ) {
    host = host.replace(/^www\./, '');
  }	
	res.redirect("https://" + host + req.path);
});

//Middleware
httpsApp.use(bodyParser.json());
httpsApp.use(bodyParser.urlencoded({extended: false}));
httpsApp.use(methodOverride('X-HTTP-Method-Override'));
httpsApp.use(cors());

//Prevent click jacking
httpsApp.use(require('helmet')());

//Set static path for assets
httpsApp.use(express.static(__dirname + '/dist'));

httpsApp.get('*', function(req,res) {
    res.sendFile(path.join(__dirname+'/dist/index.html'));
});

httpServer.createServer(httpApp).listen(httpApp.get('port'), function() {
	console.log('Express HTTP server listening on port ' + httpApp.get('port'));
});

httpsServer.createServer(httpsOpt, httpsApp).listen(httpsApp.get('port'), function() {
	console.log('Express HTTPS server listening on port ' + httpsApp.get('port'));
});

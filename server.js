// THIS APP USES EXPRESS TO DELIVER CONTENT AT A RELIABLE SPEED //
let express = require('express');
let app = express();
const path = require("path");
const cors = require('cors');

let corsOptions = {
    origin: '*',
    optionsSuccessStatus: 200 // some legacy browsers (IE11, various SmartTVs) choke on 204
}
app.use(cors(corsOptions));

app.use("/", express.static(path.join(__dirname)));

// listen for requests   :)
let listener = app.listen(process.env.PORT, function() {
    console.log("The app is now active.");
    console.log("Local address: http://127.0.0.1:" + listener.address().port);
});
require('./bootstrap');
window.Vue = require('vue');

import router from "./router"
import MainApp from "./MainApp"

window.MainApp = MainApp;
window.router = router;


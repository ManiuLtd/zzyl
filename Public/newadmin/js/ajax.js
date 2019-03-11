Ajax = function() {
    var xmlhttp;
    var failed;
    var method;
    var responseStatus;
    var url;

    this.xmlhttp = null;
    this.failed = true;
    this.method = 'GET';
    this.createAJAX();
}

Ajax.prototype.createAJAX = function() {
    try {
        this.xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e1) {
        try {
            this.xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e2) {
            this.xmlhttp = null;
        }
    }

    if (!this.xmlhttp) {
        if (typeof XMLHttpRequest != "undefined") {
            this.xmlhttp = new XMLHttpRequest();
        } else {
            this.failed = true;
        }
    }
};

Ajax.prototype.runAJAX = function(urlstring, querystring, method, async) {
    var self = this;
    this.url = urlstring;
    this.query = querystring;

    if (method.toLowerCase() == 'get') {
        this.xmlhttp.open("GET", this.url, async);
        this.xmlhttp.send(null);
    } else if (method.toLowerCase() == 'post') {
        this.xmlhttp.open("POST", this.url, async);
        this.xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
        this.xmlhttp.send(this.query);
    }

    this.xmlhttp.onreadystatechange = function() {
        if (self.xmlhttp.readyState == 4 && self.xmlhttp.status == 200) {
            if (self.callback) self.callback(self.xmlhttp.responseText);
        }
    };

    if (!async) {
        if (self.xmlhttp.readyState == 4 && self.xmlhttp.status == 200) {
            if (self.callback) self.callback(self.xmlhttp.responseText);
        }
    }
};

var regReturnEvent = function(func) {
    document.onkeydown = function(event) {
        event = (event) ? event : ((window.event) ? window.event : "");
        if (event.keyCode == 13) {
            eval(func + '()');
        }
    }
};
"use strict";

const app = require('express')();
const morgan = require('morgan')

app.use(morgan('combined'))

app.get('/200', function(req, res) {
    res.end('<html><body><p>ok</p></body></html>');
});

app.get('/301', function(req, res) {
    res.redirect(301,'/ok');
});

app.get('/404', function (req, res) {
    res.status(404).end();
})

app.get('/page1', function(req, res) {
    res.end('<html><body><a href="/page2">foo</a></body></html>');
});
app.get('/page2', function(req, res) {
    res.end('<html><body><p>ok</p></body></html>');
});

let server = app.listen(8080, function () {
    const host = 'localhost';
    const port = server.address().port;

    console.log('Testing server listening at http://%s:%s', host, port);
});
/*
 *
 */


//Dependencies

var express = require('express');
var http = require('http');
var app = express();
var socket = require('socket.io');
var path = require('path');
var fs=require('fs');
//Server instance
var server = http.createServer(app);
//build a socket using the instance of the server
var io=socket(server);

parser = new require('xml2json');


server.listen(8080,function(){
    console.log("Sunucu 8080 portunu dinliyor...");
});


io.on('connection', function(socket) {
    console.log("istemci bağlandı...");
    // Duyurular.xml dosyasını izle
    fs.watchFile(__dirname + '/Duyurular.xml', function(curr, prev) {
        fs.readFile(__dirname + '/Duyurular.xml', function(err, dosyaIcerigi) {
            if (err) throw err;
            var dosyaIcerigiJSON = parser.toJson(dosyaIcerigi);
            // yeni duyuruyu tüm istemcilere gönder
            socket.emit('yeniDuyuru', dosyaIcerigiJSON);
        });
    });



  //Mesajlaşma işlemi için
  socket.on('mesaj', function(msg)
  {
    io.emit('mesaj', msg);
  });


});
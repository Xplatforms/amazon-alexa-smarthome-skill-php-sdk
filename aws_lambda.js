'use strict';

//Example URL of your webserver hosting php api for amazon alexa smart home skill
//https://yourdomain.com/smarthome_skill/
var https = require('https');
var REMOTE_CLOUD_BASE_PATH = "/smarthome_skill/";
var REMOTE_CLOUD_HOSTNAME = "yourdomain.com";

function log(title, msg) 
{
    console.log(`[${title}] ${msg}`);
}

function getDevicesFromCloud(str, callbackfunc)
{
    var options = {
            hostname: REMOTE_CLOUD_HOSTNAME,
            port: 443,
            path: REMOTE_CLOUD_BASE_PATH+'discovery.php',
            method: 'POST',
            headers: 
            {
                'Content-Type': 'application/json',
                accept: '*/*' // Warning! Accepting all headers in production could lead to security problems.
    }};
    
    var callback = function(response){
            var str = '';
            response.on('data', function(chunk) {
                // TODO: Add string limit here
                str += chunk.toString('utf-8');
            });
    
            response.on('end', function() {
                log('DEBUG', 'Response from Cloud: '+ str);
                callbackfunc(JSON.parse(str));
            });
    }
    
    var request = https.request(options, callback);
    request.write(str);
    request.end();
}

//Get amazon user profile id. 
function getAmazonApiOptions(token)
{
var options = {
            hostname: 'api.amazon.com',
            port: 443,
            path: '/user/profile?access_token='+token,
            method: 'GET',
            headers: 
            {
                accept: '*/*' // Warning! Accepting all headers in production could lead to security problems.
    }};

    return options;
}

function isValidToken(token, callbackfunc) 
{
    var options = getAmazonApiOptions(token);
    var callback = function(response){
            var str = '';
            response.on('data', function(chunk) {
                // TODO: Add string limit here
                str += chunk.toString('utf-8');
            });
    
            response.on('end', function() {
                var profile = JSON.parse(str);
                callbackfunc(profile);
            });
    }
    
    var request = https.request(options, callback);
    request.end();
}

function handleDiscovery(request, callback) 
{
    const userAccessToken = request.directive.payload.scope.token.trim();
    if (!userAccessToken) 
    {
        const errorMessage = `Discovery Request failed. Invalid access token: ${userAccessToken}`;
        log('ERROR', errorMessage);
        callback(new Error(errorMessage));
    }
    
    isValidToken(userAccessToken, function(amazon_user)
    {
        if(!amazon_user)
        {
            const errorMessage = `Discovery Request failed. Invalid access token: ${userAccessToken}`;
            log('ERROR', errorMessage);
            callback(new Error(errorMessage));
            return;
        }
        
        //add amazon user profile id to request for using it later in php api
        request['amazon_user'] = amazon_user;
        
        getDevicesFromCloud(JSON.stringify(request), function(xresp)
        {
            log('DEBUG', 'JSONRESPONSE from Cloud:  '+ JSON.stringify(xresp));
            callback(null, xresp);
        });
    });
}

exports.handler = (request, context, callback) => {

    switch (request.directive.header.namespace) {
        case 'Alexa.Discovery':
            handleDiscovery(request, callback);
            break;
/*
        case 'Alexa.ConnectedHome.Control':
            handleControl(request, callback);
            break;
*/
        default: {
            const errorMessage = `No supported namespace: ${request.directive.header.namespace}`;
            log('ERROR', errorMessage);
            callback(new Error(errorMessage));
        }
    }    
};

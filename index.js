const express = require('express')
const middleware = require('@line/bot-sdk').middleware
console.log("Mulai COk");
const app = express()

const config = {
  channelAccessToken: "+xxHRu/hJ9ApTpJFcEXdyI+xhEitXaMNbJO/JKp67ae7WiZb0CgkTx7VY8W/GxD9HQySD4t5SfK9ZzfRZ6zibjSjtGMIat07PlnMfyNbItHWl57mX7YcK7FgJx9VqzUWtIvnl/QwFl1cTISpmdShngdB04t89/1O/w1cDnyilFU=",
  channelSecret: "3eb4814fa7b19674eb17eb2790cb73b2",
}

app.post('/webhook', middleware(config), (req, res) => {
  res.json(req.body.events) // req.body will be webhook event object
})

app.listen(8080)

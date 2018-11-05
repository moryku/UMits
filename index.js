const { LineBot } = require('bottender');
const { createServer } = require('bottender/express');

const bot = new LineBot({
  channelSecret: '3eb4814fa7b19674eb17eb2790cb73b2',
  accessToken: '+xxHRu/hJ9ApTpJFcEXdyI+xhEitXaMNbJO/JKp67ae7WiZb0CgkTx7VY8W/GxD9HQySD4t5SfK9ZzfRZ6zibjSjtGMIat07PlnMfyNbItHWl57mX7YcK7FgJx9VqzUWtIvnl/QwFl1cTISpmdShngdB04t89/1O/w1cDnyilFU=',
});

bot.onEvent(async context => {
  await context.sendText('Hello World');
});

const server = createServer(bot);

server.listen(5000, () => {
  console.log('server is running on 5000 port...');
});
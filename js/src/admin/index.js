import app from 'flarum/app';

import WebsocketPage from './components/WebsocketPage';

app.initializers.add('kyrne-websocket', app => {

  app.extensionData
    .for('kyrne-websocket')
    .registerPage(WebsocketPage);
});


import app from 'flarum/app';
import { extend } from 'flarum/extend';

import DashboardPage from 'flarum/components/DashboardPage';

import WebsocketPage from './components/WebsocketPage';
import WebsocketStatsWidget from "./components/WebsocketStatsWidget";

app.initializers.add('kyrne-websocket', app => {

  app.extensionData
    .for('kyrne-websocket')
    .registerPage(WebsocketPage);

  extend(DashboardPage.prototype, 'availableWidgets', widgets => {
    widgets.add('statistics', <WebsocketStatsWidget/>, 20);
  });
});


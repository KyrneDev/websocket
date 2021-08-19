import app from 'flarum/app';
import { extend } from 'flarum/extend';

import DashboardPage from 'flarum/components/DashboardPage';

import WebsocketPage from './components/WebsocketPage';
import WebsocketStatsWidget from "./components/WebsocketStatsWidget";
import RegisterWidget from "../common/Widget/RegisterWidget";

app.initializers.add('kyrne-websocket', app => {

  app.extensionData
    .for('kyrne-websocket')
    .registerPage(WebsocketPage);

  extend(DashboardPage.prototype, 'availableWidgets', widgets => {
    widgets.add('websocketstatistics', <WebsocketStatsWidget/>, 15);
  });

  if (app.initializers.has('afrux/forum-widgets-core')) {
    RegisterWidget(app);
  }
});


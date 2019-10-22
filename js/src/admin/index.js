import { extend } from 'flarum/extend';
import app from 'flarum/app';

import PusherSettingsModal from './components/PusherSettingsModal';

app.initializers.add('hyn-websocket', app => {
  app.extensionSettings['hyn-websocket'] = () => app.modal.show(new PusherSettingsModal());
});

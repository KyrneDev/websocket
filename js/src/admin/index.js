import { extend } from 'flarum/extend';
import app from 'flarum/app';

import PusherSettingsModal from './components/PusherSettingsModal';

app.initializers.add('kyrne-websocket', app => {
  app.extensionSettings['kyrne-websocket'] = () => app.modal.show(new PusherSettingsModal());
});
